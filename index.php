<?php

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

require_once('services.php');

require_once('endpoints/rest.php');

$app->run();
