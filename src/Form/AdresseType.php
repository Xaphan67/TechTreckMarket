<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ]
            ])
            ->add('typeRue', TextType::class, [
                'label' => "Type de rue",
                'attr' => [
                    'class' => 'formulaire-texte'
                ]
            ])
            ->add('rue', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ]
            ])
            ->add('codePostal', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ]
            ])
            ->add('ville', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ]
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'bouton-100'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
