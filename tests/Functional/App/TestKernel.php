<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Functional\App;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Fake app that use our bundle to test it.
 */
class TestKernel extends Kernel
{
    /**
     * {@inheritDoc}
     */
    public function registerBundles()
    {
        $bundles = [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Mediapart\Bundle\LaPresseLibreBundle\MediapartLaPresseLibreBundle(),
        ];

        return $bundles;
    }

    /**
     * {@inheritDoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }

    /**
     * {@inheritDoc}
     */
    protected function getKernelParameters()
    {
        return array_merge(parent::getKernelParameters(), [
            'lapresselibre_publickey' => getenv('lapresselibre_publickey'),
            'lapresselibre_secretkey' => getenv('lapresselibre_secretkey'),
            'lapresselibre_aespassword' => getenv('lapresselibre_aespassword'),
            'lapresselibre_aesiv' => getenv('lapresselibre_aesiv'),
            'lapresselibre_aesoptions' => getenv('lapresselibre_aesoptions'),
            'kernel.secret' => rand(),
        ]);
    }
}
