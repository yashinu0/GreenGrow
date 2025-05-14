<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Livreur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Commande1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statue_commande')
            ->add('date_commande', null, [
                'widget' => 'single_text'
            ])
            ->add('prixtotal_commande')
            ->add('modepaiement_commande')
            ->add('livreur_commande', EntityType::class, [
                'class' => Livreur::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
