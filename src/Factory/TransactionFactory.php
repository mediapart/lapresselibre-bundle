<?php

/**
 * This file is part of the Mediapart LaPresseLibre Bundle.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre-bundle>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\Bundle\LaPresseLibreBundle\Factory;

use Psr\Http\Message\RequestInterface as Request;
use Mediapart\LaPresseLibre\Transaction;
use Mediapart\LaPresseLibre\Security\Identity;
use Mediapart\LaPresseLibre\Security\Encryption;

class TransactionFactory
{
    /**
     * @var int
     */
    private $publicKey;

    /**
     * @var Identity
     */
    private $identity;

    /**
     * @var Encryption
     */
    private $encryption;

    /**
     * @param int $publicKey
     * @param Identity $identity
     * @param Encryption $encryption
     */
    public function __construct($publicKey, Identity $identity, Encryption $encryption)
    {
        $this->publicKey = $publicKey;
        $this->identity = $identity;
        $this->encryption = $encryption;
    }

    /**
     * @param Request
     *
     * @return Transaction
     */
    public function create(Request $request)
    {
        return new Transaction($this->identity, $this->encryption, $request);
    }

    /**
     * @return integer
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return Identity
     */
    public function getIdentity()
    {
        return $this->identity;
    }
}
