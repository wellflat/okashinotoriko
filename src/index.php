<?php
require_once __DIR__ . '/vendor/autoload.php';

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Okashi\OkashiController;
use Okashi\OkashiClient;

$builder = new ContainerBuilder();
$builder->addDefinitions([
    OkashiClient::class => DI\autowire(OkashiClient::class),
    Twig::class => function() {
        return Twig::create(__DIR__ . '/okashinotoriko/views',
            ['cache' => false]//__DIR__ . '/okashinotoriko/cache']
        );
    }
]);
AppFactory::setContainer($builder->build());
$app = AppFactory::create();

$app->get('/', [OkashiController::class, 'home']);
$app->get('/search', [OkashiController::class, 'search']);
$app->addErrorMiddleware(false, true, true);
$app->run();
