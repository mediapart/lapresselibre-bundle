<?php

/**
 * This file is part of the Mediapart LaPresseLibre Bundle.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre-bundle>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\Bundle\LaPresseLibreBundle;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\EndpointFactory;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\TransactionFactory;

class Handler
{
    /**
     * @var EndpointFactory
     */
    private $endpointFactory;

    /**
     * @var TransactionFactory
     */
    private $transactionFactory;

    /**
     * @var DiactorosFactory
     */
    private $psr7Factory;

    /**
     * @param EndpointFactory $endpointFactory
     * @param TransactionFactory $transactionFactory
     * @param DiactorosFactory $psr7Factory
     */
    public function __construct(EndpointFactory $endpointFactory, TransactionFactory $transactionFactory, DiactorosFactory $psr7Factory)
    {
        $this->endpointFactory = $endpointFactory;
        $this->transactionFactory = $transactionFactory;
        $this->psr7Factory = $psr7Factory;
    }

    /**
     * @param Request $request
     * @return mixed Result of La Presse Libre transaction
     */
    public function process(Request $request)
    {
        $psrRequest = $this
            ->psr7Factory
            ->createRequest($request)
        ;
        $transaction = $this
            ->transactionFactory
            ->create($psrRequest)
        ;
        $endpoint = $this
            ->endpointFactory
            ->create($request->get('_route'))
        ;

        return $transaction->process($endpoint);
    }

    /**
     * @return array Required HTTP Response headers
     */
    public function getHttpResponseHeaders()
    {
        $publicKey = $this->transactionFactory->getPublicKey();
        $identity = $this->transactionFactory->getIdentity();

        return [
            'X-PART' => $publicKey,
            'X-LPL' => $identity->sign($publicKey),
            'X-TS' => $identity->getDatetime()->getTimestamp(),
        ];
    }
}
