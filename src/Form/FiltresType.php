<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class FiltresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('reference', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => "Désignation, modèle,..."
            ],
            'empty_data' => '',
            'required' => false
        ])
        ->add('disponibilite', CheckboxType::class, [
            'label'    => 'Uniquement les produits en stock',
            'required' => false
        ])
        ->add('marques', ChoiceType::class, [
            'label' => false,
            'expanded' => true,
            'multiple' =>true,
            'choices' => $options['marques'],
            // 'data' => array_keys($options['marques']) -> Coche toutes les marques par défaut
        ])
        ->add('prixMinimum', NumberType::class, [
            'label' => false,
            'html5' => true,
            'attr' => [
                'placeholder' => 'Minimum',
            ],
            'required' => false
        ])
        ->add('prixMaximum', NumberType::class, [
            'label' => false,
            'html5' => true,
            'attr' => [
                'placeholder' => 'Maximum',
            ],
            'required' => false
        ])
        ->add('tri', ChoiceType::class, [
            'label' => false,
            'placeholder' => 'Trier les produits',
            'choices' => [
                'Nom' => 'nom',
                'Marque' => 'marque',
                'Du - cher au + cher' => 'ASC',
                'Du + cher au - cher' => 'DESC'
            ],
            'required' => false
        ])
        ->add('Valider', SubmitType::class, [
            'attr' => [
                'class' => 'bouton-centre bouton-centre-margin bouton-150'
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'marques' => null
        ]);
    }
}
