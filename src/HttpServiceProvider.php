<?php
namespace Pho\Http;

use DI\ContainerBuilder;
use DI\Scope;
use Pho\Core\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\SapiEmitter;
use function DI\create;
use function DI\get;
use function DI\factory;

class HttpServiceProvider implements ServiceProviderInterface {
    public function register(ContainerBuilder $containerBuilder, array $opts = []) {
        $def = array_merge([
            'http.response_class' => Response::class
        ], $opts);

        $def['http.request'] = factory(function (ContainerInterface $c) {
            return ServerRequestFactory::fromGlobals();
        });

        $def[EmitterInterface::class] = create(SapiEmitter::class);

        $containerBuilder->addDefinitions($def);
    }
}
