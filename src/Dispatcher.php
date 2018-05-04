<?php
namespace Pho\Http;

use Closure;
use Middlewares\Utils\CallableHandler;
use Middlewares\Utils\RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UnexpectedValueException;

class Dispatcher
{
    private $stack;

    public function __construct(array $stack = [])
    {
        $this->stack = $stack;
    }

    public function push($middleware) : self {
        $this->stack[] = $middleware;

        return $this;
    }

    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        $resolved = $this->resolve(0);

        return $resolved->handle($request);
    }

    private function resolve(int $index): RequestHandlerInterface
    {
        return new RequestHandler(function (ServerRequestInterface $request) use ($index) {
            $middleware = isset($this->stack[$index]) ? $this->stack[$index] : new CallableHandler(function () {
            });

            if ($middleware instanceof Closure) {
                $middleware = new CallableHandler($middleware);
            }

            if (!($middleware instanceof MiddlewareInterface)) {
                throw new UnexpectedValueException(
                    sprintf('The middleware must be an instance of %s', MiddlewareInterface::class)
                );
            }

            return $middleware->process($request, $this->resolve($index + 1));
        });
    }
}
