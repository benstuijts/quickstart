<?php

$app->get('/cms', $admin(), function() use ($app){
    $app->render('cms/overview.php', [
        'title' => '',
        'description' => '',
        'keywords' => '',
        'author' => '',
        'posts' => $app->post->orderBy('created_at', 'DESC')->get()    
    ]);
})->name('cms');

$app->get('/cms/new', $admin(), function() use ($app){
    $app->render('cms/new.php',[
        'title' => '',
        'description' => '',
        'keywords' => '',
        'author' => '',    
        'imageList' => [
            ['name' => 'naam', 'url' => 'urlFilename' ],
            ['name' => 'naam2', 'url' => 'urlFilename']
        ]
    
    ]);
})->name('cms.new');

$app->post('/cms/new', $admin(), function() use ($app){
    $request = $app->request;
    $body = $request->post('body');
    
    if($app->post->create([
        'title' => $request->post('title'),
        'subtitle' => $request->post('subtitle'),
        'newsgroup' => $request->post('newsgroup'),
        'author' => $app->auth->id,
        'url' => $request->post('url'),
        'image' => $request->post('image'),
        'tags' => $request->post('tags'),
        'audience' => $request->post('audience'),
        'status' => $request->post('status'),
        'body' => htmlspecialchars($body)
    ])) {
        $app->flash('success', 'Artikel is succesvol opgeslagen');  
    } else {
        $app->flash('danger', 'Artikel is NIET opgeslagen');    
    }
    
    $app->response->redirect($app->urlFor('cms'));
})->name('cms.new.post');

$app->post('/cms/edit', $admin(), function() use ($app){
    $request = $app->request;
    
    $id = $request->post('id');
    $body = $request->post('body');
    
    $post = $app->post->where('id', $id)->get()->first();
    if($post->update([
        'title' => $request->post('title'),
        'subtitle' => $request->post('subtitle'),
        'url' => $request->post('url'),
        'image' => $request->post('image'),
        'tags' => $request->post('tags'),
        'audience' => $request->post('audience'),
        'status' => $request->post('status'),
        'body' => htmlspecialchars($body)
    ])) {
        $app->flash('success', 'Artikel is succesvol opgeslagen');  
    } else {
        $app->flash('danger', 'Artikel is NIET opgeslagen');    
    }
    
    $app->response->redirect('../' . $post->url);
    //$app->response->redirect($app->urlFor('cms'));
})->name('cms.edit');

$app->get('/cms/dashboard', $admin(), function() use ($app){
    $app->render('cms/dashboard.php', [
        'posts' => $app->post->orderBy('created_at', 'DESC')->get()    
    ]);
})->name('cms.dashboard');

$app->get('/cms/publish(/:id)', $admin(), function($id) use ($app){
    $post = $app->post->where('id', $id)->get()->first();
    
    if($post->publish()) { 
        $app->flash('success', 'Artikel is succesvol gepubliceerd');
    } else {
        $app->flash('danger', 'Artikel is NIET gepubliceerd');
    };
    $app->response->redirect($app->urlFor('cms'));
})->name('cms.publish');

$app->get('/cms/mute(/:id)', $admin(), function($id) use ($app){
    $post = $app->post->where('id', $id)->get()->first();
    
    if($post->mute()) { 
        $app->flash('success', 'Artikel is als concept opgeslagen');
    } else {
        $app->flash('danger', 'Artikel is NIET als concept opgeslagen');
    };
    $app->response->redirect($app->urlFor('cms'));
})->name('cms.mute');

$app->get('/cms/delete(/:id)', $admin(), function($id) use ($app){
    $post = $app->post->where('id', $id)->get()->first();
    
    if($post->delete()) { 
        $app->flash('success', 'Artikel is succesvol verwijderd');
    } else {
        $app->flash('danger', 'Artikel is NIET verwijderd');
    };
    $app->response->redirect($app->urlFor('cms'));
})->name('cms.delete');

$app->get('/cms/image/upload', $admin(), function () use ($app) {
    $app->render('cms/imageupload.php',[
        'title' => '',
        'description' => '',
        'keywords' => '',
        'author' => '',        
    ]);
    
    
})->name('cms.image.upload');

$app->post('/cms/image/upload', $admin(), function() use ($app) {
    $request = $app->request;
    if (isset($_FILES["image"]["name"]) && $_FILES["image"]["tmp_name"] != ""){
        
        $description = $request->post('description');
        $slug = $request->post('slug');
        
        
        $fileName   = $_FILES["image"]["name"];
        $fileTmpLoc = $_FILES["image"]["tmp_name"];
    	$fileType   = $_FILES["image"]["type"];
    	$fileSize   = $_FILES["image"]["size"];
    	$fileErrorMsg = $_FILES["image"]["error"];
    	$kaboom = explode(".", $fileName);
    	$fileExt = end($kaboom);
    	list($width, $height) = getimagesize($fileTmpLoc);
        
        if($width < 10 || $height < 10){
		    $app->flash('warning', 'Bestand bevat geen bruikbare image. Probeer een andere plaatje.');
		    $app->response->redirect($app->urlFor('cms') );
	    }
    	$db_file_name = rand(100000000000,999999999999).".".$fileExt;
    	$db_file_name = $slug.".".$fileExt;
    	if($fileSize > 1048576*4) {
    		$app->flash('warning', 'Gebruik een bestand, kleiner dan 4MB.');
		    $app->response->redirect($app->urlFor('cms') );
    	} else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
    		$app->flash('warning', 'Gebruik een gif, jpg of png plaatje.');
		    $app->response->redirect($app->urlFor('cms') );
    	} else if ($fileErrorMsg == 1) {
    		$app->flash('warning', 'Er is iets mis gegaan, probeer het later nog eens.');
		    $app->response->redirect($app->urlFor('cms'));
    	}
    	
    	$website = $app->config->get('app.name');
    	
    	$uploadLocation = INC_ROOT . "/public/images/{$website}";
    	
    	$url = $app->config->get('app.url') . "/images/{$website}/{$db_file_name}";
    	
    	
    	
    	// directory maken!
    	if (!file_exists($uploadLocation)) {
    	    $oldmask = umask(0);
            mkdir($uploadLocation, 0777, true);
            umask($oldmask);
        }
    	
	    
	    $moveResult = move_uploaded_file($fileTmpLoc, "{$uploadLocation}/{$db_file_name}");
	    
    	if ($moveResult != true) {
    		$app->flash('warning', 'File is niet gekopieerd naar de juiste directory.');
		    $app->response->redirect($app->urlFor('cms'));
    	} else {
    	    
    	    if($app->image->create([
    	        'url' => $url,
    	        'filename' => $db_file_name, 
    	        'description' => $description,
    	        'slug' => $slug,
    	        'status' => 1
    	    ])) {
    	        $app->flash('success', 'Nieuwe afbeelding is geupload en opgeslagen in database. Image url: '.$url);
		        $app->response->redirect($app->urlFor('cms') );
    	    }else {
    	        $app->flash('danger', 'Afbeelding geupload, maar NIET opgeslagen in database.');
		        $app->response->redirect($app->urlFor('cms') );
    	    };
    	    
    	}
    	
    }
})->name('cms.image.upload.post');

$app->get('/cms/images', $admin(), function() use ($app) {
    $app->render('cms/images.php', [
        'title' => '',
        'description' => '',
        'keywords' => '',
        'author' => '',
        'images' => $app->image->get()   
    ]);
})->name('cms.images');

$app->get('/cms/comments/pending', $admin(), function() use ($app) {
    $app->render('cms/commentsPending.php',[
        'title' => '',
        'description' => '',
        'keywords' => '',
        'author' => '',
        'commentsToAccept' => $app->comment->where('status', 0)->get(),
        'acceptedComments' => $app->comment->where('status', 1)->orderBy('created_at', 'DESC')->get()
    ]);
})->name('cms.comments.pending');