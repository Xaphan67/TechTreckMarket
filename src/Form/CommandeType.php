<?php

namespace App\Form;

use App\Entity\Utilisateur;
use App\Entity\AdresseLivraison;
use App\Entity\AdresseFacturation;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\AdresseLivraisonRepository;
use App\Repository\AdresseFacturationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CommandeType extends AbstractType
{
    private AdresseFacturationRepository $adresseFacturationRepository;
    private AdresseLivraisonRepository $adresseLivraisonRepository;

    public function __construct(AdresseFacturationRepository $adresseFacturationRepository, AdresseLivraisonRepository $adresseLivraisonRepository, private Security $security)
    {
        $this->adresseFacturationRepository = $adresseFacturationRepository;
        $this->adresseLivraisonRepository = $adresseLivraisonRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $utilisateur = $this->security->getUser();
        if (!$utilisateur) {
            throw new \LogicException(
                'Un utilisateur doit être connecté pour utiliser ce formulaire'
            );
        }

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
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir une civilité.',
                    ])
                ]
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un nom.',
                    ])
                ]
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un prénom.',
                    ])
                ]
            ])
            ->add('Commander', SubmitType::class, [
                'attr' => [
                    'class' => 'bouton-valider-commande bouton-100'
                ]
            ])
        ;

        $adressesFacturation = $this->adresseFacturationRepository->findAllOrdered($utilisateur, "preferee");
        $builder
            ->add('adresseFacturation', EntityType::class, [
                'mapped' => false,
                'label' => false,
                'class' => AdresseFacturation::class,
                'choices' => $adressesFacturation
            ])
        ;

        $builder
            ->add('numeroFacturation', TextType::class, [
                'mapped' => false,
                'label' => "Numéro",
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => count($adressesFacturation) == 0,
            ])
            ->add('typeRueFacturation', TextType::class, [
                'mapped' => false,
                'label' => "Type de rue",
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => count($adressesFacturation) == 0,
            ])
            ->add('rueFacturation', TextType::class, [
                'mapped' => false,
                'label' => "Rue",
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => count($adressesFacturation) == 0,
            ])
            ->add('codePostalFacturation', TextType::class, [
                'mapped' => false,
                'label' => "Code postal",
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => count($adressesFacturation) == 0,
            ])
            ->add('villeFacturation', TextType::class, [
                'mapped' => false,
                'label' => "Ville",
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => count($adressesFacturation) == 0,
            ])
            ->add('enregistrerFacturation', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Enregistrer dans mon carnet d\'adresses',
                'row_attr' => ['class' => 'formulaire-champ-horizontal'],
                'required' => false,
            ])
        ;

        $adressesLivraison = $this->adresseLivraisonRepository->findAllOrdered($utilisateur, "preferee");
        $builder
            ->add('adresseLivraison', EntityType::class, [
                'mapped' => false,
                'label' => false,
                'class' => AdresseLivraison::class,
                'choices' => $adressesLivraison
            ]);

        $builder
            ->add('numeroLivraison', TextType::class, [
                'mapped' => false,
                'label' => "Numéro",
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => count($adressesLivraison) == 0,
            ])
            ->add('typeRueLivraison', TextType::class, [
                'mapped' => false,
                'label' => "Type de rue",
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => count($adressesLivraison) == 0,
            ])
            ->add('rueLivraison', TextType::class, [
                'mapped' => false,
                'label' => "Rue",
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => count($adressesLivraison) == 0,
            ])
            ->add('codePostalLivraison', TextType::class, [
                'mapped' => false,
                'label' => "Code postal",
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => count($adressesLivraison) == 0,
            ])
            ->add('villeLivraison', TextType::class, [
                'mapped' => false,
                'label' => "Ville",
                'attr' => [
                    'class' => 'formulaire-texte'
                ],
                'required' => count($adressesLivraison) == 0,
            ])
            ->add('enregistrerLivraison', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Enregistrer dans mon carnet d\'adresses',
                'row_attr' => ['class' => 'formulaire-champ-horizontal'],
                'required' => false,
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
