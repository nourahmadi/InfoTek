<?php

namespace App\Controller\Admin;

use App\Entity\Transporteurs;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TransporteursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Transporteurs::class;
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
