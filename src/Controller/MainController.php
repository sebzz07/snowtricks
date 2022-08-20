<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Trick;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    /**
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $tricks = $doctrine->getRepository(Trick::class)->getAllTricks();
        return $this->render('main/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

}