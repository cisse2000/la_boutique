<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                
            ])
            ->add('firstname',TextType::class, [
                
            ])
            ->add('lastname',TextType::class, [
                
            ])
            ->add('company',TextType::class, [
                
            ])
            ->add('address', TextType::class,[

            ])
            ->add('postal', TextType::class, [

            ])
            ->add('city',TextType::class, [

            ])
            ->add('country',CountryType::class, [

            ])
            ->add('submit',SubmitType::class, [
                'label' => 'Ajouter'
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
