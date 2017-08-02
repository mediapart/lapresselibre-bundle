<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

use Mediapart\LaPresseLibre\Transaction;
use Mediapart\LaPresseLibre\Security\Encryption;
use Mediapart\LaPresseLibre\Security\Identity;
use Mediapart\Bundle\LaPresseLibreBundle\Controller\ApiController as Controller;
use Mediapart\Bundle\LaPresseLibreBundle\Operation;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\EndpointFactory;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\TransactionFactory;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MediapartLaPresseLibreExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(
            new Configuration(),
            $configs
        );

        $this
            ->loadIdentity($config, $container)
            ->loadEncryption($config, $container)
            ->loadTransactionFactory($config, $container)
            ->loadEndpointFactory($config, $container)
            ->loadPsr7Factory($config, $container)
            ->loadOperation($config, $container)
            ->loadController($config, $container)
        ;
    }

    /**
     *
     */
    private function loadIdentity(array $config, ContainerBuilder $container)
    {
        $identity = new Definition(
            Identity::class,
            [
                $config['secret_key'],
            ]
        );
        $container->setDefinition(
            'mediapart_lapresselibre.identity', $identity
        );

        return $this;
    }

    /**
     *
     */
    private function loadEncryption(array $config, ContainerBuilder $container)
    {
        $encryption = new Definition(
            Encryption::class,
            [
                $config['aes_password'],
                $config['aes_iv'],
                $config['aes_options'],
            ]
        );
        $container->setDefinition(
            'mediapart_lapresselibre.encryption', $encryption
        );

        return $this;
    }

    /**
     *
     */
    private function loadTransactionFactory(array $config, ContainerBuilder $container)
    {
        $identity = new Reference('mediapart_lapresselibre.identity');
        $encryption = new Reference('mediapart_lapresselibre.encryption');

        $factory = new Definition(
            TransactionFactory::class,
            [
                $config['public_key'],
                $identity,
                $encryption,
            ]
        );
        $factory->setPublic(false);
        $container->setDefinition(
            'mediapart_lapresselibre.transaction_factory', $factory
        );

        return $this;
    }

    /**
     *
     */
    private function loadEndpointFactory(array $config, ContainerBuilder $container)
    {
        $factory = new Definition(
            EndpointFactory::class,
            []
        );
        $factory->setPublic(false);
        $container->setDefinition(
            'mediapart_lapresselibre.endpoint_factory', $factory
        );

        return $this;
    }

    /**
     *
     */
    private function loadPsr7Factory(array $config, ContainerBuilder $container)
    {
        $factory = new Definition(
            DiactorosFactory::class
        );
        $factory->setPublic(false);
        $container->setDefinition(
            'mediapart_lapresselibre.psr7_factory', $factory
        );

        return $this;
    }

    /**
     *
     */
    private function loadOperation(array $config, ContainerBuilder $container)
    {
        $operation = new Definition(
            Operation::class,
            [
                new Reference('mediapart_lapresselibre.endpoint_factory'),
                new Reference('mediapart_lapresselibre.transaction_factory'),
                new Reference('mediapart_lapresselibre.psr7_factory'),
            ]
        );
        $operation->setPublic(false);
        $container->setDefinition(
            'mediapart_lapresselibre.operation', $operation
        );

        return $this;
    }

    /**
     *
     */
    private function loadController(array $config, ContainerBuilder $container)
    {
        $controller = new Definition(
            Controller::class,
            [
                new Reference('mediapart_lapresselibre.operation'),
            ]
        );
        $container->setDefinition(
            'mediapart_lapresselibre.controller', $controller
        );

        return $this;
    }
}
