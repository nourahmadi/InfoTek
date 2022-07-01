<?php

namespace App\Controller\Admin;

use App\Entity\User1;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class User1CrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User1::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
