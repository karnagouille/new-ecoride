<?php

namespace App\Form;


use App\Entity\Car;

use App\Entity\Carpooling;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CarpoolingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { $builder
            ->add('startTown', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Départ', 'class' => 'input'],
                'required'=>true,
            ])
            ->add('endTown', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Destination', 'class' => 'input'],
                'required'=>true,
            ])
            ->add('passenger', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    '1 passager' => 1,
                    '2 passagers' => 2,
                    '3 passagers' => 3,
                    '4 passagers' => 4,
                ],
                'attr' => ['class' => 'input'],
                'required'=>true,
            ])
            ->add('startAt', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'inputdate'],
                'required'=>true,
            ])

            ->add('hour', TimeType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Heure de départ', 'class' => 'order2']
            ])

            ->add('price', ChoiceType::class, [
                'label' => false,
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
                'choices' => [
                    'Croissant' => 'asc',
                    'Décroissant' => 'desc',
                ]
            ])
            ->add('traveltime', ChoiceType::class, [
                'label' => false,
                'mapped' => false,
                'choices' => [
                    '1–2 h' => '1-2',
                    '2–3 h' => '2-3',
                    '3–4 h' => '3-4',
                    '4–5 h' => '4-5',
                    '5–6 h' => '5-6',
                    '6–7 h' => '6-7',
                    '7–8 h' => '7-8',
                    '8–9 h' => '8-9',
                    '9–10 h' => '9-10',
                    '10–11 h' => '10-11',
                    '11–12 h' => '11-12',
                ],
                
                'attr' => ['class' => 'input']
            ])
            ->add('electric', ChoiceType::class, [
                'label' => false,
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ]
            ])
            ->add('note', ChoiceType::class, [
                'label' => false,
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
                'choices' => [
                    '5' => 5,
                    '4 ou +' => 4,
                    '3 ou +' => 3,
                    '2 ou +' => 2,
                    '1 ou +' => 1,
                ]
            ])

            ->add('car', EntityType::class, [
                'class' => Car::class,
                'choice_label' => 'model', 
                'placeholder' => 'Choisissez un véhicule',
            ]);
    }















    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carpooling::class,
        ]);
    }
}
