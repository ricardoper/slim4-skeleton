<?php
declare(strict_types=1);

namespace App\Controllers\Demo;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HelloController
{

    /**
     * Index Action
     *
     * @param Request $request
     * @param Response $response
     * @param $arguments
     * @return Response
     */
    public function index(Request $request, Response $response, $arguments): Response
    {
        unset($request);

        $data = container('example')->data($arguments['name']);

        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
