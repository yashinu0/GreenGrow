<?php

namespace App\Form;

use App\Entity\Feed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class FeedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_feed', TextType::class, [
                'label' => 'Votre nom',
                'attr' => ['placeholder' => 'Entrez votre nom']
            ])
            ->add('email_feed', EmailType::class, [
                'label' => 'Votre email',
                'attr' => ['placeholder' => 'Entrez votre email']
            ])
            ->add('subject_feed', TextType::class, [
                'label' => 'Sujet',
                'attr' => ['placeholder' => 'Entrez le sujet de votre message']
            ])
            ->add('commentaire_feed', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => ['placeholder' => 'Entrez votre message', 'rows' => 5]
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'feed',
                'locale' => 'fr',
            ]
        );
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feed::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'feed_form', 
        ]);
    }
}