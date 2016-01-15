<?php

define('INC_ROOT', dirname(__DIR__));

use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

use Mailgun\Mailgun; 

use Noodlehaus\Config;
use RandomLib\Factory as RandomLib;

use BenStuijts\User\User;
use BenStuijts\Post\Post;
use BenStuijts\Post\Comment;
use BenStuijts\Post\Image;
use BenStuijts\Helpers\Hash;
use BenStuijts\Validation\Validator;
use BenStuijts\Middleware\BeforeMiddleware;
use BenStuijts\Middleware\CsrfMiddleware;

use BenStuijts\Mail\Mailer;

session_cache_limiter(false);
session_start();





//echo INC_ROOT;

require INC_ROOT . '/vendor/autoload.php';

/* Setup */
$app = new Slim([
    'mode' => file_get_contents(INC_ROOT . '/mode.php'),
    'view' => new Twig(),
    'templates.path' => INC_ROOT . '/views'
]);

/* Middleware */
$app->add(new BeforeMiddleware);
$app->add(new CsrfMiddleware);

/* Configuration */

ini_set('file_uploads', 'On');

if($app->mode == 'development') {
    ini_set('display_errors', 'On');
}

$app->configureMode($app->config('mode'), function() use ($app){
    $app->config = Config::load(INC_ROOT . "/app/config/{$app->mode}.php");
});


/* Controllers */
require 'database.php';
require 'filters.php';
require 'routes.php';

/* Adding Containers */
$app->auth = false;

$app->container->set('user', function() {
    return new User;
});

$app->container->set('post', function() {
    return new Post;
});

$app->container->set('comment', function() {
    return new Comment;
});

$app->container->set('image', function() {
    return new Image;
});

$app->container->singleton('hash', function() use ($app) {
    return new Hash($app->config);
});

$app->container->singleton('validation', function() use ($app) {
    return new Validator($app->user, $app->hash, $app->auth);
});

$app->container->singleton('randomlib', function() {
    $factory = new RandomLib;
    return $factory->getMediumStrengthGenerator();
});

$app->container->singleton('mail', function() use ($app){
    $mailgun = new Mailgun($app->config->get('mail.secret'));
    return new Mailer($app->view, $app->config, $mailgun);
});

/* Setting up view engine (Twig) */
$view = $app->view();

$view->parserOptions = [
    'debug' => $app->config->get('twig.debug')
];
$view->parserExtensions = [
    new TwigExtension,
    new Twig_Extension_Debug // WEGHALEN!!!
];


/* test comments */

// http://laravel.com/docs/5.1/eloquent-relationships#one-to-many



?>