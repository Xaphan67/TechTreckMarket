<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte formulaire-texte-titre'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un titre.',
                    ])
                ]
            ])
            ->add('texte', TextareaType::class, [
                'label' => 'Votre commentaire',
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un message.',
                    ])
                ]
            ])
            ->add('Valider', SubmitType::class, [
                'label' => 'Poster',
                'attr' => [
                    'class' => 'bouton-droite bouton-100'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
