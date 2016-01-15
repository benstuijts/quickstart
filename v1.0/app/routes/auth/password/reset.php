<?php

$app->get('/password-reset', $guest(), function() use ($app){
    
    $request = $app->request();
    
    $email = $request->get('email');
    $identifier = $request->get('identifier');
    
    $hashedIdentifier = $app->hash->createHash($identifier);
    
    $user = $app->user->where('email', $email)->first();
    
    if(!user) {
        $app->flash('warning', 'No user found.');
        $app->response->redirect($app->urlFor('home'));
    }
    
    if(!$user->recover_hash) {
        $app->flash('warning', 'Password already reset.');
        $app->response->redirect($app->urlFor('home'));
    }
    
    
    if(!$app->hash->hashCheck($user->recover_hash, $hashedIdentifier)) {
        $app->flash('warning', 'Incorrect link.');
        $app->response->redirect($app->urlFor('home'));
    }
    
    $app->render('auth/password/reset.php', [
        'email' => $user->email,
        'identifier' => $identifier
    ]);
    
    
})->name('password.reset');

$app->post('/password-reset', $guest(), function() use ($app){
    
    $request = $app->request();
    
    $email = $request->get('email');
    $identifier = $request->get('identifier');
    
    $password = $request->post('password');
    $passwordConfirm = $request->post('password_confirm');
    
    $hashedIdentifier = $app->hash->createHash($identifier);
    
    $user = $app->user->where('email', $email)->first();
    
    if(!user) {
        $app->flash('warning', 'No user found.');
        $app->response->redirect($app->urlFor('home'));
    }
    
    if(!$user->recover_hash) {
        $app->flash('warning', 'Password already reset.');
        $app->response->redirect($app->urlFor('home'));
    }
    
    if(!$app->hash->hashCheck($user->recover_hash, $hashedIdentifier)) {
        $app->flash('warning', 'Incorrect link.');
        $app->response->redirect($app->urlFor('home'));
    }
    
    
    $v = $app->validation;
    
    $v->validate([
        'password' => [$password, 'required|min(6)'],
        'password_confirm' => [$passwordConfirm, 'required|matches(password)']
    ]);
    
    if($v->passes()) {
        
        $user->update([
            'password' => $app->hash->password($password),
            'recover_hash' => null
        ]);
        
        $app->flash('success', 'Your password has been reset and you can now login with your new password.');
        $app->response->redirect($app->urlFor('login'));
    } else {
        $app->render('auth/password/reset.php', [
            'errors' => $v->errors(),
            'email' => $user->email,
            'identifier' => $identifier
        ]);
    }
    
})->name('password.reset.post');


?>