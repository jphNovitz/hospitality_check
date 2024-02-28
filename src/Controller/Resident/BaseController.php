<?php

namespace App\Controller\Resident;

use App\Entity\Resident;
use App\Form\Resident\BasicType;
use App\Form\ResidentType;
use App\Repository\ResidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/resident/{id}/base', name: 'app_resident_base_edit', methods: ['GET', 'POST'])]
final class BaseController extends AbstractController
{
    public function __invoke(Request $request, Resident $resident, EntityManagerInterface $entityManager): Response
    {

        if ((!in_array('ROLE_ADMIN', $this->getUser()->getRoles()) &&  $this->getUser()->getId() !== $resident->getReferent()->getId()))
        return $this->redirectToRoute('app_resident_show', [
            'id' => $resident->getId()], 301);

        $form = $this->createForm(BasicType::class, $resident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_resident_show', [
                'id' => $resident->getId()],
                Response::HTTP_SEE_OTHER);
        }

        return $this->render('resident/base/edit.html.twig', [
            'resident' => $resident,
            'form' => $form,
        ]);



//        return $this->render('resident/base/edit.html.twig', [
//            'resident' => $resident
//        ]);
    }
}
