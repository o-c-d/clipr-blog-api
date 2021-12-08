<?php

namespace App\Tests\Controller;

use App\Tests\ApiTestCase;

class PostControllerTest extends ApiTestCase
{
    public function testList(): void
    {
        $this->authAnonymous();

        $data = $this->apiRequest('GET', 'api/posts/', [
            'limit' => 2,
            'page' => 1
        ], [], [], json_encode([]));
        $this->assertNull($this->token);
        $this->assertNotNull($data);
        $this->assertArrayHasKey('items',$data);
    }

    // public function testCreate(): void
    // {
    //     $this->authWriter();
    //     $data = $this->apiRequest('POST', 'api/posts/', [], [], [], json_encode([
    //         'title' => 'Test title',
    //         'description' => 'Test description',
    //         'body' => 'Test body',
    //     ]));
    //     $this->assertNotNull($data);
    //     $this->assertArrayHasKey('title',$data);
    //     $this->assertEquals('Test title', $data['title']);

    // }
}