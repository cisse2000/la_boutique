<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('firstName','Prénom'),
            TextField::new('lastName','Nom'),
            EmailField::new('email','Email'),
            ChoiceField::new('roles','Rôles')->setChoices([
                'Choisissez un rôle' => 'empty',
                'Administrateur'  => 'ROLE_ADMIN',
                'Modérateur'  => 'ROLE_MODERATOR',
                'Utilisateur'  => 'ROLE_USER'
            ])
            ->allowMultipleChoices(),
            TextField::new('password', 'Mot de pass')
        ];
    }
    
}
