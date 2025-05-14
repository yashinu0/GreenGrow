<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 5,
                            'minMessage' => 'Your password should be at least {{ limit }} characters long',
                        ]),
                        new NotCompromisedPassword(),
                        new Callback([$this, 'validatePasswordStrength']),
                    ],
                    'label' => 'New password',
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                ],
                'invalid_message' => 'The password fields must match.',
                'mapped' => false,
            ])
        ;
    }

    public function validatePasswordStrength($password, ExecutionContextInterface $context)
    {
        // Optionnel : vérifiez la force du mot de passe, si nécessaire
        if (strlen($password) < 8) { // Par exemple, une vérification simple
            // Ajoutez une erreur si le mot de passe est trop faible
            $context->buildViolation('The password strength is too low. Please use a stronger password.')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
