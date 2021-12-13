<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Manager\CommentManager;
use App\Manager\PostManager;
use App\Provider\CommentProvider;
use App\Provider\PostProvider;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\HttpFoundation\Response;
use Hateoas\Representation\PaginatedRepresentation;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route("/api/posts")
 * @OA\Tag(name="Post")
 */
class PostController extends AbstractRestController
{
    /**
     * @Rest\Get(path="/", name="api_post_list")
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="Max number of posts per page."
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="The current page"
     * )
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"post_details"})
     * @OA\Response(response=Response::HTTP_OK, ref="#/components/responses/PaginatedPostsList")
     */
    public function listAction(ParamFetcherInterface $paramFetcher, PostProvider $provider)
    {
        return $provider->getAllPosts($paramFetcher->get('limit'),$paramFetcher->get('page'));
    }

    /**
     * @Rest\Get(
     *     path = "/{slug}",
     *     name = "api_post_show",
     *     requirements = {"slug"="[a-zA-Z0-9\-_\/]+"}
     * )
     * @ParamConverter("post", options={"mapping": {"slug": "slug"}})
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"post_details"})
     * @OA\Response(
     *     response=200,
     *     description="Details about a Post",
     *     @Model(type=Post::class, groups={"post_details"})
     * )
     */
    public function showAction(Post $post)
    {
        return $post;
    }

    /**
     * @Rest\Post(
     *     path = "/import",
     *     name = "api_post_import",
     * )
     * @Rest\QueryParam(
     *     name="uri",
     *     description="Uri to import."
     * )
     * @Security("is_granted('ROLE_WRITER')")
     * @Rest\View(statusCode=201, serializerGroups={"post_details"})
     * @OA\Response(
     *     response=201,
     *     description="Details about imported Post",
     *     @OA\JsonContent(ref=@Model(type=Post::class, groups={"post_details"}))
     * )
     * @OA\Response(response=401, ref="#/components/responses/Unauthorized")
     */
    public function importAction(ParamFetcherInterface $paramFetcher, PostProvider $postProvider)
    {
        $post  = $postProvider->import($paramFetcher->get('uri'));
        return $post;
    }

    /**
     * @Rest\Post(path="/", name="api_post_create")
     * @Rest\View(statusCode=201, serializerGroups={"post_details"})
     * @ParamConverter("post", converter="fos_rest.request_body", options={"deserializationContext"={"groups"={"post_input"}, "version"="1.0"}})
     * @Security("is_granted('ROLE_WRITER')", statusCode=401, message="Authentication required.")
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref=@Model(type=Post::class, groups={"post_input"}))
     * ),
     * @OA\Response(
     *     response=201,
     *     description="Returns the new Post",
     *     @OA\JsonContent(ref=@Model(type=Post::class, groups={"post_details"}))
     * )
     * @OA\Response(response=401, ref="#/components/responses/Unauthorized")
     */
    public function createAction(Post $post, PostManager $manager, ConstraintViolationListInterface $validationErrors)
    {
        $this->checkViolations($validationErrors);

        $manager->create($post);

        return $post;
    }

    /**
     * @Rest\View(statusCode=200, serializerGroups={"post_details"})
     * @Rest\Patch(
     *     path = "/{slug}",
     *     name = "api_post_update",
     *     requirements = {"slug"="[a-zA-Z0-9\-_\/]+"}
     * )
     * @ParamConverter("post", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("newPost", converter="fos_rest.request_body", options={"deserializationContext"={"groups"={"post_input"}, "version"="1.0"}})
     * @Security("is_granted('edit', post)", statusCode=404, message="Resource not found.")
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref=@Model(type=Post::class, groups={"post_input"}))
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Returns the updated Post",
     *     @OA\JsonContent(ref=@Model(type=Post::class, groups={"post_details"}))
     * )
     * @OA\Response(response=401, ref="#/components/responses/Unauthorized")
     * @OA\Response(response=403, ref="#/components/responses/AccessDenied")
     */
    public function updateAction(Post $post, Post $newPost, ConstraintViolationListInterface $validationErrors, PostManager $manager)
    {
        $this->checkViolations($validationErrors);

        $manager->patch($post, $newPost);

        return $post;
    }

    /**
     * @Rest\View(statusCode=200, serializerGroups={"post_details"})
     * @Rest\Post(
     *     path = "/{slug}/comments",
     *     name = "api_post_comment",
     *     requirements = {"slug"="[a-zA-Z0-9\-_\/]+"}
     * )
     * @ParamConverter("post", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("comment", converter="fos_rest.request_body", options={"deserializationContext"={"groups"={"comment_input"}, "version"="1.0"}})
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref=@Model(type=Comment::class, groups={"comment_input"}))
     * ),
     * @OA\Response(
     *     response=201,
     *     description="Returns the Post and his new Comment",
     *     @OA\JsonContent(ref=@Model(type=Post::class, groups={"post_details"}))
     * )
     */
    public function commentAction(Post $post, Comment $comment, ConstraintViolationListInterface $validationErrors, CommentManager $manager)
    {
        $this->checkViolations($validationErrors);

        $comment->setPost($post);
        $manager->create($comment);

        return $post;
    }


    /**
     * @Rest\View(StatusCode = 204)
     * @Rest\Delete(
     *     path = "/{slug}",
     *     name = "api_post_delete",
     *     requirements = {"slug"="[a-zA-Z0-9\-_\/]+"}
     * )
     * @ParamConverter("post", options={"mapping": {"slug": "slug"}})
     * @Security("is_granted('delete', post)", statusCode=404, message="Resource not found.")
     * @OA\Response(response=204, description="Response for a successfull deletion of Comment")
     * @OA\Response(response=401, ref="#/components/responses/Unauthorized")
     * @OA\Response(response=403, ref="#/components/responses/AccessDenied")
     */
    public function deleteAction(Post $post, PostManager $manager)
    {
        $manager->delete($post);

        return;
    }
}     