<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;
use JsonSchema\Validator as Validator;

$dataValidator = function(Request $request, RequestHandler $handler) {

    $jsonSchema = <<<'JSON'
    {
        "type": "object",
        "properties": {
            "Name": {"type": "string"},
            "Team": {"type": "string"},
            "Category": {"type": "string"}
        },
        "required": ["Name", "Team", "Category"]
    }
    JSON;

    $jsonSchemaObject = json_decode($jsonSchema);

    // $contentType = $request->getHeaderLine('Content-Type');

    // if (strstr($contentType, 'application/json')) {
    //     $contents = json_decode(file_get_contents('php://input'), true);
    //     if (json_last_error() === JSON_ERROR_NONE) {
    //         $request = $request->withParsedBody($contents);
    //     }
    // }
    
    $validator = new Validator();
    $data = $request->getParsedBody();

    $dataObject = json_decode(json_encode($data));

    $validator->validate($dataObject, $jsonSchemaObject);

    if($validator->isValid()) {
        $response = $handler->handle($request);
        return $response;
    }
    else {
        $response = new Response();
        $response->getBody()->write(json_encode($validator->getErrors()));
        return $response->withHeader('content-type', 'application/json');
    }
    
};