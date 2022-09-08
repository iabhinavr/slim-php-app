<?php

use Doctrine\DBAL\DriverManager as DriverManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

$app->get('/', function(Request $request, Response $response) {
    $response_str = json_encode(['message' => 'Welcome to our Cricket API']);
    $response->getBody()->write($response_str);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/players/', function(Request $request, Response $response) {
    
    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->select('Id', 'Name', 'Team', 'MainRole')
        ->from('Players')
    ;
    
    $results = $queryBuilder->executeQuery()->fetchAll();

    $response->getBody()->write(json_encode($results));
    return $response
            ->withHeader('content-type', 'application/json');

});

$app->get('/player/{id}', function(Request $request, Response $response, array $args) {
    
    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->select('Id', 'Name', 'Team', 'MainRole')
        ->from('Players')
        ->where('Id = ?')
        ->setParameter(1, $args['id'])
    ;
    
    $results = $queryBuilder->executeQuery()->fetchAssociative();

    $response->getBody()->write(json_encode($results));
    return $response
            ->withHeader('content-type', 'application/json');

});