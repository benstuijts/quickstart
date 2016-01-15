<?php




$app->get('/change-password', $authenticated(), function() use ($app){
    
    $app->render('user/profile.php', [
        'user' => $app->auth,
        'option' => 'changePassword'
    ]);
})->name('password.change');

$app->post('/change-password', $authenticated, function() use ($app){
    
    $user = $app->auth;

    $request = $app->request;
    $passwordOld = $app->request->post('password_old');
    $password = $app->request->post('password');
    $passwordConfirm = $app->request->post('password_confirm');
    
    $v = $app->validation;
    
    $v->validate([
        'password_old' => [$passwordOld, 'required|matchesCurrentPassword'],
        'password' => [$password, 'required|min(6)'],
        'password_confirm' => [$passwordConfirm, 'required|matches(password)']
    ]);
    
    if($v->passes()) {
        
        $user->update([
            'password' => $app->hash->password($password)  
        ]);
        
        $app->mail->send('email/auth/password/changed.php', [], function($message) use ($user) {
            $message->to($user->email);
            $message->subject('You changed your password.');
        });
        
        $app->flash('success', 'Wachtwoord is gewijzigd.');
        $app->response->redirect($app->urlFor('home'));
        
    } else {
        
        $app->render('user/profile.php', [
            'user' => $user,
            'option' => 'changePassword',
            'errors' => $v->errors()
        ]);
    }
    
})->name('password.change.post');

?>