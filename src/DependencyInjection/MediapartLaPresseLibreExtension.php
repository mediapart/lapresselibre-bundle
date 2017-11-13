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
use Mediapart\LaPresseLibre\Account\Link;
use Mediapart\LaPresseLibre\Security\Encryption;
use Mediapart\LaPresseLibre\Security\Identity;
use Mediapart\Bundle\LaPresseLibreBundle\Controller;
use Mediapart\Bundle\LaPresseLibreBundle\Handler;
use Mediapart\Bundle\LaPresseLibreBundle\Factory;

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
            ->loadRedirectionFactory($config, $container)
            ->loadEndpointFactory($container)
            ->loadPsr7Factory($container)
            ->loadHandler($container)
            ->loadWebServicesController($container)
            ->loadLinkAccountController($config, $container)
            ->loadRegistration($config, $container)
            ->loadLink($config, $container)
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
            Factory\TransactionFactory::class,
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
            Factory\EndpointFactory::class,
            [],
            self::PRIVATE_SERVICE
        );
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     *
     * @return self
     */
    private function loadRedirectionFactory(array $config, ContainerBuilder $container)
    {
        return $this->setDefinition(
            $container,
            'mediapart_lapresselibre.redirection_factory',
            Factory\RedirectionFactory::class,
            [
                new Reference('mediapart_lapresselibre.link'),
                new Reference($config['account']['provider'])
            ],
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
    private function loadWebServicesController(ContainerBuilder $container)
    {
        return $this->setDefinition(
            $container,
            'mediapart_lapresselibre.webservices_controller',
            Controller\WebServicesController::class,
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
    private function loadLinkAccountController(array $config, ContainerBuilder $container)
    {
        return $this->setDefinition(
            $container,
            'mediapart_lapresselibre.linkaccount_controller',
            Controller\LinkAccountController::class,
            [
                new Reference('mediapart_lapresselibre.redirection_factory'),
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

    private function loadLink(array $config, ContainerBuilder $container)
    {
        return $this->setDefinition(
            $container,
            'mediapart_lapresselibre.link',
            Link::class,
            [
                new Reference('mediapart_lapresselibre.encryption'),
                new Reference($config['account']['repository']),
                $config['public_key'],
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
