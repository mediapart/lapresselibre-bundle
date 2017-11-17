<?php

/**
 * This file is part of the Mediapart LaPresseLibre Bundle.
 *
 * CC BY-NC-SA <https://github.com/mediapart/lapresselibre-bundle>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mediapart\Bundle\LaPresseLibreBundle\Account;

use Mediapart\LaPresseLibre\Account\Account;

interface AccountProvider
{
    /**
     * @return Account
     */
    public function __invoke();
}
