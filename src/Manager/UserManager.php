<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class UserManager extends AbstractManager
{

    private EntityManagerInterface $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder )
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getPagerListAll($limit=10, $page=1): Pagerfanta
    {
        $query = $this->em->getRepository(User::class)->getQueryForUsersList();
        return $this->getPager($query, $limit, $page);
    }

    public function create(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function patch(User $user, User $newUser): void
    {
        // email is not editable by design
        // password is not editable by design
        $user->setRoles($newUser->getRoles());

        $this->em->flush();
    }

    public function delete(User $user): void
    {
        $this->em->remove($user);
    }


    public function makeUser(string $email, string $password, ?array $roles=[])
    {
        if(empty($roles)) {
            $roles = ['ROLE_USER'];
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $user->setRoles($roles);
        $this->em->persist($user);
        $this->em->flush();
    }
}