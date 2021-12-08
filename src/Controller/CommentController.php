<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Provider\CommentProvider;
use App\Manager\CommentManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route("/api/comments")
 * @OA\Tag(name="Comment")
 */
class CommentController extends AbstractRestController
{
    /**
     * @Rest\Get(path="/", name="api_comment_list")
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="Max number of comments per page."
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="The current page"
     * )
     * @Rest\QueryParam(
     *     name="post",
     *     nullable=true,
     *     requirements="[a-zA-Z0-9\-_\/]+",
     *     description="Filter on this post slug"
     * )
     * @Rest\QueryParam(
     *     name="user",
     *     nullable=true,
     *     requirements="\d+",
     *     description="Filter on this user id"
     * )
     * @Rest\View(serializerGroups={"default"})
     */
    public function listAction(ParamFetcherInterface $paramFetcher, CommentProvider $provider)
    {
        $filters = [];
        if($paramFetcher->get('post')) {
            $filters['post'] = $paramFetcher->get('post');
        }
        if($paramFetcher->get('user')) {
            $filters['user'] = $paramFetcher->get('user');
        }
        return $provider->getAllComments($paramFetcher->get('limit'), $paramFetcher->get('page'), $filters);
    }

    /**
     * @Rest\Get(
     *     path = "/{id}",
     *     name = "api_comment_show",
     *     requirements = {"id"="\d+"}
     * )
     * @ParamConverter("comment", options={"mapping": {"id": "id"}})
     * @Rest\View(serializerGroups={"default"})
     */
    public function showAction(Comment $comment)
    {
        return $comment;
    }

}
