<?php

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class CommentManager extends AbstractManager
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getPagerListAll(?int $limit=10, ?int $page=1, ?array $filters=[]): Pagerfanta
    {
        $post = $user = null;
        if(isset($filters['post'])&&null!==$filters['post']) {
            $post = $this->em->getRepository(Post::class)->findOneBySlug($filters['post']);
        }
        if(isset($filters['user'])&&null!==$filters['user']) {
            $user = $this->em->getRepository(User::class)->find($filters['user']);
        }
        $query = $this->em->getRepository(Comment::class)->getQueryForCommentsList($post, $user);
        return $this->getPager($query, $limit, $page);
    }

    public function getPagerListForPost(Post $post, $limit=10, $page=1): Pagerfanta
    {
        $query = $this->em->getRepository(Comment::class)->getQueryForCommentsForPost($post);
        return $this->getPager($query, $limit, $page);
    }

    public function getPagerListFromUser(User $user, $limit=10, $page=1): Pagerfanta
    {
        $query = $this->em->getRepository(Comment::class)->getQueryForCommentsFromUser($user);
        return $this->getPager($query, $limit, $page);
    }

    public function create(Comment $comment): void
    {
        $this->em->persist($comment);
        $this->em->flush();
    }

    public function patch(Comment $comment, Comment $newComment): void
    {
        $comment->setBody($newComment->getBody());

        $this->em->flush();
    }

    public function delete(Comment $comment): void
    {
        $this->em->remove($comment);
    }
}

