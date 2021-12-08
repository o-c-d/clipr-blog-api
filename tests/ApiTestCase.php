<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class ApiTestCase extends WebTestCase
{

    protected array $exceptions=[];

    /**
     * @var HttpClientInterface
     */
    protected $client;

    protected ?string $token=null;

    protected function setUp(): void
    {
        $this->client = static::createClient();

    }

    protected function authAdmin(): void
    {
        $this->token = $this->getToken('admin@test.dev', 'admin');
    }

    protected function authWriter(): void
    {
        $this->token = $this->getToken('writer@test.dev', 'writer');
    }

    protected function authAnonymous(): void
    {
        $this->token = null;
    }

    protected function getToken(string $email, string $password): ?string
    {
        $data = $this->apiRequest('POST', 'api/login/token', [], [], [], json_encode([
            'username' => $email,
            'password' => $password
        ]));
        
        if(isset($data['token'])) {
            return $data['token'];
        }
        return null;
    }

    public function apiRequest(string $method, string $path, ?array $parameters = [], ?array $files = [], ?array $server=[], ?string $content=null, bool $changeHistory=true): ?array
    {
        if(null!==$this->token && $path!=='api/login/token') {
            $server['Authorization'] = 'Bearer '.$this->token;
        }
        try {
            $crawler = $this->client->request($method, $path, $parameters, $files, $server, $content, $changeHistory);
            $response = $this->client->getResponse();
        } catch(\Exception $e) {
            $this->exceptions[] = $e;
        } finally {

        }

        if (!$response) {
            return null;
        }

        return json_decode($response->getContent(), true);
    }

}

