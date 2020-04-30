<?php
declare(strict_types=1);

namespace App\Emitters;

use Psr\Http\Message\ResponseInterface;
use Slim\ResponseEmitter as SlimResponseEmitter;

class PlainResponseEmitter extends SlimResponseEmitter
{

    /**
     * Send the response the client
     *
     * @param ResponseInterface $response
     */
    public function emit(ResponseInterface $response): void
    {
        $response = $response
            ->withHeader('Content-Type', 'text/plain; charset=UTF-8');

        if (ob_get_contents()) {
            ob_clean();
        }

        parent::emit($response);
    }
}