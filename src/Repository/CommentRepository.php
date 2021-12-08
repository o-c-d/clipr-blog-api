<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getQueryForCommentsList(?Post $post=null, ?User $user=null): Query
    {
        $qb = $this->createQueryBuilder('c');
        switch(!null) {
            case $post:
                $qb->andWhere('c.post=:post')->setParameter('post', $post);
            case $user:
                $qb->andWhere('c.createdBy=:user')->setParameter('user', $user);
        }
        return $qb->getQuery();
    }

    public function getQueryForCommentsForPost(Post $post): Query
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->andWhere('c.post=:post')->setParameter('post', $post)
            ->getQuery()
        ;
        return $qb;
    }

    public function getQueryForCommentsFromUser(User $user): Query
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->andWhere('c.createdBy=:user')->setParameter('user', $user)
            ->getQuery()
        ;
        return $qb;
    }

}
