<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Mediapart\Bundle\LaPresseLibreBundle\Operation;

/**
 *
 */
class ApiController
{
    /**
     *
     */
    private $operation;

    /**
     *
     */
    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    /**
     *
     */
    public function executeAction(Request $request)
    {
        try {
            $status = Response::HTTP_OK;
            $result = $this->operation->process($request);
        } catch (\InvalidArgumentException $e) {
            $status = Response::HTTP_BAD_REQUEST;
            $result = $e->getMessage();
        } catch (\UnexpectedValueException $e) {
            $status = Response::HTTP_UNAUTHORIZED;
            $result = $e->getMessage();
        } catch (\Exception $e) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $result = 'Internal Error';
        }
        return new Response(
            $status==Response::HTTP_OK ? $result : json_encode(['error' => $result]),
            $status,
            $this->operation->headers()
        );
    }
}
