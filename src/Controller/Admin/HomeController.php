<?php

namespace App\Controller\Admin;

use App\Repository\ResidentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/admin/', name: 'admin_home')]
    public function index(ResidentRepository $residentRepository): Response
    {
        return $this->render('admin/home/index.html.twig');
    }
}
