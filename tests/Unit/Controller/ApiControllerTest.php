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
use Mediapart\Bundle\LaPresseLibreBundle\Controller\ApiController;
use Mediapart\Bundle\LaPresseLibreBundle\Operation;

/**
 *
 */
class ApiControllerTest extends TestCase
{
    /**
     *
     */
    public function testBadRequest()
    {
        $response = $this->createResponseWithException(new \InvalidArgumentException());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     *
     */
    public function testUnauthorized()
    {
        $response = $this->createResponseWithException(new \UnexpectedValueException());

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     *
     */
    public function testInternalServerError()
    {
        $response = $this->createResponseWithException(new \Exception());

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    /**
     *
     */
    public function testSuccess()
    {
        $result = 'foobar';
        $operation = $this->createOperationMock($result);
        $request = $this->createMock(Request::class);

        $controller = new ApiController($operation);
        $response = $controller->executeAction($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($result, $response->getContent());
    }

    /**
     * @param Exception $exception
     * @return Response
     */
    private function createResponseWithException($exception)
    {
        $operation = $this->createOperationMock($exception);
        $request = $this->createMock(Request::class);

        $controller = new ApiController($operation);
        $response = $controller->executeAction($request);

        return $response;
    }

    /**
     * @param string|Exception
     * @return Operation
     */
    private function createOperationMock($return)
    {
        $operation = $this->createMock(Operation::class);
        $operation
            ->method('getHttpResponseHeader')
            ->willReturn([])
        ;
        $process = $operation->method('process');

        if ($return instanceof \Exception) {
            $process->will($this->throwException($return));
        } else {
            $process->willReturn($return);
        }

        return $operation;
    }
}
