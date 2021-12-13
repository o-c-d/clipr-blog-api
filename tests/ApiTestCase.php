<?php

namespace App\Tests;

use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class ApiTestCase extends WebTestCase
{
    private static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $admin = null;
    protected static ?KernelBrowser $writer = null;
    protected static ?KernelBrowser $user = null;
    protected static ?KernelBrowser $anonymous = null;

    protected static $container;
    protected EntityManagerInterface $em;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        if (null === self::$client) {
            self::$client = static::createClient();
            self::$client->setServerParameter('HTTP_HOST', '127.0.0.1:8000');
        }

        if (null === self::$anonymous) {
            self::$anonymous = clone self::$client;
        }
 
        self::$container = static::getContainer();
        $em = self::$container->get('doctrine')->getManager();

        $loader = new Loader();
        $loader->addFixture(new UserFixtures(self::$container->get('App\Manager\UserManager')));

        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
   }

    public function makeAdminUser()
    {
        if (null === self::$admin) {
            self::$admin = clone self::$client;
            $this->createAuthenticatedClient(self::$admin, 'admin@test.dev', 'admin');
        }
    }

    public function makeWriterUser()
    {
        if (null === self::$writer) {
            self::$writer = clone self::$client;
            $this->createAuthenticatedClient(self::$writer, 'writer@test.dev', 'writer');
        }
    }

    public function makeUser()
    {
        if (null === self::$user) {
            self::$user = clone self::$client;
            $this->createAuthenticatedClient(self::$user, 'user@test.dev', 'user');
        }
    }

    protected function createAuthenticatedClient(KernelBrowser &$client, string $username, string $password): void
    {
        $client->request(
            'POST',
            '/api/login/token', [], [], [], json_encode([
            'username' => $username,
            'password' => $password
        ])
        );

        $data = \json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', \sprintf('Bearer %s', $data['token']));
        $client->setServerParameter('CONTENT_TYPE', 'application/json');
    }

    protected function apiRequest(KernelBrowser $client, string $method="GET", string $path, ?array $parameters=[], ?string $content=""): ?array
    {
        $files = [];
        $server = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ];
        $changeHistory = true;
        $client->request($method, $path, $parameters, $files, $server, $content, $changeHistory);

        return \json_decode($client->getResponse()->getContent(), true, 512, JSON_OBJECT_AS_ARRAY);
    }
}

