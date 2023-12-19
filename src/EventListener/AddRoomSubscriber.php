<?php

namespace App\EventListener;

use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddRoomSubscriber implements EventSubscriberInterface
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents(): array
    {
// Tells the dispatcher that you want to listen on the form.pre_set_data
// event and that the preSetData method should be called.
        return [FormEvents::PRE_SUBMIT => 'preSubmit'];
//return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    public function preSubmit(FormEvent $event): void
    {

        $resident = $event->getData();
        $form = $event->getForm();
//        dd($resident);
//        dd(empty($resident['newRoom']));

        if (!empty($resident['newRoom'])) {
            if (!$this->entityManager->getRepository(Room::class)->findOneBy(['id' => $resident['newRoom']])) {
                $room = new Room();
                $room->setNumber($resident['newRoom']);
                $this->entityManager->persist($room);
                $this->entityManager->flush();
                $id = $room->getId();
                $resident['room'] = $id;
            }
            else $resident['room'] = $resident['newRoom'];
        }


        $event->setData($resident);

    }
}