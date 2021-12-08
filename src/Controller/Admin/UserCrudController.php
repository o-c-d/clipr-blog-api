<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\ Security;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class UserCrudController extends AbstractCrudController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

     /**
     * UserCrudController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder) 
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {

        $email = TextField::new('email');

        $password = TextField::new('plainPassword')
            ->setFormType(PasswordType::class)
            ->setFormTypeOption('empty_data', '')
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->setHelp('Leave blank to not edit.');

        $roles = ChoiceField::new('roles', "ROLES")
            ->setChoices([
                'Super Admin' => 'ROLE_SUPER_ADMIN',
                'Admin' => 'ROLE_ADMIN',
                'Writer' => 'ROLE_WRITER', 
                'User' => 'ROLE_USER', 
            ])
            ->allowMultipleChoices(true);

        switch ($pageName) {
            case Crud::PAGE_INDEX:
               return [
                    $email,
                    $roles,
                ];
                break;
            case Crud::PAGE_DETAIL:
                return [
                    $email,
                    $password,
                    $roles,
                ];
                break;
            case Crud::PAGE_NEW:
               return [
                    $email,
                    $password,
                    $roles,
                ];
                break;
            case Crud::PAGE_EDIT:
                return [
                    $email,
                    $password,
                    $roles,
                ];
                break;
        }
    }
    
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->encodePassword($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->encodePassword($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function encodePassword(User $user)
    {
        if ($user->getPlainPassword() !== null) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        }
    }
}
