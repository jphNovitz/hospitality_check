<?php

namespace App\Controller\Profile;

use App\Entity\User;
use App\Form\PasswordFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile_show', methods: ['GET'])]
    public function show(): Response
    {
        $user= $this->getUser();
        return $this->render('profile/show.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user= $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/change-password', name: 'app_password_change', methods: ['GET', 'POST'])]
    public function editPasswword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user= $this->getUser();
        $form = $this->createForm(PasswordFormType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->getData()['new_password']
            );
            $entityManager->getRepository(User::class)->upgradePassword($user, $hashedPassword );

            $this->addFlash('success', 'Password Updated');
            return $this->redirectToRoute('app_profile_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/password-edit.html.twig', [
            'form' => $form,
        ]);
    }

}
