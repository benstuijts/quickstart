<?php 

$app->get('/activate', $guest(), function() use ($app) {
    
    $request = $app->request;
    $email = $request->get('email');
    $identifier = $request->get('identifier');

    $hashedIdentifier = $app->hash->createHash($identifier);
    
    $user = $app->user
        ->where('email', $email)
        ->where('active', false)
        ->first();
    
    //echo $app->hash->hashCheck($user->active_hash, $hashedIdentifier);
    
    if( !$user || !$app->hash->hashCheck($user->active_hash, $hashedIdentifier) ) {
        $app->flash('warning', 'There was a problem activating your account.');
        $app->response->redirect($app->urlFor('home'));
    } else {
        $user->activateAccount();
        $app->flash('success', 'Your account has been activated and you can sign in.');
        $app->response->redirect($app->urlFor('home'));
    }
                    

})->name('activate');

?>