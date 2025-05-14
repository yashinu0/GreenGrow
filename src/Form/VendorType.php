<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VendorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_user', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('prenom_user', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('email_user', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('mot_de_passe_user', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['id' => 'password']
            ])
            ->add('adresse_user', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('ville_user', TextType::class, [
                'label' => 'Ville',
            ])
            ->add('code_postal_user', TextType::class, [
                'label' => 'Code Postal',
            ])
            ->add('telephone_user', TextType::class, [
                'label' => 'Téléphone',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
