<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statutRec', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'Pending',
                    'En cours' => 'In Progress',
                    'Résolue' => 'Resolved',
                    'Fermée' => 'closed'
                ],
                'label' => 'Statut',
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
