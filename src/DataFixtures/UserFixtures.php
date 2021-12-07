<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    // ...
    public function load(ObjectManager $manager)
    {
        $this->userManager->makeUser('super@test.dev', 'super', ['ROLE_SUPER_ADMIN']);
        $this->userManager->makeUser('admin@test.dev', 'admin', ['ROLE_ADMIN']);
        $this->userManager->makeUser('writer@test.dev', 'writer', ['ROLE_WRITER']);
        $this->userManager->makeUser('user@test.dev', 'user', ['ROLE_USER']);
    }
}
