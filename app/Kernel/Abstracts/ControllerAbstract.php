<?php
declare(strict_types=1);

namespace App\Kernel\Abstracts;

use App\Emitters\JsonResponseEmitter;
use Psr\Http\Message\ResponseInterface as Response;

abstract class ControllerAbstract extends KernelAbstract
{

    /**
     * Get Response
     *
     * @return Response
     */
    protected function getResponse(): Response
    {
        return $this->container['response'];
    }

    /**
     * Set Emitter
     *
     * @param string $emitter
     */
    protected function setEmitter(string $emitter): void
    {
        $configs = $this->container['configs'];

        $configs->set('emitters', array_merge($configs->get('emitters'), [$emitter]));
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
            $this->setEmitter(JsonResponseEmitter::class);
        }

        return $response;
    }
}
