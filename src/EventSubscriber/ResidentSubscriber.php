<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ResidentSubscriber implements EventSubscriberInterface
{

    public function __construct(private TokenStorageInterface $tokenStorage)
    {
    }

    public function preSetData($event): void
    {
        if (!in_array('ROLE_ADMIN', $this->tokenStorage->getToken()->getRoleNames())){

            $form = $event->getForm();
            $user = $this->tokenStorage->getToken()->getUser();

            if ($form->has('referent')) {
                $form->remove('referent');
                $form->add('referent', EntityType::class, [
                    'class' => User::class,
                    'query_builder' => function (EntityRepository $er) use ($user) {
                        return $er->createQueryBuilder('u')
                            ->where('u.id = :userId')
                            ->setParameter('userId', $user->getId());
                    },
                    'disabled' => true,
                    'row_attr' => ['class' => 'disabled: opacity:70'],
//                    'data' => $user

                    ]);
            }
//            dd($event->getForm());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData'
        ];
    }
}
