<?php

namespace App\Controller\Resident;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
    #[Route('/room', name: 'app_room')]
    public function __invoke(): Response
    {
        return $this->render('room/room.html.twig');
    }

}
