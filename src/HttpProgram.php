<?php
namespace Pho\Http;

use Pho\Core\ProgramInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\EmitterInterface;

class HttpProgram implements ProgramInterface {
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function run() {
        $request = $this->container->get('http.request');
        $response = $this->container->call([Kernel::class, 'handle'], [$request]);
        $this->container->call([EmitterInterface::class, 'emit'], [$response]);

        return $response;
    }
}
