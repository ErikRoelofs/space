<?php

$app->post('/api/login', function(\Symfony\Component\HttpFoundation\Request $request) use ($app){
    $vars = json_decode($request->getContent(), true);

    try {
        if (empty($vars['_username']) || empty($vars['_password'])) {
            throw new \Exception('Please supply a username and password');
        }

        /**
         * @var $user User
         */
        $user = $app['users']->loadUserByUsername($vars['_username']);
        if (! $app['security.encoder.digest']->isPasswordValid($user->getPassword(), $vars['_password'], '')) {
            throw new \Exception(sprintf('Username "%s" does not exist.', $vars['_username']));
        } else {
            $response = [
                'success' => true,
                'token' => $app['security.jwt.encoder']->encode([$app['security.jwt']['options']['username_claim'] => $user->getUsername()]),
            ];
        }
    } catch (\Exception $e) {
        $response = [
            'success' => false,
            'error' => 'Invalid credentials',
        ];
    }
    return $app->json($response, ($response['success'] == true ? \Symfony\Component\HttpFoundation\Response::HTTP_OK : \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST));
});

$app->get('/home/stats', function() use ($app) {
    $stats = [
        'gamesActive' => $app['game-repo']->findNumberOfActiveGames(),
        'players' => $app['user-repo']->findNumberOfUsers(),
        'gamesArchived' => $app['game-repo']->findNumberOfArchivedGames(),
    ];

   return json_encode($stats);
});
