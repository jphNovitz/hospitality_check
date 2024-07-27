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
        $user = $this->getUser();

        if (!$user) {
            return $this->render('welcome.html.twig', []);
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirectToRoute('admin_home');
        }

        if (method_exists($user, 'getName') && $user->getName() && $user->isVerified()) {
            return $this->redirectToRoute('app_resident_index');
        }

        return $this->render('welcome.html.twig', []);
    }
}
