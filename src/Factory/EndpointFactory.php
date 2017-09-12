<?php

/**
 * This file is part of the Mediapart LaPresseLibre Bundle.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre-bundle>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\Bundle\LaPresseLibreBundle\Factory;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Mediapart\LaPresseLibre\Endpoint;

class EndpointFactory
{
    /**
     * @var Array
     */
    private $endpoints = [];

    /**
     * @param object|string $service
     * @param array $attributes
     *
     * - method    : method that will be called on $service
     * - route     : route of the endpoint
     * - operation : what will be executed
     *
     * @return void
     */
    public function register($service, array $attributes)
    {
        $attributes = $this->resolve($attributes);

        $route    = $attributes['route'];
        $callback = [$service, $attributes['method']];
        $endpoint = [$attributes['operation'], $callback];

        $this->endpoints[$route] = $endpoint;
    }

    /**
     * @param string $route
     *
     * @return Endpoint
     */
    public function create($route)
    {
        list($class, $callback) = $this->endpoints[$route];

        return Endpoint::answer($class, $callback);
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    private function resolve(array $attributes)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['method', 'route', 'operation']);
        $resolver->setAllowedValues('operation', Endpoint::all());

        return $resolver->resolve($attributes);
    }
}
