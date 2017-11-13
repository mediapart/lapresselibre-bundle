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
	'/link-account',
	['_controller' => 'mediapart_lapresselibre.linkaccount_controller']
);
$route->setMethods(['GET']);

$collection->add('lapresselibre_linkaccount', $route);

$collection->setSchemes(['https']);

return $collection;
