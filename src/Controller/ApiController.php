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
     * @throws HttpException
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
            $this->throwHttpException(
                Response::HTTP_BAD_REQUEST,
                $exception->getMessage(),
                $exception,
                $headers
            );
        } catch (\UnexpectedValueException $exception) {
            $this->throwHttpException(
                Response::HTTP_UNAUTHORIZED,
                $exception->getMessage(),
                $exception,
                $headers
            );
        } catch (\Exception $exception) {
            $this->throwHttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Internal Error',
                $exception,
                $headers
            );
        }
    }

    /**
     * @param integer $code
     * @param string $message
     * @param Exception $exception
     * @param array $headers
     * @throws HttpException
     */
    private function throwHttpException($code, $message = '', \Exception $exception, array $headers = [])
    {
        throw new HttpException($code, $message, $exception, $headers);
    }
}
