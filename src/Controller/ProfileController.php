<?php

namespace App\Controller;

use App\Provider\CommentProvider;
use App\Provider\PostProvider;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;


/**
 * @Route("/api/profile")
 * @Security("is_granted('ROLE_USER')")
 * @OA\Tag(name="Profile")
 */
class ProfileController extends AbstractRestController
{
    /**
     * @Rest\Get(
     *     path = "/",
     *     name = "api_get_profile",
     * )
     * @Rest\View(serializerGroups={"default"})
     */
    public function getProfille()
    {
        $user = $this->getUser();
        return $user;
    }
    
    /**
     * @Rest\Get(
     *     path = "/comments",
     *     name = "api_get_profile_comments",
     * )
     * @Rest\View(serializerGroups={"default"})
     */
    public function getProfileComments(CommentProvider $commentProvider)
    {
        $user = $this->getUser();
        return $commentProvider->getCommentsFromUser($user);
    }
    
    /**
     * @Rest\Get(
     *     path = "/posts",
     *     name = "api_get_profile_posts",
     * )
     * @Rest\View(serializerGroups={"default"})
     */
    public function getProfilePosts(PostProvider $postProvider)
    {
        $user = $this->getUser();
        return $postProvider->getPostsFromUser($user);

    }

}