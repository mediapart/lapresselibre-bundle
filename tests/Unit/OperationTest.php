<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Unit;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface as Psr7Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Mediapart\LaPresseLibre\Transaction;
use Mediapart\LaPresseLibre\Endpoint;
use Mediapart\LaPresseLibre\Security\Identity;
use Mediapart\Bundle\LaPresseLibreBundle\Operation;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\EndpointFactory;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\TransactionFactory;

/**
 *
 */
class OperationTest extends TestCase
{
    /**
     *
     */
    public function testHeaders()
    {
        $publicKey = 42;
        $timestamp = 1337;
        $signature = 'loremipsumdolor';
        $datetime = $this->createMock(\DateTime::class);
        $identity = $this->createMock(Identity::class);
        $endpointFactory = $this->createMock(EndpointFactory::class);
        $psr7Factory = $this->createMock(DiactorosFactory::class);
        $transactionFactory = $this->createMock(TransactionFactory::class);
        $transactionFactory->method('getIdentity')->willReturn($identity);
        $transactionFactory->method('getPublicKey')->willReturn($publicKey);
        $identity->method('getDatetime')->willReturn($datetime);
        $identity->method('sign')->with($this->equalTo($publicKey))->willReturn($signature);
        $datetime->method('getTimestamp')->willReturn($timestamp);

        $operation = new Operation($endpointFactory, $transactionFactory, $psr7Factory);

        $this->assertEquals(
            [
                'X-PART' => $publicKey,
                'X-LPL' => $signature,
                'X-TS' => $timestamp
            ],
            $operation->headers()
        );
    }

    /**
     *
     */
    public function testProcess()
    {
        $content = 'commeunzephir';
        $route = 'lorem';
        $endpointFactory = $this->createMock(EndpointFactory::class);
        $psr7Factory = $this->createMock(DiactorosFactory::class);
        $transactionFactory = $this->createMock(TransactionFactory::class);
        $request = $this->createMock(Request::class);
        $psr7Request = $this->createMock(Psr7Request::class);
        $transaction = $this->createMock(Transaction::class);
        $endpoint = $this->createMock(Endpoint::class);

        $request->method('get')->with($this->equalTo('_route'))->willReturn($route);
        $psr7Factory->method('createRequest')->with($this->equalTo($request))->willReturn($psr7Request);
        $transactionFactory->method('create')->with($this->equalTo($psr7Request))->willReturn($transaction);
        $endpointFactory->method('create')->with($this->equalTo($route))->willReturn($endpoint);
        $transaction->expects($this->once())->method('process')->with($this->equalTo($endpoint))->willReturn($content);

        $operation = new Operation($endpointFactory, $transactionFactory, $psr7Factory);
        $result = $operation->process($request);

        $this->assertEquals($content, $result);
    }
}
