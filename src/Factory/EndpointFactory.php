<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Factory;

use Symfony\Component\HttpFoundation\Request;
use Mediapart\LaPresseLibre\Endpoint;

/**
 *
 */
class EndpointFactory
{
    /**
     *
     */
    private $operations = [];

    /**
     *
     */
    public function create($route)
    {
        list($class, $callback) = $this->operations[$route];

        return Endpoint::answer($class, $callback);
    }

    /**
     *
     */
    public function declare($service, $attributes)
    {
        $callback = [$service, $attributes['method']];

        $this->operations[$attributes['route']] = [$attributes['operation'], $callback];
    }
}
