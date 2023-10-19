<?php

namespace App\Form;

use App\Entity\Base;
use App\Entity\Resident;
use App\Entity\Room;
use App\EventListener\AddRoomSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResidentType extends AbstractType
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture')
            ->add('firstName')
            ->add('birthDate')
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

        ;

        $builder->addEventSubscriber(new AddRoomSubscriber($this->entityManager));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resident::class,
        ]);
    }
}
