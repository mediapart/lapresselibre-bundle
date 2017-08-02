<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mediapart_la_presse_libre');

        $rootNode
            ->children()
                ->scalarNode('public_key')
                    ->defaultValue('%lapresselibre_publickey%')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('secret_key')
                    ->defaultValue('%lapresselibre_secretkey%')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('aes_password')
                    ->defaultValue('%lapresselibre_aespassword%')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('aes_iv')
                    ->defaultValue('%lapresselibre_aesiv%')
                ->end()
                ->scalarNode('aes_options')
                    ->defaultValue(OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING)
                ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}