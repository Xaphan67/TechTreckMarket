<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Form\JsonCodeEditorType;
use App\Repository\CategorieRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnIndex()->hideOnForm(),
            AssociationField::new('marque')->setRequired(true),
            AssociationField::new('categoriePrincipale', 'Catégorie Principale')
            ->setRequired(true)
            ->setFormTypeOptions([
                'query_builder' => function (CategorieRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id <= 3');
                },
            ])
            ->hideOnIndex(),
            AssociationField::new('categorie', 'Catégorie')
            ->setRequired(true)
            ->setFormTypeOptions([
                'query_builder' => function (CategorieRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id > 3');
                },
            ]),
            DateField::new('dateLancement', 'Date de lancement')->hideOnForm(),
            TextField::new('designation', 'Désignation'),
            TextField::new('resume', 'Résumé')->hideOnIndex(),
            TextEditorField::new('descriptif', 'Déscriptif')->hideOnIndex(),
            CodeEditorField::new('caracteristiquesTechniques')->setFormType(JsonCodeEditorType::class)->hideOnIndex(),
            ImageField::new('photo')
            ->setBasePath('img/produits/')
            ->setUploadDir('public/img/produits')
            ->setRequired($pageName !== Crud::PAGE_EDIT),
            MoneyField::new('prix')->setCurrency('EUR')->setStoredAsCents(false),
            IntegerField::new('stock')
        ];
    }
}
