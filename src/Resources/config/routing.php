<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add(
    'lapresselibre_verification',
    new Route(
        '/verification',
        ['_controller' => 'mediapart_lapresselibre.controller:executeAction'],
        [],
        [],
        '',
        ['https'],
        ['GET']
    )
);

$collection->add(
    'lapresselibre_account_creation',
    new Route(
        '/account-creation',
        ['_controller' => 'mediapart_lapresselibre.controller:executeAction'],
        [],
        [],
        '',
        ['https'],
        ['POST']
    )
);

$collection->add(
    'lapresselibre_account_updates',
    new Route(
        '/account-updates',
        ['_controller' => 'mediapart_lapresselibre.controller:executeAction'],
        [],
        [],
        '',
        ['https'],
        ['PUT']
    )
);

return $collection;
