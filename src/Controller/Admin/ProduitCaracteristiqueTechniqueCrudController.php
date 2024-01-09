<?php

namespace App\Controller\Admin;

use App\Entity\CaracteristiqueTechnique;
use App\Entity\ProduitCaracteristiqueTechnique;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitCaracteristiqueTechniqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProduitCaracteristiqueTechnique::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnIndex()->hideOnForm(),
            AssociationField::new('produit')->setRequired(true),
            AssociationField::new('caracteristiqueTechnique')->setRequired(true),
            TextField::new('valeur')
        ];
    }
}
