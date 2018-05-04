<?php
namespace Pho\Http;

use Middlewares\ErrorHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PhoMiddlewareStack {
    private $container;
    private $dispatcher;

    public function __construct(ContainerInterface $container, Dispatcher $dispatcher)
    {
        $this->container = $container;
        $this->dispatcher = $dispatcher;
    }

    public function middlewares() {
        $this
            ->push($this->container->get(ErrorHandler::class)->catchExceptions(true));
    }

    public function push($middleware) : self {
        $this->dispatcher->push($middleware);

        return $this;
    }

    public function run(ServerRequestInterface $request) : ResponseInterface {
        return $this->dispatcher->dispatch($request);
    }
}
