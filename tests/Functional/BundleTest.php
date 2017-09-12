<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Functional;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function testEndpoinWithoutHeaders()
    {
        $kernel = new TestKernel('test', true);
        $kernel->boot();

        $this->expectException(HttpException::class);

        $request = Request::create('https://localhost/verification');
        $response = $kernel->handle($request);
    }

    public function testVerificationEndpoint()
    {
        $kernel = new TestKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        $identity = $container->get('mediapart_lapresselibre.identity');
        $encryption = $container->get('mediapart_lapresselibre.encryption');

        $crd = ['Mail' => 'test@domain.tld', 'CodeUtilisateur' => '42'];
        $crd = $encryption->encrypt($crd);

        $request = Request::create('https://localhost/verification?crd='.$crd);
        $request->headers->replace([
            'X-PART' => 2,
            'X-LPL' => $identity->sign(2),
            'X-TS' => $identity->getDatetime()->getTimestamp(),
        ]);
        $response = $kernel->handle($request);
        $result = $encryption->decrypt($response->getContent(), OPENSSL_RAW_DATA & OPENSSL_NO_PADDING);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($result['AccountExist']);
    }
}
