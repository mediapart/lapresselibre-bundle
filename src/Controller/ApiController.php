<?php

/**
 * This file is part of the Mediapart LaPresseLibre Bundle.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre-bundle>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\Bundle\LaPresseLibreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Mediapart\Bundle\LaPresseLibreBundle\Operation;

/**
 * Use to respond to the La Presse Libre requests.
 */
class ApiController
{
    /**
     * @var Operation
     */
    private $operation;

    /**
     * @param Operation $operation
     */
    public function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param Request $request
     * @return Response
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
            Response::HTTP_OK==$status 
                ? $result
                : json_encode(['error' => $result]),
            $status,
            $this->operation->getHttpResponseHeader()
        );
    }
}
