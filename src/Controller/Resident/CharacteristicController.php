<?php

namespace App\Controller\Resident;

use App\Entity\Resident;
use App\Form\ResidentType;
use App\Repository\ResidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/resident/{id}/characteristic', name: 'app_resident_characteristic_edit', methods: ['GET'])]
final class CharacteristicController extends AbstractController
{
    public function __invoke(Resident $resident): Response
    {
        if ((!in_array('ROLE_ADMIN', $this->getUser()->getRoles()) &&  $this->getUser()->getId() !== $resident->getReferent()->getId()))
            return $this->redirectToRoute('app_resident_show', [
                'id' => $resident->getId()], 301);

        return $this->render('resident/characteristic/edit.html.twig', [
            'resident' => $resident
        ]);
    }
}
