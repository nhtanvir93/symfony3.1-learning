<?php

namespace AppBundle\Service;

use AppBundle\Interfaces\TestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Greeting implements TestInterface
{
    private $logger;
    protected $container;
    private $message;

    public function __construct(LoggerInterface $logger, $message)
    {
        $this->logger = $logger;
        $this->message = $message;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getDefault($name)
    {
        return "$this->message!!! $name";
    }

    public function show()
    {
        echo 'Chill';
    }
}