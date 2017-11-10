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

use Symfony\Component\HttpKernel\Exception\HttpException;

trait ThrowHttpException
{
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
