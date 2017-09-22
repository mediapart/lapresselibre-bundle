<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Unit\Resources;

use PHPUnit\Framework\TestCase;

class RoutingTest extends TestCase
{
    public function testAllRoutesHasHTTPSSchemeOnly()
    {
        $collection = require 'src/Resources/config/routing.php';

        foreach ($collection->getIterator() as $route) {
            $this->assertEquals(['https'], $route->getSchemes());
        }
    }

    public function testVerificationRouteMethodIsGET()
    {
        $collection = require 'src/Resources/config/routing.php';
        $verification = $collection->get('lapresselibre_verification');

        $this->assertEquals(['GET'], $verification->getMethods());
    }

    public function testAccountCreationRouteMethodIsPOST()
    {
        $collection = require 'src/Resources/config/routing.php';
        $account_creation = $collection->get('lapresselibre_account_creation');

        $this->assertEquals(['POST'], $account_creation->getMethods());
    }

    public function testAccountUpdateRouteMethodIsPUT()
    {
        $collection = require 'src/Resources/config/routing.php';
        $account_update = $collection->get('lapresselibre_account_update');

        $this->assertEquals(['PUT'], $account_update->getMethods());
    }
}
