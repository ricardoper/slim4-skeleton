<?php
declare(strict_types=1);

namespace App\Kernel\Abstracts;

use App\Emitters\JsonResponseEmitter;
use Psr\Http\Message\ResponseInterface as Response;

abstract class ControllerAbstract extends KernelAbstract
{

    /**
     * Set Emitter
     *
     * @param string $name
     * @param string $emitter
     */
    protected function setEmitter(string $name, string $emitter): void
    {
        $configs = $this->container['configs'];

        $configs->set('emitters', array_replace_recursive($configs->get('emitters'), [$name => $emitter]));
    }

    /**
     * Write Text Plain
     *
     * @param string $data
     * @return Response
     */
    public function write(string $data): Response
    {
        $response = $this->getResponse();

        $response->getBody()->write($data);

        return $response;
    }

    /**
     * Returns Json Encoded
     *
     * @param array $data
     * @param bool $sendHeaders
     * @return Response
     */
    public function json(array $data, bool $sendHeaders = true): Response
    {
        $response = $this->getResponse();

        $response->getBody()->write(json_encode($data));

        if ($sendHeaders === true) {
            $this->setEmitter('json', JsonResponseEmitter::class);
        }

        return $response;
    }
}
