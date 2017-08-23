<?php

$app->get('user/myInfo', function() use ($app) {
   return $app['converter-service']->toJson($app['user']);
});

$app->post('register', function() use ($app) {
    $data = json_decode(file_get_contents('php://input'), true);
    $user = new \Plu\Entity\User();

    $user->username = $data['username'];
    $user->password = $app['security.encoder.digest']->encodePassword($data['password'], null);
    $user->email = $data['email'];
    $user->registrationDate = new \DateTime();
    $user->confirmed = false;
    $user->roles = [ 'ROLE_USER' ];

    $app['user-repo']->add($user);

    return $app['converter-service']->toJson($user);
});
