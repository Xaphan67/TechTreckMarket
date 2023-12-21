<?php

namespace App\Form;

use App\Entity\Utilisateur;
use App\Entity\AdresseLivraison;
use App\Entity\AdresseFacturation;
use Symfony\Component\Form\AbstractType;
use App\Repository\AdresseLivraisonRepository;
use App\Repository\AdresseFacturationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommandeType extends AbstractType
{
    private AdresseFacturationRepository $adresseFacturationRepository;
    private AdresseLivraisonRepository $adresseLivraisonRepository;

    public function __construct(AdresseFacturationRepository $adresseFacturationRepository, AdresseLivraisonRepository $adresseLivraisonRepository)
    {
        $this->adresseFacturationRepository = $adresseFacturationRepository;
        $this->adresseLivraisonRepository = $adresseLivraisonRepository;
    }

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
                'expanded' => true,
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
            ])
            ->add('adresseFacturation', EntityType::class, [
                'mapped' => false,
                'class' => AdresseFacturation::class,
                'choices' => $this->adresseFacturationRepository->findAllOrdered("preferee"),
            ])
            ->add('adresseLivraison', EntityType::class, [
                'mapped' => false,
                'class' => AdresseLivraison::class,
                'choices' => $this->adresseLivraisonRepository->findAllOrdered("preferee"),
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
