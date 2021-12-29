<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangePasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'disabled' => true
            ])
            ->add('firstName',TextType::class,[
                'disabled' => true,
                'label' => 'Prénom'
            ])
            ->add('lastName',TextType::class,[
                'disabled' => true,
                'label' => 'Nom'
            ])
            ->add('password', PasswordType::class,[
                'label' => 'Ancien mot de pass'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'New password',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'Repeat Password',
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('submit',SubmitType::class,[
                 'label' => 'Changer mon mot de passe',
                'attr' => [
                    'class' => 'btn btn-info'
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
