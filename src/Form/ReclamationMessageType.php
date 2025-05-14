<?php

namespace App\Form;

use App\Entity\ReclamationMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('content', TextareaType::class, [
            'label' => 'Votre message',
            'attr' => ['rows' => 3, 'placeholder' => 'Tapez votre message ici...']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReclamationMessage::class,
        ]);
    }
}