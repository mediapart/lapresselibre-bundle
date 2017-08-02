<?php

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
        $response = $this->getResponseWithException(new \InvalidArgumentException());

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     *
     */
    public function testUnauthorized()
    {
        $response = $this->getResponseWithException(new \UnexpectedValueException());

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     *
     */
    public function testInternalServerError()
    {
        $response = $this->getResponseWithException(new \Exception());

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    /**
     *
     */
    public function testSuccess()
    {
        $result = 'foobar';
        $operation = $this->createMock(Operation::class);
        $request = $this->createMock(Request::class);

        $operation
            ->method('process')
            ->willReturn($result)
        ;
        $operation
            ->method('headers')
            ->willReturn([])
        ;

        $controller = new ApiController($operation);
        $response = $controller->executeAction($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($result, $response->getContent());
    }

    /**
     *
     */
    private function getResponseWithException($exception)
    {
        $operation = $this->createMock(Operation::class);
        $request = $this->createMock(Request::class);

        $operation
            ->method('process')
            ->will(
                $this->throwException($exception)
            )
        ;
        $operation
            ->method('headers')
            ->willReturn([])
        ;

        $controller = new ApiController($operation);
        $response = $controller->executeAction($request);

        return $response;
    }
}
