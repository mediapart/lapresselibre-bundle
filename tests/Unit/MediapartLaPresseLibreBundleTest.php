<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Mediapart\Bundle\LaPresseLibreBundle\MediapartLaPresseLibreBundle;
use Mediapart\Bundle\LaPresseLibreBundle\DependencyInjection\OperationCompilerPass;

/**
 *
 */
class MediapartLaPresseLibreBundleTest extends TestCase
{
    /**
     *
     */
    public function testBuild()
    {
        $container = $this->createMock(ContainerBuilder::class);

        $container
            ->expects($this->once())
            ->method('addCompilerPass')
            ->with($this->isInstanceOf(OperationCompilerPass::class))
        ;

        $bundle = new MediapartLaPresseLibreBundle();
        $bundle->build($container);
    }
}
