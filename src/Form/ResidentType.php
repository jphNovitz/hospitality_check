<?php

namespace App\Form;

use App\Entity\Resident;
use App\Entity\Room;
use App\EventListener\AddRoomSubscriber;
use App\EventSubscriber\ResidentSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ResidentType extends AbstractType
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface                 $entityManager,
        private readonly TokenStorageInterface $tokenStorage
    )
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'translation_domain' => 'messages',
                'required' => false,
                'allow_delete' => true
            ])
            ->add('firstName', TextType::class, [
                'translation_domain' => 'messages'
            ])
            ->add('birthDate', BirthdayType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'choice',
                'years' => range(date('Y') - 65, date('Y'))
            ])
            ->add('nationality', TextType::class, [
                'translation_domain' => 'messages',
            ])
            ->add('room', EntityType::class, [
                'translation_domain' => 'messages',
                'class' => Room::class,
                'required' => false,
                'empty_data' => '',
            ])
            ->add('newRoom', TextType::class, [
                'translation_domain' => 'messages',
                'mapped' => false,
                'required' => false,
            ])
            ->add('referent');


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
