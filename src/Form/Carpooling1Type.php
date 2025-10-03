<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Carpooling;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Carpooling1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startTown')
            ->add('endTown')
            ->add('startAt')
            ->add('passenger')
            ->add('hour')
            ->add('price')
            ->add('traveltime')
            ->add('electric')
            ->add('note')
            ->add('statut')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('car', EntityType::class, [
                'class' => Car::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carpooling::class,
        ]);
    }
}
