<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Trick;
use App\Form\PostType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use function PHPUnit\Framework\isNull;

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
    public function new(Request $request, TrickRepository $trickRepository,SluggerInterface $slugger): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictureCollectionFields = $form->get('pictures');

            foreach ($pictureCollectionFields as $pictureField ) {

                $picture = $pictureField->getData();
                $pictureFile= $picture->getFile();
                $originalFilename = pathinfo($pictureFile , PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try{
                    $pictureFile->move(
                        $this->getParameter('Pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
                }

                $picture->setPictureLink($newFilename);
                $picture->setTrick($trick);
                $picture->setUser($this->getUser());
            }

            $trick->setSlug($slugger->slug($trick->getName()));
            $trick->setUser($this->getUser());

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
    #[Route('/{slug}', name: 'app_trick', methods: ['GET', 'POST'])]
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

    #[Route('/{slug}/edit', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, TrickRepository $trickRepository,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureCollectionFields = $form->get('pictures');

            foreach ($pictureCollectionFields as $pictureField ) {
                if($pictureField->getData()->getId() == null) {
                    $picture = $pictureField->getData();
                    $pictureFile= $picture->getFile();
                    $originalFilename = pathinfo($pictureFile , PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                    try{
                        $pictureFile->move(
                            $this->getParameter('Pictures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
                    }

                    $picture->setPictureLink($newFilename);
                    $picture->setTrick($trick);
                    $picture->setUser($this->getUser());
                }

            }

            $trickRepository->add($trick, true);

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[NoReturn]
    #[Route('/{slug}/status/{publicationStatus}', name: 'app_trick_status', methods: ['GET', 'POST'])]
    public function updateStatus(Request $request, string $publicationStatus, Trick $trick, TrickRepository $trickRepository): Response
    {

            $trick->setPublicationStatusTrick($publicationStatus);
            $trickRepository->add($trick, true);

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);

    }

}
