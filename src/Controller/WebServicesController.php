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
use Mediapart\Bundle\LaPresseLibreBundle\Handler;

/**
 * Use to respond to the La Presse Libre requests.
 */
class WebServicesController
{
    /**
     * @var Handler
     */
    private $handler;

    /**
     * @param Handler $handler
     */
    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws HttpException
     */
    public function __invoke(Request $request)
    {
        $headers = array_merge(
            $this->handler->getHttpResponseHeaders(),
            ['Content-Type' => 'application/json']
        );
        try {
            $response = new Response(
                $this->handler->process($request),
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

        return $response;
    }

    /**
     * @param integer $code
     * @param string $message
     * @param \Exception $exception
     * @param array $headers
     *
     * @throws HttpException
     */
    protected function throwHttpException($code, $message = '', \Exception $exception, array $headers = [])
    {
        throw new HttpException($code, $message, $exception, $headers);
    }
}