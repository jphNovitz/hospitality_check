<?php

namespace App\Controller\Preference;

use App\Entity\Base;
use App\Form\BaseType;
use App\Repository\BaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/preference/base')]
class BaseController extends AbstractController
{
    #[Route('/', name: 'app_preference_base_index', methods: ['GET'])]
    public function index(BaseRepository $baseRepository): Response
    {
        return $this->render('preference/base/index.html.twig', [
            'bases' => $baseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_preference_base_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $base = new Base();
        $form = $this->createForm(BaseType::class, $base);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($base);
            $entityManager->flush();

            return $this->redirectToRoute('app_preference_base_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('preference/base/new.html.twig', [
            'base' => $base,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_preference_base_show', methods: ['GET'])]
    public function show(Base $base): Response
    {
        return $this->render('preference/base/show.html.twig', [
            'base' => $base,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_preference_base_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Base $base, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BaseType::class, $base);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_preference_base_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('preference/base/edit.html.twig', [
            'base' => $base,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_preference_base_delete', methods: ['POST'])]
    public function delete(Request $request, Base $base, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$base->getId(), $request->request->get('_token'))) {
            $entityManager->remove($base);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_preference_base_index', [], Response::HTTP_SEE_OTHER);
    }
}
