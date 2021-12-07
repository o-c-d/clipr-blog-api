<?php

namespace App\Manager;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class PostManager extends AbstractManager
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getPagerListAll($limit=10, $page=1): Pagerfanta
    {
        $query = $this->em->getRepository(Post::class)->getQueryForPostsList();
        return $this->getPager($query, $limit, $page);
    }

    public function getPagerListFromUser(User $user, $limit=10, $page=1): Pagerfanta
    {
        $query = $this->em->getRepository(Post::class)->getQueryForPostsFromUser($user);
        return $this->getPager($query, $limit, $page);
    }

    public function create(Post $post): void
    {
        $this->em->persist($post);
        $this->em->flush();
    }

    public function patch(Post $post, Post $newPost): void
    {
        // Slug is not editable by design
        $post->setTitle($newPost->getTitle());
        $post->setDescription($newPost->getDescription());
        $post->setBody($newPost->getBody());

        $this->em->flush();
    }

    public function delete(Post $post): void
    {
        $this->em->remove($post);
    }

}

