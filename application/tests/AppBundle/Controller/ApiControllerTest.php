<?php

namespace AppBundle\Tests\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ApiControllerTest extends WebTestCase
{

    private $container;
    private static $staticClient;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {

        $kernel = self::bootKernel();
        $this->container = $kernel->getContainer();

        self::$staticClient = static::createClient([
                    'base_uri'    => $this->container->getParameter('project_host') . ":" . $this->container->getParameter('project_port'),
                    'http_errors' => false,
                        ], [
                    'HTTP_X_API_KEY' => $this->container->getParameter('api_key'),
        ]);
    }

    public function testGermanWrongAPIKeyExcute()
    {
        /* @var $client \Symfony\Bundle\FrameworkBundle\Client */
        self::$staticClient->request(Request::METHOD_POST, '/api/contact', [], [], [
            'HTTP_X_API_KEY'       => $this->container->getParameter('api_key') . "88",
            "HTTP_ACCEPT_LANGUAGE" => 'de'
        ]);

        $response = self::$staticClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame($responseData['message'], "API Schlüsselfehler.");
        $this->assertSame($responseData['code'], Response::HTTP_UNAUTHORIZED);
    }

    public function testEnglishWrongAPIKeyExcute()
    {
        /* @var $client \Symfony\Bundle\FrameworkBundle\Client */
        self::$staticClient->request(Request::METHOD_POST, '/api/contact', [], [], [
            'HTTP_X_API_KEY'       => $this->container->getParameter('api_key') . "88",
            "HTTP_ACCEPT_LANGUAGE" => 'en'
        ]);

        $response = self::$staticClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame($responseData['message'], "API key error.");
        $this->assertSame($responseData['code'], Response::HTTP_UNAUTHORIZED);
    }

    public function testWrongUrlExcute()
    {
        /* @var $client \Symfony\Bundle\FrameworkBundle\Client */
        self::$staticClient->request(Request::METHOD_POST, '/api/login', [], [], [
            "HTTP_ACCEPT_LANGUAGE" => 'en'
        ]);

        $response = self::$staticClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame($responseData['code'], Response::HTTP_NOT_FOUND);
    }

}
