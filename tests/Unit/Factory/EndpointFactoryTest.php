<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Unit\Factory;

use PHPUnit\Framework\TestCase;
use Mediapart\LaPresseLibre\Operation\Verification;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\EndpointFactory;
use Mediapart\Bundle\LaPresseLibreBundle\Test\Functional\App\VerificationService;

/**
 *
 */
class EndpointFactoryTest extends TestCase
{
    /**
     *
     */
    public function testCreateEndpoint()
    {
        $route = 'lorem';
        $attributes = [
            'method' => 'execute',
            'route' => $route,
            'operation' => Verification::class,
        ];
        $service = $this->createMock(VerificationService::class);

        $factory = new EndpointFactory();
        $factory->register($service, $attributes);
        $endpoint = $factory->create($route);

        $this->assertInstanceOf(Verification::class, $endpoint);
    }
}
