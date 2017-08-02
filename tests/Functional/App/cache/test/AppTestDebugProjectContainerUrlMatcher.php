<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * AppTestDebugProjectContainerUrlMatcher.
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class AppTestDebugProjectContainerUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $trimmedPathinfo = rtrim($pathinfo, '/');
        $context = $this->context;
        $request = $this->request;
        $requestMethod = $canonicalMethod = $context->getMethod();
        $scheme = $context->getScheme();

        if ('HEAD' === $requestMethod) {
            $canonicalMethod = 'GET';
        }


        // lapresselibre_verification
        if ('/verification' === $pathinfo) {
            return array (  '_controller' => 'mediapart_lapresselibre.controller:executeAction',  '_route' => 'lapresselibre_verification',);
        }

        // lapresselibre_account_creation
        if ('/account-creation' === $pathinfo) {
            return array (  '_controller' => 'mediapart_lapresselibre.controller:executeAction',  '_route' => 'lapresselibre_account_creation',);
        }

        // lapresselibre_account_updates
        if ('/account-updates' === $pathinfo) {
            return array (  '_controller' => 'mediapart_lapresselibre.controller:executeAction',  '_route' => 'lapresselibre_account_updates',);
        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
