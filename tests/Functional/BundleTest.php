<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Functional;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Mediapart\Bundle\LaPresseLibreBundle\Test\Functional\App\TestKernel;
use Mediapart\Bundle\LaPresseLibreBundle\MediapartLaPresseLibreBundle;

/**
 *
 */
class BundleTest extends TestCase
{
    /**
     *
     */
    public function testAKernelWithTheBundle()
    {
        $kernel = new TestKernel('test', true);
        $kernel->boot();

        $bundle = $kernel->getBundle('MediapartLaPresseLibreBundle');

        $this->assertInstanceOf(MediapartLaPresseLibreBundle::class, $bundle);
    }

    /**
     *
     */
    public function testEndpoinWithoutHeaders()
    {
        $kernel = new TestKernel('test', true);
        $kernel->boot();

        $request = Request::create('/verification');
        $response = $kernel->handle($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('Missing header X-PART', json_decode($response->getContent())->error);
    }

    /**
     *
     */
    public function testVerificationEndpoint()
    {
        $kernel = new TestKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        $identity = $container->get('mediapart_lapresselibre.identity');
        $encryption = $container->get('mediapart_lapresselibre.encryption');

        $crd = ['Mail' => 'test@domain.tld', 'CodeUtilisateur' => '42'];
        $crd = $encryption->encrypt($crd);

        $request = Request::create('/verification?crd='.$crd);
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
