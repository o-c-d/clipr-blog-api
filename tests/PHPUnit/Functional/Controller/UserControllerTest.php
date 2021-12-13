<?php

namespace App\Tests\Controller;

use App\Tests\ApiTestCase;

class UserControllerTest extends ApiTestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->makeAdminUser();
    }

    public function testList(): void
    {
        $data = $this->apiRequest(self::$admin, 'GET', '/api/users/', [
            'limit' => 2,
            'page' => 1,
        ]);
        $this->assertNotNull($data);
        $this->assertArrayHasKey('items', $data);
    }
}