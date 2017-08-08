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
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
        $headers = $this->operation->getHttpResponseHeader();
        try {
            return new Response(
                $this->operation->process($request),
                Response::HTTP_OK, 
                $headers
            );
        } catch (\InvalidArgumentException $exception) {
            $httpException = new BadRequestHttpException(
                $exception->getMessage(),
                $exception
            );
            $httpException->setHeaders($headers);
            throw $httpException;
        } catch (\UnexpectedValueException $exception) {
            $httpException = new AccessDeniedHttpException(
                $exception->getMessage(),
                $exception
            );
            $httpException->setHeaders($headers);
            throw $httpException;
        } catch (\Exception $exception) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Internal Error',
                $exception,
                $headers
            );
        }
    }
}
