<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Mahmoud Mostafa <micheal.mouner@gmail.com>
 */
class CheckAPITokenListener
{
    /* @var $apiKeys array */

    private $apiKey;
    private $translator;

    /**
     * @param APIOperations $apiOperations
     */
    public function __construct(\Symfony\Component\Translation\TranslatorInterface $translator, $APIKey)
    {
        $this->apiKey = $APIKey;
        $this->translator = $translator;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $request->setLocale($request->getPreferredLanguage());

        if(strpos($request->getRequestUri(), '/api/doc') === false && $request->getRequestUri() != '/' && strpos($request->getRequestUri(), '/_profiler') === false && strpos($request->getRequestUri(), '/_wdt') === false)
        {
            $apiKeyIndex = $request->headers->get('x-api-key');
            if($apiKeyIndex != $this->apiKey)
            {
                $apiProblem = new \AppBundle\Api\ApiProblem(Response::HTTP_UNAUTHORIZED);
                $apiProblem->set('message', $this->translator->trans('apikey.error', [], null, $request->getPreferredLanguage()));
                $responseFactory = new \AppBundle\Api\ResponseFactory();
                $response = $responseFactory->createResponse($apiProblem);
                $event->setResponse($response);
                return;
            }
        }
    }

}
