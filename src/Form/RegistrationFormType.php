<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('prenom', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minLength' => 2,
                'maxLength' => 180,
                'placeholder' => 'Votre prénom'
            ],
            'label' => 'Prénom :',
            'label_attr' => [
                'class' => 'form-label',
            ],
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Veuillez entrez un prénom',
                ]),
                new Assert\Length([
                    'min' => 2,
                    'max' => 180,
                ]),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z0-9]*$/',
                    'message' => 'Le pseudo ne peut contenir que des caractères alphanumériques.'
                ]),
            ],
        ])
        ->add('nom', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minLength' => 2,
                'maxLength' => 180,
                'placeholder' => 'Votre nom'
            ],
            'label' => 'Nom :',
            'label_attr' => [
                'class' => 'form-label',
            ],
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Veuillez entrez un nom',
                ]),
                new Assert\Length([
                    'min' => 2,
                    'max' => 180,
                ]),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z0-9]*$/',
                    'message' => 'Le pseudo ne peut contenir que des caractères alphanumériques.'
                ]),
            ],
        ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => 2,
                    'maxLength' => 180,
                    'placeholder' => 'Votre E-mail'
                ],
                'label' => 'Adresse E-mail :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrez une adresse email',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 180,
                    ]),
                    new Assert\Email([
                        'message' => 'L\'adresse email n\'est pas valide ',
                    ]),
                ],
            ])

            ->add('plainPassword', RepeatedType::class, [
                // Au lieu de mettre la donnée directement dans l'entité
                // On la lit et on l'encode ensuite dans le controller
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Votre mot de passe'
                    ],
                    'label' => 'Mot de passe :',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Veuillez entrez un mot de passe',
                        ]),
                        new Assert\Length([
                            'min' => 8,
                            'minMessage' => 'Votre mot de passe doit comprendre au moins {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                        new Assert\Regex([
                            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{10,}$/',
                            'message' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre.'
                        ]),
                    ],
                    'mapped' => false,
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control bg-white-secondary rounded-button border-none',
                        'placeholder' => 'Confirmer le mot de passe'
                    ],
                    'label' => 'Confirmer le mot de passe :',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Veuillez entrez votre mot de passe',
                        ]),
                        new Assert\Length([
                            'min' => 8,
                            'minMessage' => 'Votre mot de passe doit comprendre au moins {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                        new Assert\Regex([
                            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{10,}$/',
                            'message' => 'Le mot de passe doit comporter au moins 10 caractères dont une majuscule, une minuscule, un caractère spécial et un chiffre.'
                        ]),
                    ],
                    'mapped' => false,
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input ms-2 mt-1 border-none'
                ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
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
