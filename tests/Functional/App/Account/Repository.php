<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Functional\App\Account;

use Mediapart\LaPresseLibre\Account\Account;
use Mediapart\LaPresseLibre\Account\Repository as RepositoryInterface;

class Repository implements RepositoryInterface
{
    private $accounts = [];

    public function __construct()
    {
        array_map(
            [$this, 'save'], 
            [
                new Account('test1@domain.tld', '99f104e8-2fa3-4a77-1664-5bac75fb668d'),
                new Account('test2@domain.tld', '68b3c837-c7f4-1b54-2efa-1c5cc2945c3f'),
            ]
        );
    }

    public function find($code)
    {
        return array_key_exists($code, $this->accounts) ? $this->accounts[$code] : null;
    }

    public function save(Account $account)
    {
        $this->accounts[$account->getCode()] = $account;
    }
}
