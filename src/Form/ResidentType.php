<?php

namespace App\Form;

use App\Entity\Base;
use App\Entity\Resident;
use App\Entity\Room;
use App\EventListener\AddRoomSubscriber;
use App\EventListener\ManageReferentSubscriber;
use App\EventSubscriber\ResidentSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ResidentType extends AbstractType
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        private TokenStorageInterface $tokenStorage
    )
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true
            ])
            ->add('firstName')
            ->add('birthDate', BirthdayType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'choice',
                'years' => range(date('Y') -80, date('Y'))
                ])
            ->add('nationality')
            ->add('room', EntityType::class, [
                'class'=> Room::class,
                'required'   => false,
                'empty_data' => '',
            ])
            ->add('newRoom', TextType::class, [
                'mapped' => false,
                'required'   => false,
            ])
            ->add('referent')
            ->add('bases', EntityType::class, [
                'class' => Base::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'by_reference' => false,
            ])
//            ->add('interests', CollectionType::class, [
//                'entry_type' => InterestType::class,
//                'entry_options' => [
//                    'label' => false,
//                ],
//                'allow_add' => true,
//                'allow_delete' => true,
//                'prototype' => true,
//                'by_reference' => false,
//
//            ])
            ->add('characteristics', CollectionType::class, [
                'entry_type' => CharacteristicType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,


            ])

        ;


        $builder->addEventSubscriber(new AddRoomSubscriber($this->entityManager));
        $builder->addEventSubscriber(new ResidentSubscriber($this->tokenStorage));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resident::class,
        ]);
    }
}
