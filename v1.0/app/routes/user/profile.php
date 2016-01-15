<?php

$app->get('/user(/:username)', $authenticated(), function($username = '') use ($app) {
    if($username == '') {
        
        if($app->auth) {
            $username = $app->auth->username;
        } else {
            $app->response->redirect($app->urlFor('home'));
        }
    }

    $user = $app->user
        ->where('username', $username)
        ->first();
    if(!$user) {
        $app->flash('warning', 'Deze gebruiker is onbekend.');
        $app->response->redirect($app->urlFor('home'));
    }
    /* favoriete posts ophalen */
    $allPosts = $app->post->where('likes' , '!=', '')->get();
    $myFavoritePosts = [];
    
    foreach($allPosts as $post) {
        
        if($post->isLikedByMe($app->auth->id)) {
            array_push($myFavoritePosts, $post);
        }
        
    }
    
    $app->render('user/profile.php', [
        'user' => $user,
        'myFavoritePosts' => $myFavoritePosts,
    ]);
    
})->name('user.profile');

$app->get('/users', function() use ($app){
    $users = $app->user->where('active', true)->get();
    
    $app->render('user/users.php', [
        'users' => $users
        ]);
})->name('users');

$app->post('/user/avatar/upload', function() use ($app) {
    if (isset($_FILES["avatar"]["name"]) && $_FILES["avatar"]["tmp_name"] != ""){
     
        $fileName   = $_FILES["avatar"]["name"];
        $fileTmpLoc = $_FILES["avatar"]["tmp_name"];
    	$fileType   = $_FILES["avatar"]["type"];
    	$fileSize   = $_FILES["avatar"]["size"];
    	$fileErrorMsg = $_FILES["avatar"]["error"];
    	$kaboom = explode(".", $fileName);
    	$fileExt = end($kaboom);
    	list($width, $height) = getimagesize($fileTmpLoc);
        
        if($width < 10 || $height < 10){
		    $app->flash('warning', 'Bestand bevat geen bruikbare avatar. Probeer een andere plaatje.');
		    $app->response->redirect($app->urlFor('user.profile') . "/{$app->auth->username}");
	    }
    	$db_file_name = rand(100000000000,999999999999).".".$fileExt;
    	if($fileSize > 1048576*2) {
    		$app->flash('warning', 'Gebruik een bestand, kleiner dan 2MB.');
		    $app->response->redirect($app->urlFor('user.profile') . "/{$app->auth->username}");
    	} else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
    		$app->flash('warning', 'Gebruik een gif, jpg of png plaatje.');
		    $app->response->redirect($app->urlFor('user.profile') . "/{$app->auth->username}");
    	} else if ($fileErrorMsg == 1) {
    		$app->flash('warning', 'Er is iets mis gegaan, probeer het later nog eens.');
		    $app->response->redirect($app->urlFor('user.profile') . "/{$app->auth->username}");
    	}
    	
    	$uploadLocation = "../public/uploads/{$app->user->getUsername()}/{$app->user->avatar}";
    	
    	echo $app->auth->id; 
    	
    	// directory maken!
    	if (!file_exists("../public/uploads/{$app->auth->getUsername()}")) {
            $oldmask = umask(0);
            mkdir("../public/uploads/{$app->auth->getUsername()}", 0777, true);
            umask($oldmask);
        }
    	
    	if($app->user->avatar != ""){
		    $picurl = "../public/uploads/{$app->auth->getUsername()}/{$app->user->avatar}"; 
	        if (file_exists($picurl)) { unlink($picurl); }
	    }
	    
	    $moveResult = move_uploaded_file($fileTmpLoc, "../public/uploads/{$app->auth->getUsername()}/{$db_file_name}");
    	if ($moveResult != true) {
    		$app->flash('warning', 'Er is iets mis gegaan, probeer het later nog eens.');
		    $app->response->redirect($app->urlFor('user.profile') . "/{$app->auth->username}");
    	} else {
    	    
    	    $app->auth->update([
    	        'avatar' => $db_file_name   
            ]);
    	    
    	    
    	    $app->flash('success', 'Nieuwe avatar is geupload.');
		    $app->response->redirect($app->urlFor('user.profile') . "/{$app->auth->username}");
    	}
    	
    }
})->name('user.avatar.upload');

$app->get('/user/avatar/update', $authenticated(), function() use ($app){
    $app->render('user/profile.php', [
        'user' => $app->auth,
        'option' => 'user.avatar.update'
    ]);
})->name('user.avatar.update');

$app->get('/user/profile/update', $authenticated(), function() use ($app){
    $app->render('user/profile.php', [
        'user' => $app->auth,
        'option' => 'user.profile.update'
    ]);
})->name('user.profile.update');

$app->post('/user/profile/update', $authenticated(), function() use ($app){
    
    $request = $app->request;
    
    $email      = $request->post('email');
    $username   = $request->post('username');
    $firstName  = $request->post('first_name');
    $lastName   = $request->post('last_name');
    $city       = $request->post('city');
    $club        = $request->post('club');
    $job        = $request->post('job');
    $tags       = $request->post('tags');
    
    $v = $app->validation;
    
    $v->validate([
        'email'             => [$email, 'required|email|uniqueEmail'],
        'username'          => [$username, 'required|alnumDash|max(20)|min(4)|uniqueUsername'],
        'first_name'        => [$firstName, 'alpha|max(50)'],
        'last_name'         => [$lastName, 'alpha|max(50)'],
        'city'              => [$city, 'max(255)'],
        'club'               => [$club, 'max(255)'],
        'job'               => [$job, 'max(255)'],
        'tags'              => [$tags, 'max(255)']
    ]);
    
    if($v->passes()) {
        
        $app->auth->update([
            'email' => $email,
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'city' => $city,
            'club' => $club,
            'job' => $job,
            'tags' => $tags
        ]);
        
        $app->flash('success', 'Wijzigingen succesvol opgeslagen.');
        $app->response->redirect($app->urlFor('user.profile') . "/{$app->auth->username}");
        
    } else {
        $app->flash('warning', 'Er is iets misgegaan');
    
        $app->render('user/profile.php', [
            'errors' => $v->errors(),
            'user' => $app->auth,
            'request' => $request
        
        ]);  
    }
    
})->name('user.profile.update.post');

function img_resize($target, $newcopy, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
    $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
           $w = $h * $scale_ratio;
    } else {
           $h = $w / $scale_ratio;
    }
    $img = "";
    $ext = strtolower($ext);
    if ($ext == "gif"){ 
      $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
      $img = imagecreatefrompng($target);
    } else { 
      $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
    imagejpeg($tci, $newcopy, 84);
}