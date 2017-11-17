<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Functional;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Mediapart\Bundle\LaPresseLibreBundle\Test\Functional\App\TestKernel;
use Mediapart\Bundle\LaPresseLibreBundle\MediapartLaPresseLibreBundle;

class LinkAccountTest extends TestCase
{
    public function testLinkAccount()
    {
        $kernel = new TestKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        $encryption = $container->get('mediapart_lapresselibre.encryption');
        $lplUser = $encryption->encrypt('7f75e972-d5c7-b0c5-1a1b-9d5a582cbd27');

        $request = Request::create('https://localhost/link-account?lplUser='.$lplUser);
        $response = $kernel->handle($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('https://beta.lapresselibre.fr/manage/link-result?lpl=', substr($response->getTargetUrl(), 0, 53));
    }
}
