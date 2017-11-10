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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Mediapart\Bundle\LaPresseLibreBundle\Controller\WebServicesController;
use Mediapart\Bundle\LaPresseLibreBundle\Handler;

class WebServicesControllerTest extends TestCase
{
    public function testBadRequest()
    {
        $this->expectException(HttpException::class);

        $response = $this->createResponseWithException(new \InvalidArgumentException());
    }

    public function testUnauthorized()
    {
        $this->expectException(HttpException::class);

        $response = $this->createResponseWithException(new \UnexpectedValueException());
    }

    public function testInternalServerError()
    {
        $this->expectException(HttpException::class);

        $response = $this->createResponseWithException(new \Exception());
    }

    public function testSuccess()
    {
        $result = 'foobar';
        $handler = $this->createHandlerMock($result);
        $request = $this->createMock(Request::class);

        $controller = new WebServicesController($handler);
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($result, $response->getContent());
    }

    /**
     * @param Exception $exception
     * @return Response
     */
    private function createResponseWithException($exception)
    {
        $handler = $this->createHandlerMock($exception);
        $request = $this->createMock(Request::class);

        $controller = new WebServicesController($handler);
        $response = $controller($request);

        return $response;
    }

    /**
     * @param string|Exception
     * @return Handler
     */
    private function createHandlerMock($return)
    {
        $handler = $this->createMock(Handler::class);
        $handler
            ->method('getHttpResponseHeaders')
            ->willReturn([])
        ;
        $process = $handler->method('process');

        if ($return instanceof \Exception) {
            $process->will($this->throwException($return));
        } else {
            $process->willReturn($return);
        }

        return $handler;
    }
}
