<?php

namespace App\Tests\Controller;

use App\Tests\ApiTestCase;

class ProfileControllerTest extends ApiTestCase
{
    public function testList(): void
    {
        $this->authWriter();
        $this->assertNotNull($this->token);

        $data = $this->apiRequest('GET', 'http://127.0.0.1:8000/api/profile');
        // $this->assertNotNull($data);
        // $this->assertArrayHasKey('roles', $data);
    }
}