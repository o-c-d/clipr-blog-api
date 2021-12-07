<?php

namespace App\Controller;

use App\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractRestController
{
    /**
     * @Rest\Post(path="/login/token", name="api_login_token")
     * @Rest\QueryParam(name="login", description="User email.")
     * @Rest\QueryParam(name="password", description="User password.")
     * @OA\Parameter(
     *     name="login",
     *     in="query",
     *     description="User email",
     *     @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *     name="password",
     *     in="query",
     *     description="User password",
     *     @OA\Schema(type="string")
     * ), 
     */
    public function login(?User $user): Response
    {
        return $this->json([]);
    }
}
