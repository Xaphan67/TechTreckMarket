<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite', NumberType::class, [
                'label' => false,
                'html5' => true,
                'attr' => [
                    'class' => 'input-quantite',
                    'placeholder' => 1,
                ],
                'empty_data' => 1,
                'required' => false
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'bouton-achat-large bouton-100'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}