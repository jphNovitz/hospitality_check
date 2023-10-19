<?php

namespace App\Controller\Resident;

use App\Entity\Resident;
use App\Form\ResidentBaseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasePreferenceController extends AbstractController
{
    #[Route('toto/{id}', name: 'resident_base_preference', methods: ['GET', 'POST'])]
    public function edit(Request $request, Resident $resident, EntityManagerInterface $entityManager): Response
    {
//        dd($resident);
        $form = $this->createForm(ResidentBaseType::class, $resident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData()->getBasePrefs() as $pref){
                $resident->addBasePref($pref);
            }
            $entityManager->persist($resident);
            $entityManager->flush();
dd($resident);
            return $this->redirectToRoute('app_resident', ['id' => $resident->getId()], Response::HTTP_SEE_OTHER);
//            return $this->redirectToRoute('app_resident_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('resident/base_preferences/_form.html.twig', [
            'form' => $form
        ]);
    }
}
