<?php

namespace App\Controller;

use App\Entity\Trick;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $tricks = $doctrine->getRepository(Trick::class)->findAll();
        return $this->render('main/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    #[Route('/trick/{slug}', name: 'app_trick')]
    public function read(Trick $trick) :Response
    {
        return $this->render('main/trick.html.twig', [
            'trick' => $trick
        ]);
    }
}