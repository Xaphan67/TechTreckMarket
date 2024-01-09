<?php

namespace App\Controller\Admin;

use App\Entity\CaracteristiqueTechnique;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CaracteristiqueTechniqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CaracteristiqueTechnique::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnIndex()->hideOnForm(),
            TextField::new('nom'),
        ];
    }
}
