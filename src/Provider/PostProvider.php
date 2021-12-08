<?php

namespace App\Provider;

use App\Entity\Post;
use App\Entity\User;
use App\Manager\PostManager;
use App\Provider\RedditProvider;
use Hateoas\Hateoas;
use Hateoas\HateoasBuilder;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use App\Representation\PaginatedCollectionRepresentation;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PostProvider
{
    // private Hateoas $hateoas;

    private PostManager $manager;
    private UrlGeneratorInterface $router;
    private RedditProvider $redditProvider;

    public function __construct(PostManager $manager, UrlGeneratorInterface $router, RedditProvider $redditProvider)
    {
        $this->manager = $manager;
        $this->router = $router;
        $this->redditProvider = $redditProvider;
        // $this->hateoas = HateoasBuilder::create()->build();

    }

    public function getAllPosts(?int $limit=10, ?int $page=1): array
    {
        $pager = $this->manager->getPagerListAll($limit, $page);
        // $pagerfantaFactory = new PagerfantaFactory('page', 'limit');
        // $sort = "created_at";
        // $sort_order = "DESC";
        // $paginatedCollection = $pagerfantaFactory->createRepresentation(
        //     $pager, 
        //     new Route('api_post_list', array('page' => $page, 'limit' => $limit, 'sort' => $sort, 'sort_order' => $sort_order)), 
        //     new CollectionRepresentation($pager->getCurrentPageResults())
        // );
        // return $paginatedCollection;
        $pcr = new PaginatedCollectionRepresentation($pager, $this->router, 'api_post_list');
        return $pcr->represent();
    }

    public function getPostsFromUser(User $user, ?int $limit=10, ?int $page=1): array
    {
        $pager = $this->manager->getPagerListFromUser($user, $limit, $page);
        $pcr = new PaginatedCollectionRepresentation($pager, $this->router, 'api_get_profile_posts');
        return $pcr->represent();
    }

    public function import($uri): Post
    {
        $postData = $this->redditProvider->getPostDataFromUri($uri);
        if(empty($postData))
        {
            // TODO: Post Not found error
        }
        $post = new Post();
        if(isset($postData['title'])) {
            $post->setTitle($postData['title']);
        }
        if(isset($postData['description'])) {
            $post->setDescription($postData['description']);
        }
        if (isset($postData['body'])) {
            $post->setBody($postData['body']);
        }
        $this->manager->create($post);
        return $post;
    }


}
