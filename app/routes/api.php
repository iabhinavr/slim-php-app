<?php

use Doctrine\DBAL\DriverManager as DriverManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

include __DIR__ . '/../middlewares/jsonBodyParser.php';

$app->get('/', function(Request $request, Response $response) {
    $response_str = json_encode(['message' => 'Welcome to our Cricket API']);
    $response->getBody()->write($response_str);
    return $response->withHeader('Content-Type', 'application/json');
});

/*
 Route to get all players
*/

$app->get('/players/', function(Request $request, Response $response) {
    
    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->select('Id', 'Name', 'Team', 'Category')
        ->from('Players')
    ;
    
    $results = $queryBuilder->executeQuery()->fetchAll();

    $response->getBody()->write(json_encode($results));
    return $response
            ->withHeader('content-type', 'application/json');

});

/*
 Route to get a single player
*/

$app->get('/player/{id}', function(Request $request, Response $response, array $args) {
    
    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->select('Id', 'Name', 'Team', 'Category')
        ->from('Players')
        ->where('Id = ?')
        ->setParameter(1, $args['id'])
    ;
    
    $results = $queryBuilder->executeQuery()->fetchAssociative();

    $response->getBody()->write(json_encode($results));
    return $response
            ->withHeader('content-type', 'application/json');

});

/*
 Route to add a new player
*/

$app->post('/player/add', function(Request $request, Response $response) {
    $parsedBody = $request->getParsedBody();
    $response->getBody()->write(json_encode($parsedBody));
    return $response->withHeader('content-type', 'application/json');
})->add($jsonBodyParserMiddleware);