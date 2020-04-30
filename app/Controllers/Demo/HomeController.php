<?php
declare(strict_types=1);

namespace App\Controllers\Demo;

use App\Emitters\PlainResponseEmitter;
use App\Kernel\Controllers\ControllerAbstract;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends ControllerAbstract
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
        unset($request, $response);

        $data = ['Hello' => 'World!'];

        return $this->json($data);
    }

    /**
     * Dump Action
     *
     * Example action to know the ways to obtain data
     *
     * @param Request $request
     * @param Response $response
     * @param array $arguments )
     * @return Response
     */
    public function dump(Request $request, Response $response, array $arguments): Response
    {
        $app = app();
        $app = $this->getApp();

        $container = container();
        $exampleService = container('example');

        $container = $this->getContainer();
        $exampleService = $this->getContainer('example');

        $exampleService = $this->getService('example');

        $configs = configs();
        $viewsConfigs = configs('views');

        $configs = $this->getConfigs();
        $loggerConfigs = $this->getConfigs('logger');

        $logErrorsEnv = env('LOG_ERRORS', false);

        // Add Emmiter //
        $this->setEmitter(PlainResponseEmitter::class);

        unset($request, $response, $arguments, $app, $container, $exampleService, $configs, $viewsConfigs, $logErrorsEnv);

        $this->write('Please, ');
        return $this->write('see the source code of this action.');
    }
}
