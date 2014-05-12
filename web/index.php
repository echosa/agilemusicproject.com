<?php
require_once __DIR__.'/../vendor/autoload.php'; 

use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application(); 

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->get('/hello/{name}', function($name) use($app) { 
    return 'Hello '.$app->escape($name); 
});

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html'); 
});

$app->run(); 