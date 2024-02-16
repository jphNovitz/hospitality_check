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

#[Route('/resident')]
class ResidentController extends AbstractController
{

    public function __construct(protected ResidentRepository $residentRepository){

    }
    #[Route('/', name: 'app_resident_index', methods: ['GET'])]
    public function index(ResidentRepository $residentRepository): Response
    {
        return $this->render('resident/index.html.twig', [
            'residents' => $residentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_resident_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $resident = new Resident();
        $form = $this->createForm(ResidentType::class, $resident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($resident);
            $entityManager->flush();

            return $this->redirectToRoute('app_resident_show', ['id' => $resident->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('common/resident/new.html.twig', [
            'resident' => $resident,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_resident_show', methods: ['GET'])]
    public function show(Resident $resident): Response
    {
        return $this->render('resident/show.html.twig', [
            'resident' => $resident,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_resident_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Resident $resident, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(ResidentType::class, $resident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($resident);
            $entityManager->flush();

            return $this->redirectToRoute('app_resident_show', ['id' => $resident->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('resident/edit.html.twig', [
            'resident' => $resident,
            'form' => $form,
        ]);




//        return $this->render('resident/edit.html.twig', [
//            'resident' => $resident
//        ]);
    }

    #[Route('/room/{id}', name: 'app_resident_room', methods: ['GET'])]
    public function roomResident($id=null): Response
    {
       return $this->render('room/resident.html.twig', [
           'residents' => $this->residentRepository->findByRoom($id)
       ]);
    }

}
