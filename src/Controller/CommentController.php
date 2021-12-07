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

/**
 * @Route("/api/comments")
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
     * @Rest\View(serializerGroups={"default"})
     */
    public function listAction(ParamFetcherInterface $paramFetcher, CommentProvider $provider)
    {
        return $provider->getAllComments($paramFetcher->get('limit'), $paramFetcher->get('page'));
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
