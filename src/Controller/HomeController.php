<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if ($user = $this->getUser()) {
            if (null !== $user->getName()) {
                if ($user->isVerified())
                    if (in_array('ROLE_ADMIN', $user->getRoles())) return $this->redirectToRoute('app_admin_home');
                    else return $this->redirectToRoute('app_resident_index');
            } else {
                return $this->render('welcome.html.twig', []);
            }
        }
        return $this->render('welcome.html.twig', []);
    }
}
