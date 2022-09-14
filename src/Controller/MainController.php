<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MainController extends AbstractController
{
    /**
     * @param TrickRepository $tricks
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function index(TrickRepository $tricks): Response
    {
        if ($this->getUser() !== null){
            $criteria = ['publicationStatusTrick' => 'Published' ];
        }else{
            $criteria = [];
        }


        return $this->render('main/index.html.twig', [
            'tricks' => $tricks->findBy($criteria,['created_at' => 'ASC'],8,0),
            'offset' => 8
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
        if ($this->getUser() !== null){
            $criteria = ['publicationStatusTrick' => 'Published' ];
        }else{
            $criteria = [];
        }
        return $this->render('main/_trickList_partial.html.twig', [
            'tricks' => $tricks->findBy($criteria,['created_at' => 'ASC'],8,$offset)
        ]);


    }

}