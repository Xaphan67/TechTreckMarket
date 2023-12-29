<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Email;

class InfosUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civilite', ChoiceType::class, [
                'attr' => [
                    'class' => 'formulaire-radio'
                ],
                'choices' => [
                    'Monsieur' => 'monsieur',
                    'Madame' => 'madame'
                ],
                'expanded' => true,
                'placeholder' => false,
                'required' => false
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => false
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse email.',
                    ]),
                    new Email([
                        'message' => 'Veuillez entrer une adresse email valide.',
                    ])
                ]
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => false
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'bouton-100'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
