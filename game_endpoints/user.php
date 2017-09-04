<?php

use \Symfony\Component\HttpFoundation\Response;
use \Doctrine\DBAL\Exception\UniqueConstraintViolationException;

$app->get('user/myInfo', function() use ($app) {
   return $app['converter-service']->toJson($app['user']);
});

$app->post('register', function() use ($app) {
    $data = json_decode(file_get_contents('php://input'), true);

    if(empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        return new Response("missing-fields", 400);
    }

    $user = new \Plu\Entity\User();

    $user->username = $data['username'];
    $user->password = $app['security.encoder.digest']->encodePassword($data['password'], null);
    $user->email = $data['email'];
    $user->registrationDate = new \DateTime();
    $user->confirmed = false;
    $user->roles = [ 'ROLE_USER' ];

    try {
        $app['user-repo']->add($user);
    }
    catch( UniqueConstraintViolationException $e ) {
        return new Response("username-taken", 400);
    }

    return $app['converter-service']->toJson($user);
});
