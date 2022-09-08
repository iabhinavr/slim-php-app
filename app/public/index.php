<?php

use Symfony\Component\Dotenv\Dotenv;
use DI\Container;
use DI\ContainerBuilder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../src/definitions.php');
$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

include __DIR__ . '/../routes/api.php';
include __DIR__ . '/../routes/web.php';

$app->addRoutingMiddleware();

$errorSettings = $container->get('Config')->getErrorSettings();
$errorMiddleware = $app->addErrorMiddleware(
    $errorSettings['displayErrorDetails'], 
    $errorSettings['logErrors'], 
    $errorSettings['logErrorDetails']);

$app->run();

