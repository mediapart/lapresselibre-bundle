<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Factory;

use Psr\Http\Message\RequestInterface as Request;
use Mediapart\LaPresseLibre\Transaction;
use Mediapart\LaPresseLibre\Security\Identity;
use Mediapart\LaPresseLibre\Security\Encryption;

/**
 *
 */
class TransactionFactory
{
    /**
     *
     */
    private $publicKey;

    /**
     *
     */
    private $identity;

    /**
     *
     */
    private $encryption;

    /**
     *
     */
    public function __construct($publicKey, Identity $identity, Encryption $encryption)
    {
        $this->publicKey = $publicKey;
        $this->identity = $identity;
        $this->encryption = $encryption;
    }

    /**
     *
     */
    public function create(Request $request)
    {
        $transaction = new Transaction($this->identity, $this->encryption, $request);

        return $transaction;
    }

    /**
     *
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     *
     */
    public function getIdentity()
    {
        return $this->identity;
    }
}
