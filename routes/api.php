<?php

use Laravel\Lumen\Routing\Router;

$router = app(Router::class);

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/session-history/{user_id}', 'SessionHistoryController@getHistory');
});

$router->get('/protected-route', 'SessionHistoryController@getHistory')->middleware('auth.user');
