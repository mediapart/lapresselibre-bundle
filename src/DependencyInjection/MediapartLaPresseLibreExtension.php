<?php

/**
 * This file is part of the Mediapart LaPresseLibre Bundle.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre-bundle>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\Bundle\LaPresseLibreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Mediapart\LaPresseLibre\Transaction;
use Mediapart\LaPresseLibre\Registration;
use Mediapart\LaPresseLibre\Security\Encryption;
use Mediapart\LaPresseLibre\Security\Identity;
use Mediapart\Bundle\LaPresseLibreBundle\Controller\LaPresseLibreController as Controller;
use Mediapart\Bundle\LaPresseLibreBundle\Handler;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\EndpointFactory;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\TransactionFactory;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MediapartLaPresseLibreExtension extends Extension
{
    const PUBLIC_SERVICE = true;
    const PRIVATE_SERVICE = false;

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
            ->loadEndpointFactory($container)
            ->loadPsr7Factory($container)
            ->loadHandler($container)
            ->loadController($container)
            ->loadRegistration($config, $container)
        ;
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     *
     * @return self
     */
    private function loadIdentity(array $config, ContainerBuilder $container)
    {
        return $this->setDefinition(
            $container,
            'mediapart_lapresselibre.identity',
            Identity::class,
            [
                $config['secret_key'],
            ]
        );
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     *
     * @return self
     */
    private function loadEncryption(array $config, ContainerBuilder $container)
    {
        return $this->setDefinition(
            $container,
            'mediapart_lapresselibre.encryption',
            Encryption::class,
            [
                $config['aes_password'],
                $config['aes_iv'],
                $config['aes_options'],
            ]
        );
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     *
     * @return self
     */
    private function loadTransactionFactory(array $config, ContainerBuilder $container)
    {
        return $this->setDefinition(
            $container,
            'mediapart_lapresselibre.transaction_factory',
            TransactionFactory::class,
            [
                $config['public_key'],
                new Reference('mediapart_lapresselibre.identity'),
                new Reference('mediapart_lapresselibre.encryption'),
            ],
            self::PRIVATE_SERVICE
        );
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return self
     */
    private function loadEndpointFactory(ContainerBuilder $container)
    {
        return $this->setDefinition(
            $container,
            'mediapart_lapresselibre.endpoint_factory',
            EndpointFactory::class,
            [],
            self::PRIVATE_SERVICE
        );
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return self
     */
    private function loadPsr7Factory(ContainerBuilder $container)
    {
        return $this->setDefinition(
            $container,
            'mediapart_lapresselibre.psr7_factory',
            DiactorosFactory::class,
            [],
            self::PRIVATE_SERVICE
        );
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return self
     */
    private function loadHandler(ContainerBuilder $container)
    {
        return $this->setDefinition(
            $container,
            'mediapart_lapresselibre.handler',
            Handler::class,
            [
                new Reference('mediapart_lapresselibre.endpoint_factory'),
                new Reference('mediapart_lapresselibre.transaction_factory'),
                new Reference('mediapart_lapresselibre.psr7_factory'),
            ],
            self::PRIVATE_SERVICE
        );
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return self
     */
    private function loadController(ContainerBuilder $container)
    {
        return $this->setDefinition(
            $container,
            'mediapart_lapresselibre.controller',
            Controller::class,
            [
                new Reference('mediapart_lapresselibre.handler'),
            ]
        );
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     *
     * @return self
     */
    private function loadRegistration(array $config, ContainerBuilder $container)
    {
        return $this->setDefinition(
            $container,
            'mediapart_lapresselibre.registration',
            Registration::class,
            [
                $config['public_key'],
                new Reference('mediapart_lapresselibre.encryption'),
            ]
        );
    }

    /**
     * @param ContainerBuilder $container
     * @param string $id
     * @param string $class
     * @param array $arguments
     * @param boolean $private
     *
     * @return self
     */
    private function setDefinition(ContainerBuilder $container, $id, $class, $arguments = [], $public = self::PUBLIC_SERVICE)
    {
        $definition = new Definition($class, $arguments);
        $definition->setPublic($public);
        $container->setDefinition($id, $definition);
        return $this;
    }
}
