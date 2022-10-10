<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;

$apiKeyVerifier = function(Request $request, RequestHandler $handler) {

    $UserName = $request->getHeaderLine('X-API-User');
    $ApiKey = $request->getHeaderLine('X-API-Key');

    if(!$UserName || !$ApiKey) {
        return sendErrorResponse(['msg' => 'Specify UserName and ApiKey for authentication']);
    }

    $queryBuilder = $this->get('DB')->getQueryBuilder();

    $queryBuilder
        ->select('ApiKey')
        ->from('Users')
        ->where('UserName = ?')
        ->setParameter(1, $UserName)
    ;

    $result = $queryBuilder->executeQuery()->fetchAssociative();

    if(!$result) {
        return sendErrorResponse(['msg' => 'UserName does not exist']);
    }

    if(array_key_exists('ApiKey', $result)) {
        $hashedApiKey = $result['ApiKey'];
    }
    else {
        return sendErrorResponse(['msg' => 'UserName does not exist']);
    }

    if(!password_verify($ApiKey, $hashedApiKey)) {
        return sendErrorResponse([
            'msg' => 'Invalid Api Key',
        ]);
    }

    $response = $handler->handle($request);
    return $response;
};

function sendErrorResponse($error) {
    $response = new Response();
    $response->getBody()->write(json_encode($error));
    $newResponse = $response->withStatus(401);
    return $newResponse;
}