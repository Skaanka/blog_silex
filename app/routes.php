<?php

use Symfony\Component\HttpFoundation\Request;

// Home page
$app->get('/', function () use ($app) {
    $billets = $app['dao.billet']->findAll();
    return $app['twig']->render('index.html.twig', array('billets' => $billets));
})->bind('home');

// Billet details avec commentaires
$app->get('/billet/{id}', function ($id) use ($app) {
    $billet = $app['dao.billet']->find($id);
    $commentaires = $app['dao.commentaire']->findAllByBillet($id);
    return $app['twig']->render('billet.html.twig', array('billet' => $billet, 'commentaires' => $commentaires));
})->bind('billet');

// Login form
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');
