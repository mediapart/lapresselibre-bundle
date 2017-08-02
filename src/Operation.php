<?php

namespace Mediapart\Bundle\LaPresseLibreBundle;

use Mediapart\Bundle\LaPresseLibreBundle\Factory\EndpointFactory;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\TransactionFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class Operation
{
    /**
     *
     */
    private $endpointFactory;

    /**
     *
     */
    private $transactionFactory;

    /**
     *
     */
    private $psr7Factory;

    /**
     *
     */
    public function __construct(EndpointFactory $endpointFactory, TransactionFactory $transactionFactory, DiactorosFactory $psr7Factory)
    {
        $this->endpointFactory = $endpointFactory;
        $this->transactionFactory = $transactionFactory;
        $this->psr7Factory = $psr7Factory;
    }

    /**
     *
     */
    public function process(Request $request)
    {
        $route = $request->get('_route');
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
            ->create($route)
        ;

        return $transaction->process($endpoint);
    }

    /**
     *
     */
    public function headers()
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
