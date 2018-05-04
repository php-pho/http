<?php
namespace Pho\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Kernel {
    private $middlewareStack;

    public function __construct(PhoMiddlewareStack $middlewareStack)
    {
        $this->middlewareStack = $middlewareStack;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface {
        return $this->middlewareStack->run($request);
    }
}
