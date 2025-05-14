<?php

namespace App\Form;

use App\Entity\Livreur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Livreur1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_livreur')
            ->add('prenom_livreur')
            ->add('numero_livreur')
            ->add('addresse_livreur')
            ->add('photo_livreur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livreur::class,
        ]);
    }
}
