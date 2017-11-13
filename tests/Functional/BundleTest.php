<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Functional;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Mediapart\Bundle\LaPresseLibreBundle\Test\Functional\App\TestKernel;
use Mediapart\Bundle\LaPresseLibreBundle\MediapartLaPresseLibreBundle;

class BundleTest extends TestCase
{
    public function testAKernelWithTheBundle()
    {
        $kernel = new TestKernel('test', true);
        $kernel->boot();

        $bundle = $kernel->getBundle('MediapartLaPresseLibreBundle');

        $this->assertInstanceOf(MediapartLaPresseLibreBundle::class, $bundle);
    }
}
