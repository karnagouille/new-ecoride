<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class NewcarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('model', TextType::class,[
                'required'=> true,
            ])
            
            ->add('registration', TextType::class,[
                'required'=> true,
            ])
            ->add('energy', TextType::class,[
                'required'=> true,
            ])
            ->add('color', TextType::class,[

                'required'=> true,
            ])
            ->add('date_first_registration', TextType::class,[
                'required'=> true,
            ])
            ->add('preference', TextType::class,[
                'required'=> true,
            ])

            ->add('brand', TextType::class, [
                'required' => true,
                'label' => 'Marque',
                'required' => true,
                'mapped' => false,
            ])
            ->add('Envoyer', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
