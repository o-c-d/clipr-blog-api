<?php

namespace App\Controller;

use App\Provider\CommentProvider;
use App\Provider\PostProvider;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;


/**
 * @Route("/api/profile")
 * @Security("is_granted('ROLE_USER')")
 * @OA\Tag(name="Profile")
 * @OA\Response(response=401, ref="#/components/responses/Unauthorized")
 */
class ProfileController extends AbstractRestController
{
    /**
     * @Rest\Get(
     *     path = "/",
     *     name = "api_get_profile",
     * )
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"user_details"})
     * @OA\Response(
     *      response=Response::HTTP_OK, 
     *      description="Details about current User", 
     *      @Model(type=User::class, groups={"user_details"})
     * )
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
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"comment_details"})
     * @OA\Response(response=Response::HTTP_OK, ref="#/components/responses/PaginatedCommentsList")
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
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"post_details"})
     * @OA\Response(response=Response::HTTP_OK, ref="#/components/responses/PaginatedPostsList")
     */
    public function getProfilePosts(PostProvider $postProvider)
    {
        $user = $this->getUser();
        return $postProvider->getPostsFromUser($user);

    }

}