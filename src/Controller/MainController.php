<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function index(TrickRepository $tricks): Response
    {
        return $this->render('main/index.html.twig', [
            'tricks' => $tricks->findBy([],['created_at' => 'ASC'],5,0),
            'offset' => 5
        ]);
    }

    /**
     * @param TrickRepository $tricks
     * @param $offset
     * @return Response
     */
    #[Route('/nextTricks/{offset}', name: 'app_loadMore')]
    public function loadMoreTricks(TrickRepository $tricks, $offset): Response
    {
        $html = $this->render('main/_trickList_partial.html.twig', [
            'tricks' => $tricks->findBy([],['created_at' => 'ASC'],5,$offset)
        ]);

        return new Response($html);

    }

}