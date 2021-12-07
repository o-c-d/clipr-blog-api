<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use App\Provider\UserProvider;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/api/users")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class UserController extends AbstractRestController
{
    /**
     * @Rest\Get(path="/", name="api_user_list")
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="Max number of users per page."
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="The current page"
     * )
     * @Rest\View(serializerGroups={"default"})
     */
    public function listAction(ParamFetcherInterface $paramFetcher, UserProvider $provider)
    {
        return $provider->getAllUsers($paramFetcher->get('limit'), $paramFetcher->get('page'));
    }

    /**
     * @Rest\Get(
     *     path = "/{id}",
     *     name = "api_user_show",
     *     requirements = {"id"="\d+"}
     * )
     * @ParamConverter("user", options={"mapping": {"id": "id"}})
     * @Rest\View(serializerGroups={"default"})
     */
    public function showAction(User $user)
    {
        return $user;
    }

    /**
     * @Rest\Post(path="/", name="api_user_create")
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function createAction(User $user, UserManager $manager, ConstraintViolationListInterface $validationErrors)
    {
        $this->checkViolations($validationErrors);

        $manager->create($user);

        return $user;
    }

    /**
     * @Rest\View(StatusCode = 200)
     * @Rest\Put(
     *     path = "/{id}",
     *     name = "api_user_update",
     *     requirements = {"id"="\d+"}
     * )
     * @ParamConverter("user", options={"mapping": {"id": "id"}})
     * @ParamConverter("newUser", converter="fos_rest.request_body")
     */
    public function updateAction(User $user, User $newUser, ConstraintViolationListInterface $validationErrors, UserManager $manager)
    {
        $this->denyAccessUnlessGranted('edit', $user);

        $this->checkViolations($validationErrors);

        $manager->patch($user, $newUser);

        return $user;
    }

    /**
     * @Rest\View(StatusCode = 204)
     * @Rest\Delete(
     *     path = "/{id}",
     *     name = "api_user_delete",
     *     requirements = {"id"="\d+"}
     * )
     * @ParamConverter("user", options={"mapping": {"id": "id"}})
     */
    public function deleteAction(User $user, UserManager $manager)
    {
        $this->denyAccessUnlessGranted('delete', $user);
        
        $manager->delete($user);

        return;
    }

}
