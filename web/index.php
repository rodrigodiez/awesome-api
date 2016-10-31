<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'     => getenv('DB_HOST'),
        'dbname'   => getenv('DB_NAME'),
        'user'     => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'charset'  => 'utf8'
    ),
));

$app['payment.controller'] = function($app) {
    return new RodrigoDiez\AwesomeAPI\Controller\PaymentController($app['db'], $app['response.factory.json']);
};

$app['response.factory.json'] = function($app) {
    return new RodrigoDiez\AwesomeAPI\Response\Factory\JsonResponseFactory();
};

$app->before(function (Symfony\Component\HttpFoundation\Request $request, Silex\Application $app) {

    if ($request->get('api_token') !== getenv('API_TOKEN')) {
        return $app['response.factory.json']->createUnauthorised();
    }
});

$app->post('payment', 'payment.controller:post');
$app->get('payment', 'payment.controller:get');
$app->run();
