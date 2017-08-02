<?php

namespace Mediapart\Bundle\LaPresseLibreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Mediapart\Bundle\LaPresseLibreBundle\DependencyInjection\OperationCompilerPass;

class MediapartLaPresseLibreBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new OperationCompilerPass());
    }
}
