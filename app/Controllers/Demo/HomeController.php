<?php
declare(strict_types=1);

namespace App\Controllers\Demo;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{

    /**
     * Index Action
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response): Response
    {
        unset($request);

        $data = ['Hello' => 'World!'];

        $response->getBody()->write(json_encode($data));

        return $response;
    }

    /**
     * Dump Action
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function dump(Request $request, Response $response): void
    {
        unset($request, $response);

        d(app());

        d(container());

        d(container('example'));

        d(configs());
        d(configs('services'));

        dde(env('LOG_ERRORS', false));
    }
}
