<?php

use Symfony\Component\HttpFoundation\Request;
use Manager\Commentaire;
use Manager\Billet;
use Manager\User;
use Form\Type\CommentaireType;
use Form\Type\BilletType;
use Form\Type\UserType;


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

// Admin home page
$app->get('/admin', function() use ($app) {
    $billets = $app['dao.billet']->findAll();
    $commentaires = $app['dao.commentaire']->findAll();
    $users = $app['dao.user']->findAll();
    return $app['twig']->render('admin/admin.html.twig', array(
        'billets' => $billets,
        'commentaires' => $commentaires,
        'users' => $users));
})->bind('admin');


// Add a new billet
$app->match('/admin/billet/add', function(Request $request) use ($app) {
    $billet = new Billet();
    $billetForm = $app['form.factory']->create(new BilletType(), $billet);
    $billetForm->handleRequest($request);
    if ($billetForm->isSubmitted() && $billetForm->isValid()) {
        $app['dao.billet']->save($billet);
        $app['session']->getFlashBag()->add('success', 'The billet was successfully created.');
    }
    return $app['twig']->render('admin/billet_form.html.twig', array(
        'titre' => 'New billet',
        'billetForm' => $billetForm->createView()));
})->bind('admin_billet_add');

// Edit an existing billet
$app->match('/admin/billet/{id}/edit', function($id, Request $request) use ($app) {
    $billet = $app['dao.billet']->find($id);
    $billetForm = $app['form.factory']->create(new BilletType(), $billet);
    $billetForm->handleRequest($request);
    if ($billetForm->isSubmitted() && $billetForm->isValid()) {
        $app['dao.billet']->save($billet);
        $app['session']->getFlashBag()->add('success', 'The billet was succesfully updated.');
    }
    return $app['twig']->render('admin/billet_form.html.twig', array(
        'titre' => 'Edit billet',
        'billetForm' => $billetForm->createView()));
})->bind('admin_billet_edit');

// Remove an billet
$app->get('/admin/billet/{id}/delete', function($id, Request $request) use ($app) {
    // Delete all associated comments
    $app['dao.commentaire']->deleteAllByArticle($id);
    // Delete the billet
    $app['dao.billet']->delete($id);
    $app['session']->getFlashBag()->add('success', 'The billet was succesfully removed.');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_billet_delete');



// Edit an existing commentaire
$app->match('/admin/commentaire/{id}/edit', function($id, Request $request) use ($app) {
    $commentaire = $app['dao.commentaire']->find($id);
    $commentaireForm = $app['form.factory']->create(new CommentaireType(), $commentaire);
    $commentaireForm->handleRequest($request);
    if ($commentaireForm->isSubmitted() && $commentaireForm->isValid()) {
        $app['dao.commentaire']->save($commentaire);
        $app['session']->getFlashBag()->add('success', 'The commentaire was succesfully updated.');
    }
    return $app['twig']->render('admin/commentaire_form.html.twig', array(
        'titre' => 'Edit commentaire',
        'commentaireForm' => $commentaireForm->createView()));
})->bind('admin_commentaire_edit');

// Remove a commentaire
$app->get('/admin/commentaire/{id}/delete', function($id, Request $request) use ($app) {
    $app['dao.commentaire']->delete($id);
    $app['session']->getFlashBag()->add('success', 'The commentaire was succesfully removed.');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_commentaire_delete');



// Add a user
$app->match('/admin/user/add', function(Request $request) use ($app) {
    $user = new User();
    $userForm = $app['form.factory']->create(new UserType(), $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        // generate a random salt value
        $salt = substr(md5(time()), 0, 23);
        $user->setSalt($salt);
        $plainPassword = $user->getPassword();
        // find the default encoder
        $encoder = $app['security.encoder.digest'];
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password);
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'The user was successfully created.');
    }
    return $app['twig']->render('admin/user_form.html.twig', array(
        'titre' => 'New user',
        'userForm' => $userForm->createView()));
})->bind('admin_user_add');

// Edit an existing user
$app->match('/admin/user/{id}/edit', function($id, Request $request) use ($app) {
    $user = $app['dao.user']->find($id);
    $userForm = $app['form.factory']->create(new UserType(), $user);
    $userForm->handleRequest($request);
    if ($userForm->isSubmitted() && $userForm->isValid()) {
        $plainPassword = $user->getPassword();
        // find the encoder for the user
        $encoder = $app['security.encoder_factory']->getEncoder($user);
        // compute the encoded password
        $password = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($password);
        $app['dao.user']->save($user);
        $app['session']->getFlashBag()->add('success', 'The user was succesfully updated.');
    }
    return $app['twig']->render('admin/user_form.html.twig', array(
        'titre' => 'Edit user',
        'userForm' => $userForm->createView()));
})->bind('admin_user_edit');

// Remove a user
$app->get('/admin/user/{id}/delete', function($id, Request $request) use ($app) {
    // Delete all associated comments
    $app['dao.commentaire']->deleteAllByUser($id);
    // Delete the user
    $app['dao.user']->delete($id);
    $app['session']->getFlashBag()->add('success', 'The user was succesfully removed.');
    // Redirect to admin home page
    return $app->redirect($app['url_generator']->generate('admin'));
})->bind('admin_user_delete');
