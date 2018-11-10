<?php

namespace AppBundle\Tests\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AccountControllerTest extends WebTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $container;
    private static $staticClient;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {

        $kernel = self::bootKernel();
        $this->container = $kernel->getContainer();
        $this->entityManager = $this->container
                ->get('doctrine')
                ->getManager();
        self::$staticClient = static::createClient([
                    'base_uri'    => $this->container->getParameter('project_host') . ":" . $this->container->getParameter('project_port'),
                    'http_errors' => false,
                        ], [
                    'HTTP_X_API_KEY' => $this->container->getParameter('api_key'),
        ]);
    }

    public function testSuccessAddContactExcute()
    {
        $email = "micheal.mouner@gmail.com";
        $message = "I love coding.";
        /* @var $client \Symfony\Bundle\FrameworkBundle\Client */
        self::$staticClient->request('POST', '/api/contact', [
            'email'   => $email,
            'message' => $message,
                ], [], [
            "HTTP_ACCEPT_LANGUAGE" => 'en'
        ]);

        $response = self::$staticClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame(Response::HTTP_OK, $responseData['code']);
        $this->assertArrayHasKey("data", $responseData);
        $this->assertArrayHasKey("id", $responseData['data']);
        $this->assertSame($responseData['data']['email'], $email);
        $this->assertSame($responseData['data']['message'], $message);
    }

    public function testEnglishEmailValidationErrorExcute()
    {
        $email = "micheal.mouner@gmail";
        $message = "I love coding.";
        /* @var $client \Symfony\Bundle\FrameworkBundle\Client */
        self::$staticClient->request('POST', '/api/contact', [
            'email'   => $email,
            'message' => $message,
                ], [], [
            "HTTP_ACCEPT_LANGUAGE" => 'en'
        ]);

        $response = self::$staticClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $responseData['code']);
        $this->assertArrayHasKey("errors", $responseData);
        $this->assertArrayHasKey("email", $responseData['errors']);
        $this->assertSame($responseData['errors']['email'][0], 'The email is not a valid email.');
        $this->assertSame($responseData['message'], 'Form validation error.');
    }

    public function testGermanEmailValidationErrorExcute()
    {
        $email = "micheal.mouner@gmail";
        $message = "I love coding.";
        /* @var $client \Symfony\Bundle\FrameworkBundle\Client */
        self::$staticClient->request('POST', '/api/contact', [
            'email'   => $email,
            'message' => $message,
                ], [], [
            "HTTP_ACCEPT_LANGUAGE" => 'de'
        ]);

        $response = self::$staticClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $responseData['code']);
        $this->assertArrayHasKey("errors", $responseData);
        $this->assertArrayHasKey("email", $responseData['errors']);
        $this->assertSame($responseData['errors']['email'][0], 'Die E-Mail ist keine gültige E-Mail.');
        $this->assertSame($responseData['message'], 'Fehler bei der Formularüberprüfung.');
    }

    public function testEnglishMessageValidationErrorExcute()
    {
        $email = "micheal.mouner@gmail.com";
        $message = str_pad("", 1001, "A", STR_PAD_RIGHT);

        /* @var $client \Symfony\Bundle\FrameworkBundle\Client */
        self::$staticClient->request('POST', '/api/contact', [
            'email'   => $email,
            'message' => $message,
                ], [], [
            "HTTP_ACCEPT_LANGUAGE" => 'en'
        ]);

        $response = self::$staticClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $responseData['code']);
        $this->assertArrayHasKey("errors", $responseData);
        $this->assertArrayHasKey("message", $responseData['errors']);
        $this->assertSame($responseData['errors']['message'][0], 'Your message cannot be longer than 1000 characters.');
        $this->assertSame($responseData['message'], 'Form validation error.');
    }

    public function testGermanMessageValidationErrorExcute()
    {
        $email = "micheal.mouner@gmail.com";
        $message = str_pad("", 1001, "A", STR_PAD_RIGHT);

        /* @var $client \Symfony\Bundle\FrameworkBundle\Client */
        self::$staticClient->request('POST', '/api/contact', [
            'email'   => $email,
            'message' => $message,
                ], [], [
            "HTTP_ACCEPT_LANGUAGE" => 'de'
        ]);

        $response = self::$staticClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $responseData['code']);
        $this->assertArrayHasKey("errors", $responseData);
        $this->assertArrayHasKey("message", $responseData['errors']);
        $this->assertSame($responseData['errors']['message'][0], 'Ihre Nachricht darf nicht länger als 1000 Zeichen sein.');
        $this->assertSame($responseData['message'], 'Fehler bei der Formularüberprüfung.');
    }


    public function testEnglishNotBlankValidationErrorExcute()
    {
        $email = "";
        $message = "";

        /* @var $client \Symfony\Bundle\FrameworkBundle\Client */
        self::$staticClient->request('POST', '/api/contact', [
            'email'   => $email,
            'message' => $message,
                ], [], [
            "HTTP_ACCEPT_LANGUAGE" => 'en'
        ]);

        $response = self::$staticClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $responseData['code']);
        $this->assertArrayHasKey("errors", $responseData);
        $this->assertArrayHasKey("message", $responseData['errors']);
        $this->assertSame($responseData['errors']['message'][0], 'This value should not be blank.');
        $this->assertSame($responseData['errors']['email'][0], 'This value should not be blank.');
        $this->assertSame($responseData['message'], 'Form validation error.');
    }

    public function testGermanNotBlankValidationErrorExcute()
    {
        $email = "";
        $message = "";

        /* @var $client \Symfony\Bundle\FrameworkBundle\Client */
        self::$staticClient->request('POST', '/api/contact', [
            'email'   => $email,
            'message' => $message,
                ], [], [
            "HTTP_ACCEPT_LANGUAGE" => 'de'
        ]);

        $response = self::$staticClient->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $responseData['code']);
        $this->assertArrayHasKey("errors", $responseData);
        $this->assertArrayHasKey("message", $responseData['errors']);
        $this->assertSame($responseData['errors']['message'][0], 'Dieser Wert sollte nicht leer sein.');
        $this->assertSame($responseData['errors']['email'][0], 'Dieser Wert sollte nicht leer sein.');
        $this->assertSame($responseData['message'], 'Fehler bei der Formularüberprüfung.');
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $users = $this->entityManager->getRepository("AppBundle:Contact")->findAll();
        foreach($users as $user)
        {
            $this->entityManager->remove($user);
        }
        $this->entityManager->flush();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }

}
