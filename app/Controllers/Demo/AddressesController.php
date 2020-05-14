<?php
declare(strict_types=1);

namespace App\Controllers\Demo;

use App\Kernel\Abstracts\ControllerAbstract;
use App\Models\Demo\AddressesModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddressesController extends ControllerAbstract
{

    /**
     * List Action
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function list(Request $request, Response $response): Response
    {
        unset($request, $response);

        $addresses = (new AddressesModel())->getLast();

        return $this->json($addresses);
    }

    /**
     * List With PDO Action
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function pdo(Request $request, Response $response): Response
    {
        unset($request, $response);

        $addresses = (new AddressesModel())->getLastWithPdo();

        return $this->json($addresses);
    }
}
