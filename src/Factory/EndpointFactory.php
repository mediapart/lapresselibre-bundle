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
    public function register($service, $attributes)
    {
        $callback = [$service, $attributes['method']];

        $this->operations[$attributes['route']] = [$attributes['operation'], $callback];
    }
}
