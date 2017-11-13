<?php

namespace Mediapart\Bundle\LaPresseLibreBundle\Test\Functional\App\Account;

use Mediapart\LaPresseLibre\Account\Account;
use Mediapart\Bundle\LaPresseLibreBundle\Account\AccountProvider;

class Provider implements AccountProvider
{
    public function __invoke()
    {
        return new Account('test3@domain.tld', '');
    }
}
