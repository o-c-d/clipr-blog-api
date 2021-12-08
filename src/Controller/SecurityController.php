<?php

namespace App\Controller;

use App\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @Route("/api")
 * @OA\Tag(name="Security")
 */
class SecurityController extends AbstractRestController
{
    /**
     * @Rest\Post(path="/login/token", name="api_login_token")
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *           type="object",
     *           @OA\Property(property="username", type="string"),
     *           @OA\Property(property="password", type="string")
     *     )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Returns the token authentication",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="token", type="string")
     *     )
     * )
     */
    public function login(?User $user): Response
    {
        return $this->json([]);
    }
}
