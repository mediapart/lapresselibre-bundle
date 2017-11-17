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

use Symfony\Component\HttpFoundation\Request;
use Mediapart\LaPresseLibre\Account\Link;
use Mediapart\Bundle\LaPresseLibreBundle\Account\AccountProvider;

class RedirectionFactory
{
    /**
     * @var Link
     */
    private $link;

    /**
     * @var AccountProvider
     */
    private $accountProvider;
    
    /**
     * @param Link $link
     * @param AccountProvider $accountProvider
     */
    public function __construct(Link $link, AccountProvider $accountProvider)
    {
        $this->link = $link;
        $this->accountProvider = $accountProvider;
    }

    /**
     * @param Request $request
     * @return string Redirection url.
     */
    public function generate(Request $request)
    {
        parse_str($request->getQueryString(), $query);
        $input = $query['lplUser'];

        $logguedAccount = call_user_func($this->accountProvider);

        $redirection = $this
            ->link
            ->generate($input, $logguedAccount)
        ;

        return $redirection;
    }
}
