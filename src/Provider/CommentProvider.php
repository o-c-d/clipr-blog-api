<?php

namespace App\Provider;

use App\Entity\Post;
use App\Entity\User;
use App\Manager\CommentManager;
use App\Representation\PaginatedCollectionRepresentation;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommentProvider
{
    private CommentManager $manager;
    private UrlGeneratorInterface $router;

    public function __construct(CommentManager $manager, UrlGeneratorInterface $router)
    {
        $this->manager = $manager;
        $this->router = $router;
    }

    public function getAllComments(?int $limit=10, ?int $page=1): array
    {
        $pager = $this->manager->getPagerListAll($limit, $page);
        $pcr = new PaginatedCollectionRepresentation($pager, $this->router, 'api_comment_list');
        return $pcr->represent();
    }

    public function getCommentsForPost(Post $post, ?int $limit=10, ?int $page=1): array
    {
        $pager = $this->manager->getPagerListFromUser($post, $limit, $page);
        $pcr = new PaginatedCollectionRepresentation($pager, $this->router, 'api_post_comment_list');
        return $pcr->represent();
    }

    public function getCommentsFromUser(User $user, ?int $limit=10, ?int $page=1): array
    {
        $pager = $this->manager->getPagerListFromUser($user, $limit, $page);
        $pcr = new PaginatedCollectionRepresentation($pager, $this->router, 'api_post_list');
        return $pcr->represent();
    }


}
