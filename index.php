<?php

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;
error_reporting(E_ALL);
ini_set('display_errors', true);


require_once('services.php');

require_once('endpoints/rest.php');
require_once('game_endpoints/view.php');
require_once('game_endpoints/order.php');
require_once('game_endpoints/admin.php');
require_once('game_endpoints/lobby.php');
require_once('game_endpoints/user.php');
require_once('test_endpoints/test.php');

$app->get('/', function() use ($app) {
    return $app->redirect('/game');
});

$app->get('/load/pieces', function() use ($app) {
    $s = new \Plu\Service\PieceTypesService();
    $pieces = $s->loadPieceTypes();
    foreach($pieces as $piece) {
        $app['piece-type-repo']->add($piece);
    }
    return 'done';
});

$app->post('/api/login', function(\Symfony\Component\HttpFoundation\Request $request) use ($app){
    $vars = json_decode($request->getContent(), true);

    try {
        if (empty($vars['_username']) || empty($vars['_password'])) {
            throw new \Exception(sprintf('Username "%s" does not exist.', $vars['_username']));
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

$app->run();
