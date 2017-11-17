<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Unit\Factory;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Mediapart\LaPresseLibre\Account\Link;
use Mediapart\LaPresseLibre\Account\Account;
use Mediapart\Bundle\LaPresseLibreBundle\Account\AccountProvider;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\RedirectionFactory;

class RedirectionFactoryTest extends TestCase
{
    public function testGenerateRedirection()
    {
        $link = $this->createMock(Link::class);
        $account = $this->createMock(Account::class);
        $provider = $this->createMock(AccountProvider::class);
        $request = $this->createMock(Request::class);
        $request->method('getQueryString')->willReturn('lplUser=lplCode');
        $provider->method('__invoke')->willReturn($account);
        $link->method('generate')->with('lplCode', $account)->willReturn('somewhere');

        $factory = new RedirectionFactory($link, $provider);
        $redirection = $factory->generate($request);

        $this->assertEquals('somewhere', $redirection);
    }
}
