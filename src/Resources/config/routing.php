<?php

/**
 * This file is part of the Mediapart LaPresseLibre Bundle.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre-bundle>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$route = new Route(
    '/verification',
    ['_controller' => 'mediapart_lapresselibre.controller:executeAction']
);
$route->setMethods(['GET']);
$collection->add('lapresselibre_verification', $route);

$route = new Route(
    '/account-creation',
    ['_controller' => 'mediapart_lapresselibre.controller:executeAction']
);
$route->setMethods(['POST']);
$collection->add('lapresselibre_account_creation', $route);

$route = new Route(
    '/account-update',
    ['_controller' => 'mediapart_lapresselibre.controller:executeAction']
);
$route->setMethods(['PUT']);
$collection->add('lapresselibre_account_update', $route);

$collection->setSchemes(['https']);

return $collection;
