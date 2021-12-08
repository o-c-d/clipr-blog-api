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
     * @Rest\View(serializerGroups={"default"})
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
     * @Rest\View(serializerGroups={"default"})
     * @OA\Response(
     *     response=200,
     *     description="Post",
     *     @Model(type=Post::class, groups={"default"})
     * )
     */
    public function showAction(Post $post)
    {
        return $post;
    }

    /**
     * @Rest\View(StatusCode = 200)
     * @Rest\Post(
     *     path = "/import",
     *     name = "api_post_import",
     * )
     * @Rest\QueryParam(
     *     name="uri",
     *     description="Uri to import."
     * )
     * @Security("is_granted('ROLE_WRITER')")
     */
    public function importAction(ParamFetcherInterface $paramFetcher, PostProvider $postProvider)
    {
        $post  = $postProvider->import($paramFetcher->get('uri'));
        return $post;
    }

    /**
     * @Rest\Post(path="/", name="api_post_create")
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("post", converter="fos_rest.request_body")
     * @Security("is_granted('ROLE_WRITER')")
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref=@Model(type=Post::class, groups={"post_input"}))
     * ),
     * @OA\Response(
     *     response=201,
     *     description="Returns the new Post",
     *     @OA\JsonContent(ref=@Model(type=Post::class, groups={"default"}))
     * )
     */
    public function createAction(Post $post, PostManager $manager, ConstraintViolationListInterface $validationErrors)
    {
        $this->checkViolations($validationErrors);

        $manager->create($post);

        return $post;
    }

    /**
     * @Rest\View(StatusCode = 200)
     * @Rest\Patch(
     *     path = "/{slug}",
     *     name = "api_post_update",
     *     requirements = {"slug"="[a-zA-Z0-9\-_\/]+"}
     * )
     * @ParamConverter("post", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("newPost", converter="fos_rest.request_body")
     * @Security("is_granted('edit', $post)")
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref=@Model(type=Post::class, groups={"post_input"}))
     * ),
     */
    public function updateAction(Post $post, Post $newPost, ConstraintViolationListInterface $validationErrors, PostManager $manager)
    {
        $this->checkViolations($validationErrors);

        $manager->patch($post, $newPost);

        return $post;
    }

    /**
     * @Rest\View(StatusCode = 200)
     * @Rest\Post(
     *     path = "/{slug}/comments",
     *     name = "api_post_comment",
     *     requirements = {"slug"="[a-zA-Z0-9\-_\/]+"}
     * )
     * @ParamConverter("post", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("comment", converter="fos_rest.request_body")
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref=@Model(type=Comment::class, groups={"comment_input"}))
     * ),
     * @OA\Response(
     *     response=201,
     *     description="Returns the new Post",
     *     @OA\JsonContent(ref=@Model(type=Post::class, groups={"default"}))
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
     * @Security("is_granted('delete', $post)")
     */
    public function deleteAction(Post $post, PostManager $manager)
    {
        $manager->delete($post);

        return;
    }
}     