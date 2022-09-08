<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @param TrickRepository $tricks
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function index(TrickRepository $tricks, ManagerRegistry $doctrine): Response
    {
        //$tricks = $doctrine->getRepository(Trick::class)->getAllTricks();
        return $this->render('main/index.html.twig', [
            'tricks' => $tricks->findBy([],[],5,0)
        ]);
    }
    #[Route('/nextTricks/{offset}', name: 'app_loadMore')]
    public function loadMore(TrickRepository $tricks, ManagerRegistry $doctrine, $offset): Response
    {
        //$tricks = $doctrine->getRepository(Trick::class)->getAllTricks();
        return $this->render('main/index.html.twig', [
            'tricks' => $tricks->findBy([],[],5,$offset)
        ]);
    }

}