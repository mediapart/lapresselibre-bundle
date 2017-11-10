<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Mediapart\Bundle\LaPresseLibreBundle\DependencyInjection\MediapartLaPresseLibreExtension;
use Mediapart\Bundle\LaPresseLibreBundle\Test\Functional\App\Account;

class MediapartLaPresseLibreExtensionTest extends TestCase
{
    private function loadContainer()
    {
        $container = new ContainerBuilder();
        $extension = new MediapartLaPresseLibreExtension();
        $extension->load(
            [
                'mediapart_la_presse_libre' => [
                    'account' => [
                        'repository' => Account\Repository::class, 
                        'provider' => Account\Provider::class,
                    ]
                ]
            ], 
            $container
        );

        return $container;
    }

    public function testLoadIdentity()
    {
        $container = $this->loadContainer();

        $this->assertTrue($container->has('mediapart_lapresselibre.identity'));
    }

    public function testLoadEncryption()
    {
        $container = $this->loadContainer();

        $this->assertTrue($container->has('mediapart_lapresselibre.encryption'));
    }

    public function testLoadWebServicesController()
    {
        $container = $this->loadContainer();

        $this->assertTrue($container->has('mediapart_lapresselibre.webservices_controller'));
    }

    public function testLoadRegistration()
    {
        $container = $this->loadContainer();

        $this->assertTrue($container->has('mediapart_lapresselibre.registration'));
    }
}
