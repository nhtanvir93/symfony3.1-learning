<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class, [
                'attr' => [
                    'class' => 'mb-2',
                    'placeholder' => 'Username'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Username is required'
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => "Username must contain at least {{ limit }} characters",
                        'max' => 100,
                        'maxMessage' => "Username can not exceed {{ limit }} characters"
                    ])
                ]
            ])
            ->add('email',EmailType::class, [
                'attr' => [
                    'class' => 'mb-2',
                    'placeholder' => 'Email'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Email is required'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/  ',
                        'match' => true,
                        'message' => "Email is invalid"
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => "Email can not exceed {{ limit }} characters"
                    ])
                ]
            ])
            ->add('password',RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match',
                'options' => [
                    'attr' => [
                        'class' => 'mb-2',
                        'placeholder' => 'Email'
                    ]
                ],
                'first_options'  => [
                    'label' => 'Password',
                    'attr' => [
                        'class' => 'mb-2',
                        'placeholder' => 'Password'
                    ],
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Password is required'
                        ]),
                        new Assert\Length([
                            'min' => 4,
                            'minMessage' => "Password must contain at least {{ limit }} characters",
                            'max' => 100,
                            'maxMessage' => "Password can not exceed {{ limit }} characters"
                        ])
                    ]
                ],
                'second_options' => [
                    'label' => 'Retype Password',
                    'attr' => [
                        'class' => 'mb-2',
                        'placeholder' => 'Retype Password'
                    ],
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Retype Password is required'
                        ]),
                        new Assert\Length([
                            'min' => 4,
                            'minMessage' => "Retype Password must contain at least {{ limit }} characters",
                            'max' => 100,
                            'maxMessage' => "Retype Password can not exceed {{ limit }} characters"
                        ])
                    ]
                ]
            ])
            ->add('mobile',TextType::class, [
                'attr' => [
                    'class' => 'mb-2',
                    'placeholder' => 'Mobile'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Mobile number is required'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/\d/',
                        'match' => true,
                        'message' => "Mobile number must contain digits only"
                    ]),
                    new Assert\Length([
                        'min' => 11,
                        'max' => 11,
                        'exactMessage' => "Mobile number must have exactly 11 digits"
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
//            ->add('role');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
