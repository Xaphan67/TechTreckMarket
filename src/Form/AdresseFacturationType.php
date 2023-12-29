<?php

namespace App\Form;

use App\Entity\AdresseFacturation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AdresseFacturationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un numéro.',
                    ])
                ]
            ])
            ->add('typeRue', TextType::class, [
                'label' => "Type de rue",
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un type de rue.',
                    ])
                ]
            ])
            ->add('rue', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une rue.',
                    ])
                ]
            ])
            ->add('codePostal', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un code postal.',
                    ])
                ]
            ])
            ->add('ville', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une ville.',
                    ])
                ]
            ])
            ->add('preferee', CheckboxType::class, [
                'label' => 'Définir comme adresse préférée',
                'row_attr' => ['class' => 'formulaire-champ-horizontal'],
                'required' => false,
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
        $resolver->setDefaults([
            'data_class' => AdresseFacturation::class,
        ]);
    }
}
