<?php

namespace App\Tests\Controller;

use App\Tests\ApiTestCase;

class ProfileControllerTest extends ApiTestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->makeWriterUser();
    }

    public function testGetProfile(): void
    {
        $data = $this->apiRequest(self::$writer, 'GET', '/api/profile/');

        $this->assertSame(200, self::$writer->getResponse()->getStatusCode());
        $this->assertArrayHasKey('roles', $data);
    }

    public function testGetProfileComments(): void
    {
        $data = $this->apiRequest(self::$writer, 'GET', '/api/profile/comments');

        $this->assertSame(200, self::$writer->getResponse()->getStatusCode());
        $this->assertArrayHasKey('items', $data);
    }

    public function testGetProfilePosts(): void
    {
        $data = $this->apiRequest(self::$writer, 'GET', '/api/profile/posts');

        $this->assertSame(200, self::$writer->getResponse()->getStatusCode());
        $this->assertArrayHasKey('items', $data);
    }
}