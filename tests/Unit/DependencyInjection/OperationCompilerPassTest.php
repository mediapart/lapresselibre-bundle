<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\EndpointFactory;
use Mediapart\Bundle\LaPresseLibreBundle\DependencyInjection\OperationCompilerPass;
use Mediapart\Bundle\LaPresseLibreBundle\DependencyInjection\MediapartLaPresseLibreExtension;

/**
 *
 */
class OperationCompilerPassTest extends TestCase
{
    /**
     *
     */
    public function testProcess()
    {
        $nbDeclarationsExpected = 3;
        $container = new ContainerBuilder();

        $definition = $this->createMock(Definition::class);
        $definition
            ->method('getClass')
            ->willReturn(EndpointFactory::class)
        ;
        $definition
            ->expects($this->exactly($nbDeclarationsExpected))
            ->method('addMethodCall')
            ->with('declare')
        ;
        $container->setDefinition(
            'mediapart_lapresselibre.endpoint_factory', 
            $definition
        );

        $definition = new Definition(\stdClass::class);
        $definition->addTag(
            'lapresselibre',
            [
                'method' => '',
                'route' => '',
                'operation' => '',
            ]
        );
        for ($i=1; $i<=$nbDeclarationsExpected; $i++) {
            $container->setDefinition('acme_service_'.$i, $definition);
        }

        $compiler = new OperationCompilerPass();
        $compiler->process($container);
    }
}
