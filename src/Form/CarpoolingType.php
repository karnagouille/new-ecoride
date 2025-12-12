<?php

namespace App\Form;


use App\Entity\Car;

use App\Entity\Credit;
use App\Entity\Carpooling;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
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
                'required'=>false,
            ])
            ->add('startAt', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'inputdate'],
                'required'=>false,
            ])

            ->add('hour', TimeType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Heure de départ', 'class' => 'order2'],
                'required'=>false,
            ])

            ->add('price',MoneyType::class,[
                'required' => true,
                'currency' => 'EUR',
                'mapped' => false,
            ])

            
            ->add('traveltime', ChoiceType::class, [
                'label' => false,
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
                'required'=>false,                
                'attr' => ['class' => 'input']
            ])
            ->add('electric', ChoiceType::class, [
                'label' => false,
                'required'=>false,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                
            ])

            ->add('car', EntityType::class, [
                'class' => Car::class,
                'choices' => $options['user_cars'], // options filtrées par user
                'choice_label' => fn(Car $car) => $car->getModel(), 
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carpooling::class,
            'user_cars' => [],
        ]);
    }
}
