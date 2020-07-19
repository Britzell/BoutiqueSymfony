<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', TextType::class, [
                'label' => 'Adresse :',
                'attr' => [
                    'placeholder' => '42 rue Test',
                    'value' => '42 rue Test'
                ]
            ])
            ->add('addressComplement', TextType::class, [
                'label' => 'Complement d\'adresse :',
                'attr' => [
                    'placeholder' => 'Appartement 25',
                ],
                'required' => false
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal :',
                'attr' => [
                    'placeholder' => '59000',
                    'value' => '59000'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville :',
                'attr' => [
                    'placeholder' => 'Lille',
                    'value' => 'Lille'
                ]
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays :',
                'attr' => [
                    'placeholder' => 'France',
                    'value' => 'France'
                ]
            ])
            ->add('Ajouter', SubmitType::class, [
                'row_attr' => [
                    'class' => 'text-center'
                ],
                'attr' => [
                    'class' => 'btn-dark'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
