<?php
declare(strict_types=1);

namespace App\Controllers\Demo;

use App\Kernel\Abstracts\ControllerAbstract;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HelloController extends ControllerAbstract
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
        unset($request, $response);

        $data = container('example')->toJson($arguments['name']);

        return $this->json($data);
    }
}
