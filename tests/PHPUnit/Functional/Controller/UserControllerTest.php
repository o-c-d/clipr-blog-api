<?php

namespace App\Tests\Controller;

use App\Tests\ApiTestCase;

class UserControllerTest extends ApiTestCase
{
    public function testList(): void
    {
        $this->authAdmin();
        $this->assertNotNull($this->token);

        $data = $this->apiRequest('GET', 'http://127.0.0.1:8000/api/users/', [
            'limit' => 2,
            'page' => 1,
        ], [], [
            'Authorization' => 'Bearer ' . $this->token,
        ], json_encode([]));
        // $this->assertNotNull($data);
        // $this->assertArrayHasKey('items', $data);
    }
}