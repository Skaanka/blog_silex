<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../templates',
));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => $app->share(function () use ($app) {
                return new DAO\UserDAO($app['db']);
            }),
        ),
    ),
));

// Register services.
$app['dao.billet'] = $app->share(function ($app) {
    return new DAO\BilletDAO($app['db']);
});

$app['dao.user'] = $app->share(function ($app) {
	return new DAO\UserDAO($app['db']);
});

$app['dao.commentaire'] = $app->share(function ($app) {
    $commentaireDAO = new DAO\CommentaireDAO($app['db']);
    $commentaireDAO->setBilletDAO($app['dao.billet']);
    $commentaireDAO->setUserDAO($app['dao.user']);
    return $commentaireDAO;
});
