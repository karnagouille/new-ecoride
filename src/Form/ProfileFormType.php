<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>false
            ])
            ->add('lastname',TextType::class,[
                'label'=>false
            ])
            ->add('email',TextType::class,[
                'label'=>false
            ])
            ->add('photo',FileType::class,[
                'mapped'=>false,
                'label'=>false,
                'constraints'=>[
                    new Image(),
                ]
            ])
            ->add('pseudo',TextType::class,[
                'label'=>false
            ])
            ->add('Envoyer', SubmitType::class
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
