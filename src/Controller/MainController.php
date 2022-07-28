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

    /**
     * @param Trick $trick
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/trick/{slug}', name: 'app_trick', methods: ['GET'])]
    public function read(Trick $trick, Request $request, EntityManagerInterface $entityManager) :Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class,$post)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser())
                ->setTrick($trick);
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', 'Your comment has been published.');
            return $this->redirectToRoute('app_trick', ['slug' =>$trick->getSlug()]);
        };


        return $this->render('main/trick.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Trick $trick
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/trick/{slug}/addpicture', name: 'app_addPicture')]
    public function AddPicture() :Response
    {

        return $this->render('picture/addpicture.html.twig');
    }
}