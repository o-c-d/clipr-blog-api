<?php

namespace App\Tests\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Tests\ApiTestCase;

class PostControllerTest extends ApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->makeWriterUser();
        $this->makeAdminUser();
    }

    public function testList(): void
    {
        $data = $this->apiRequest(self::$writer, 'GET', '/api/posts/');
        $this->assertNotNull($data);
        $this->assertArrayHasKey('items',$data);
        $this->assertArrayHasKey('count', $data);
        $this->assertEquals(0, $data['count']);
    }

    public function testCreate(): void
    {
        $data = $this->apiRequest(self::$writer, 'POST', '/api/posts/', [], json_encode([
            'title' => 'Test title',
            'description' => 'Test description',
            'body' => 'Test body',
        ]));
        $this->assertNotNull($data);
        $this->assertArrayHasKey('title',$data);
        $this->assertEquals('Test title', $data['title']);

    }


    public function testUpdate(): void
    {
        $user = self::$container->get('doctrine')->getManager()->getRepository(User::class)->findOneBy(['email'=>'admin@test.dev']);
        $post = new Post();
        $post->setTitle('Test title');
        $post->setDescription('Test description');
        $post->setBody('Test body');
        $post->setCreatedBy($user);
        self::$container->get('App\Manager\PostManager')->create($post);
        $data = $this->apiRequest(self::$admin, 'PATCH', '/api/posts/'.$post->getSlug(), [], json_encode([
            'title' => 'Test title updated',
            'description' => 'Test description updated',
            'body' => 'Test body updated',
        ]));
        $this->assertNotNull($data);
        $this->assertArrayHasKey('title',$data);
        $this->assertEquals('Test title updated', $data['title']);

    }

    public function testImport(): void
    {
        $data = $this->apiRequest(self::$writer, 'POST', '/api/posts/import?uri='.\urlencode("https://www.reddit.com/r/reddit.com/comments/87/the_downing_street_memo/"));
        $this->assertNotNull($data);
        $this->assertArrayHasKey('title',$data);
        $this->assertEquals('The Downing Street Memo', $data['title']);

    }
}