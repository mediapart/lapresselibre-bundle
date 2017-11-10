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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Mediapart\Bundle\LaPresseLibreBundle\Factory\RedirectionFactory;

/**
 * Use to link an account
 */
class LinkAccountController
{
    use ThrowHttpException;

    /**
     * @var RedirectionFactory
     */
    private $redirectionFactory;

    /**
     * @param RedirectionFactory $redirectionFactory
     */
    public function __construct(RedirectionFactory $redirectionFactory)
    {
        $this->redirectionFactory = $redirectionFactory;
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
        try {
            $redirection = $this
                ->redirectionFactory
                ->generate($request)
            ;
            $response = new RedirectResponse($redirection);
        } catch (\Exception $exception) {
            $this->throwHttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Internal Error',
                $exception
            );
        }

        return $response;
    }
}
