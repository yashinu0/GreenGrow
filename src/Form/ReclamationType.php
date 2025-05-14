<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('description_rec', ChoiceType::class, [
            'choices' => [
                'Produit défectueux' => 'Produit défectueux',
                'Livraison retardée' => 'Livraison retardée',
                'Produit ne correspond pas à la description' => 'Produit ne correspond pas à la description'
            ],
            'required' => true,
            'placeholder' => 'Choisissez un type de réclamation',
            'empty_data' => null,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez choisir un type de réclamation'
                ])
            ]
        ])
        ->add('message_reclamation', TextareaType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez décrire votre problème'
                ]),
                new Length([
                    'min' => 10,
                    'max' => 1000,
                    'minMessage' => 'Votre message doit faire au moins {{ limit }} caractères',
                    'maxMessage' => 'Votre message ne peut pas dépasser {{ limit }} caractères'
                ])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}