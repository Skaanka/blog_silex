<?php

use Symfony\Component\HttpFoundation\Request;
use Manager\Commentaire;
use Form\Type\CommentaireType;
// Home page
$app->get('/', function () use ($app) {
    $billets = $app['dao.billet']->findAll();
    return $app['twig']->render('index.html.twig', array('billets' => $billets));
})->bind('home');

// Billet details avec commentaires
$app->match('/billet/{id}', function ($id, Request $request) use ($app) {
    $billet = $app['dao.billet']->find($id);
    $commentaireFormView = null;
    if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
        // A user is fully authenticated : he can add comments
        $commentaire = new Commentaire();
        $commentaire->setBillet($billet);
        $user = $app['user'];
        $commentaire->setAuteur($user);
        $commentaireForm = $app['form.factory']->create(new CommentaireType(), $commentaire);
        $commentaireForm->handleRequest($request);
        if ($commentaireForm->isSubmitted() && $commentaireForm->isValid()) {
            $app['dao.commentaire']->save($commentaire);
            $app['session']->getFlashBag()->add('success', 'Your commentaire was succesfully added.');
        }
        $commentaireFormView = $commentaireForm->createView();
    }
    $commentaires = $app['dao.commentaire']->findAllByBillet($id);
    return $app['twig']->render('billet.html.twig', array(
        'billet' => $billet,
        'commentaires' => $commentaires,
        'commentaireForm' => $commentaireFormView));
})->bind('billet');

// Login form
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');
