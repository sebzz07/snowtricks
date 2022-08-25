<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Post;
use App\Entity\Trick;
use App\Form\PostType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Service\SlugGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/trick')]
class TrickController extends AbstractController
{
    /**
     * @param TrickRepository $trickRepository
     * @return Response
     */
    #[Route('/', name: 'app_trick_index', methods: ['GET'])]
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TrickRepository $trickRepository,SluggerInterface $slugger, SlugGeneratorService $slugGeneratorService): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictures = $form->get('picture')->getData();


            foreach ($pictures as $picture) {

                dd($picture);
                $originalFilename = pathinfo($picture->getPictureLink() , PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();
                try{
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
                }
                $pic = new Picture();
                $pic->setPictureLink($newFilename);
                $trick->addPicture($pic);
            }

            $trick->setSlug($slugGeneratorService->getSlug($trick->getName()));
            dd($trick);

            $trickRepository->add($trick, true);

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    /**
     * @param Trick $trick
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/{slug}', name: 'app_trick', methods: ['GET'])]
    public function read(Trick $trick, Request $request, EntityManagerInterface $entityManager): Response
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


        return $this->render('trick/trick.html.twig', [
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
    #[Route('/{slug}/addpicture', name: 'app_addPicture')]
    public function AddPicture() :Response
    {

        return $this->render('picture/addpicture.html.twig');
    }


    #[Route('/{slug}/edit', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, TrickRepository $trickRepository): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickRepository->add($trick, true);

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

}
