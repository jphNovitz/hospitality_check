<?php

namespace App\Form;

use App\Entity\Resident;
use App\Form\EventListener\AddRoomSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResidentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture')
            ->add('firstName')
            ->add('birthDate')
            ->add('nationality')
            ->add('room')
            ->add('newRoom', TextType::class, ['mapped' => false])
            ->add('referent');

        $builder->addEventSubscriber(new AddRoomSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resident::class,
        ]);
    }
}
