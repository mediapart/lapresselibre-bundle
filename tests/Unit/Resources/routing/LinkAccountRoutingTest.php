<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Unit\Resources;

use PHPUnit\Framework\TestCase;

class LinkAccountRoutingTest extends TestCase
{
    public function testAllRoutesHasHTTPSSchemeOnly()
    {
        $collection = require 'src/Resources/config/routing/link-account.php';

        foreach ($collection->getIterator() as $route) {
            $this->assertEquals(['https'], $route->getSchemes());
        }
    }

    public function testLinkAccountRouteMethodIsGET()
    {
        $collection = require 'src/Resources/config/routing/link-account.php';
        $verification = $collection->get('lapresselibre_linkaccount');

        $this->assertEquals(['GET'], $verification->getMethods());
    }
}
