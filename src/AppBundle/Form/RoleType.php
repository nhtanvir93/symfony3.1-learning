<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Role;

class RoleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, [
                'attr' => [
                    'class' => 'mb-2',
                    'placeholder' => 'Title'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Role title is required'
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => "Role title must contain at least {{ limit }} characters"
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'mb-2',
                    'rows' => 5,
                    'placeholder' => 'Description'
                ],
                'constraints' => [
                    new Assert\Length([
                        'max' => 500,
                        'maxMessage' => "Role description can not exceed {{ limit }} characters"
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Role::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_role';
    }
}
