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

#[Route('/resident/{id}/base', name: 'app_resident__base_edit', methods: ['GET'])]
final class ResidentBaseController extends AbstractController
{
    public function __invoke(Request $request, Resident $resident): Response
    {
        return $this->render('resident/base/edit.html.twig', [
            'resident' => $resident
        ]);
    }
}
