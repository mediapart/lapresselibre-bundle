<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Mediapart\Bundle\LaPresseLibreBundle\DependencyInjection\MediapartLaPresseLibreExtension;

/**
 *
 */
class MediapartLaPresseLibreExtensionTest extends TestCase
{
    /**
     *
     */
    private function loadContainer()
    {
        $container = new ContainerBuilder();
        $extension = new MediapartLaPresseLibreExtension();
        $extension->load([], $container);

        return $container;
    }

    /**
     *
     */
    public function testLoadIdentity()
    {
        $container = $this->loadContainer();

        $this->assertTrue($container->has('mediapart_lapresselibre.identity'));
    }

    /**
     *
     */
    public function testLoadEncryption()
    {
        $container = $this->loadContainer();

        $this->assertTrue($container->has('mediapart_lapresselibre.encryption'));
    }

    /**
     *
     */
    public function testLoadController()
    {
        $container = $this->loadContainer();

        $this->assertTrue($container->has('mediapart_lapresselibre.controller'));
    }
}
