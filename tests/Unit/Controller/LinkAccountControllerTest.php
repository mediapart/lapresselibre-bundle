<?php

/**
 * This file is part of the Mediapart LaPresseLibre Bundle.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre-bundle>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Unit\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Mediapart\Bundle\LaPresseLibreBundle\Controller\LinkAccountController;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\RedirectionFactory;

class LinkAccountControllerTest extends TestCase
{
	public function testSuccess()
	{
		$redirectionFactory = $this->createMock(RedirectionFactory::class);
        $request = $this->createMock(Request::class);
		$redirectionFactory
			->expects($this->once())
			->method('generate')
			->with($request)
			->willReturn('string')
		;

		$controller = new LinkAccountController($redirectionFactory);
		$response = $controller($request);

		$this->assertInstanceOf(RedirectResponse::class, $response);
		$this->assertEquals('string', $response->getTargetUrl());
	}

	public function testException()
	{
		$redirectionFactory = $this->createMock(RedirectionFactory::class);
        $request = $this->createMock(Request::class);
		$redirectionFactory
			->expects($this->once())
			->method('generate')
			->with($request)
			->will($this->throwException(new \Exception()))
		;

        $this->expectException(HttpException::class);

		$controller = new LinkAccountController($redirectionFactory);
		$response = $controller($request);
	}
}
