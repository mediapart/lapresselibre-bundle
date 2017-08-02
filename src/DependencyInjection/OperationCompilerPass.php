<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 *
 */
class OperationCompilerPass implements CompilerPassInterface
{   
	/**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('mediapart_lapresselibre.endpoint_factory');
        $taggedServices = $container->findTaggedServiceIds('lapresselibre');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'declare',
                    array(new Reference($id), $attributes)
                );
            }
        }
    }
}
