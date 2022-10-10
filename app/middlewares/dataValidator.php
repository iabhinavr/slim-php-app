<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;
use JsonSchema\Validator as Validator;

$jsonSchema = <<<'JSON'
{
    "type": "object",
    "properties": {
        "Name": {"type": "string"},
        "Team": {"type": "string"},
        "Category": {"type": "string"}
    }
}
JSON;

$jsonSchemaObject = json_decode($jsonSchema);

$dataValidator = function(Request $request, RequestHandler $handler) {
    
    $validator = new Validator();
    $data = $request->getParsedBody();

    $dataObject = json_decode($data);

    $validator->validate($dataObject, $jsonSchemaObject);

    if($validator->isValid()) {
        $response = $handler->handle($request);
        return $response;
    }
    else {
        $response = new Response();
        $response->getBody()->write(json_encode($validator->getErrors()));
        return $response;
    }
    
};