<?php

namespace App\Provider;

use App\Manager\UserManager;
use App\Representation\PaginatedCollectionRepresentation;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserProvider
{
    private UserManager $manager;
    private UrlGeneratorInterface $router;

    public function __construct(UserManager $manager, UrlGeneratorInterface $router)
    {
        $this->manager = $manager;
        $this->router = $router;
    }

    public function getAllUsers(?int $limit=10, ?int $page=1): array
    {
        $pager = $this->manager->getPagerListAll($limit, $page);
        $pcr = new PaginatedCollectionRepresentation($pager, $this->router, 'api_user_list');
        return $pcr->represent();
    }


}
