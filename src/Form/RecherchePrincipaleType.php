<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecherchePrincipaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recherche', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'recherchePrincipale',
                    'placeholder' => "Rechercher un produit, une marque..."
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer au moins un terme de recherche.',
                    ]),
                ]
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'bouton-recherche-principale'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
