<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Unit\Factory;

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Request;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\TransactionFactory;
use Mediapart\LaPresseLibre\Security\Identity;
use Mediapart\LaPresseLibre\Security\Encryption;

class TransactionFactoryTest extends TestCase
{
    public function testCreate()
    {
        $publicKey = 42;
        $timestamp = 1337;
        $signature = 'foobar';
        $identity = $this->createMock(Identity::class);
        $encryption = $this->createMock(Encryption::class);

        $identity->expects($this->once())->method('sign')->with($publicKey, $timestamp)->willReturn($signature);

        $request = new Request();
        $request = $request
            ->withHeader('X-PART', $publicKey)
            ->withHeader('X-TS', $timestamp)
            ->withHeader('X-LPL', $signature)
        ;

        $factory = new TransactionFactory($publicKey, $identity, $encryption);
        $object = $factory->create($request);

        $this->assertEquals($publicKey, $factory->getPublicKey());
        $this->assertEquals($identity, $factory->getIdentity());
    }
}
