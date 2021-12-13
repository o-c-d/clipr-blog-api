<?php

namespace App\Tests\Controller;

use App\Tests\ApiTestCase;

class SecurityControllerTest extends ApiTestCase
{
    public function testLogin(): void
    {
        $data = $this->apiRequest(self::$anonymous, 'POST', '/api/login/token', [], json_encode([
            "username" => "writer@test.dev",
            "password" => "writer",
        ]));
        $this->assertArrayHasKey("token", $data);
        $this->assertSame(200, self::$anonymous->getResponse()->getStatusCode());

        $data = $this->apiRequest(self::$anonymous, 'POST', '/api/login/token', [], json_encode([
            "username" => "toto",
            "password" => "toto",
        ]));
        $this->assertSame(401, self::$anonymous->getResponse()->getStatusCode());

    }

    protected function extractUsernameFromJWT(string $token): ?string
    {
        $tokenParts = explode('.', $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        if(isset($jwtPayload->username)) {
            return $jwtPayload->username;
        }
        return null;
    }
}