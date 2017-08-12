<?php

$app->get('user/myInfo', function() use ($app) {
   return $app['converter-service']->toJson($app['user']);
});
