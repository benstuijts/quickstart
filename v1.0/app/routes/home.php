<?php

$app->get('/', function() use ($app){
    $app->render('info.php',[
        'pendingComments' => $app->comment->where('status', 0)->get()->count()
    ]);
})->name('home');