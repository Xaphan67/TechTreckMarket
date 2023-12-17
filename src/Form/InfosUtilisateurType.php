<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
                    'Homme' => 'homme',
                    'Femme' => 'femme'
                ],
                'expanded' => true
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => false,
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ]
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
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
            'data_class' => Utilisateur::class,
        ]);
    }
}
