<?php

namespace App\Tests\Controller;

use App\Tests\ApiTestCase;

class SecurityControllerTest extends ApiTestCase
{
    public function testLogin(): void
    {
        $token =  $this->getToken('writer@test.dev', 'writer');
        $this->assertNotNull($token);
        $username = $this->extractUsernameFromJWT($token);
        $this->assertEquals('writer@test.dev', $username);
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