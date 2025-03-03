<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'border border-gray-200 rounded p-2 w-full',
                    'placeholder' => 'Enter your email'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Email cannot be empty',
                    ])
                ],
            ])
            ->add('first_name', TextType::class, [
                'label' => 'First Name',
                'attr' => [
                    'class' => 'border border-gray-200 rounded p-2 w-full',
                    'placeholder' => 'Enter your first name'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'First name cannot be empty',
                    ])
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Last Name',
                'attr' => [
                    'class' => 'border border-gray-200 rounded p-2 w-full',
                    'placeholder' => 'Enter your last name'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Last name cannot be empty',
                    ])
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'I agree to the terms and conditions',
                'mapped' => false,
                'attr' => [
                    'class' => 'ml-2'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Password',
                'mapped' => false,
                'attr' => [
                    'class' => 'border border-gray-200 rounded p-2 w-full',
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Enter your password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
