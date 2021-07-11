<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Tuupola\Middleware\HttpBasicAuthentication;

return function (App $app) {
    $app->get('/', \App\Action\Home\HomeAction::class)->setName('homepage');
    // $app->get('/price/new', \App\Action\Price\PriceAction::class, ':priceNew');
    $app->get('/prices', [\App\Action\Price\PriceAction::class, 'priceListAll'])->setName('priceListAll');
    $app->get('/price/new', [\App\Action\Price\PriceAction::class, 'priceNew']);
    $app->post('/price/new', [\App\Action\Price\PriceAction::class, 'priceNewSubmit']);
    $app->get('/price/{id}', [\App\Action\Price\PriceAction::class, 'priceDetail'])->setName('price-detail');

    // Redirect to Swagger documentation
    // Swagger API documentation
    $app->get('/docs/v1', \App\Action\OpenApi\Version1DocAction::class)->setName('docs');

    // Password protected area
    $app->group(
        '/api',
        function (RouteCollectorProxy $app) {
            $app->get('/users', \App\Action\User\UserFindAction::class);
            $app->post('/users', \App\Action\User\UserCreateAction::class);
            $app->get('/users/{user_id}', \App\Action\User\UserReadAction::class);
            $app->put('/users/{user_id}', \App\Action\User\UserUpdateAction::class);
            $app->delete('/users/{user_id}', \App\Action\User\UserDeleteAction::class);
        }
    )->add(HttpBasicAuthentication::class);
};
