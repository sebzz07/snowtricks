<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Trick;
use App\Form\PostType;
use App\Form\TrickType;
use App\Repository\PostRepository;
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
use function PHPUnit\Framework\throwException;

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
    public function read(Trick $trick, PostRepository $postRepository, Request $request, EntityManagerInterface $entityManager): Response
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


        $posts = $postRepository->findBy(['trick'=> $trick],['created_at' => 'DESC'], 5, 0);

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
            'posts' => $posts,
            'form' => $form->createView(),
            'offsetpost' => 5
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
                if ($pictureField->getData()->getFile() != null) {
                    $picture = $pictureField->getData();
                    $pictureFile = $picture->getFile();
                    $originalFilename = pathinfo($pictureFile, PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                    try {
                        $pictureFile->move(
                            $this->getParameter('Pictures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        return $this->redirectToRoute('app_trick_edit', [], Response::HTTP_SEE_OTHER);
                    }

                    $picture->setPictureLink($newFilename);
                    $picture->setTrick($trick);
                    $picture->setUser($this->getUser());
                }
            }

                $videoCollectionFields = $form->get('video');
                foreach ($videoCollectionFields as $videoField ) {
                    try {
                        $video = $videoField->getData();
                    if(str_contains($video->getVideoLink(), "?v=")) {
                        $videoLink = $video->getVideoLink();
                        $arrayExplode = explode("=", $videoLink);
                        if(count($arrayExplode) <=2) {
                            $NewVideoLink = "https://www.youtube.com/embed/" . $arrayExplode[1];
                            $video->setVideoLink($NewVideoLink);
                        }
                    }
                    } catch (FileException $e) {
                        // ... handle exception if something happens during conversion link
                        return $this->redirectToRoute('app_trick_edit', [], Response::HTTP_SEE_OTHER);
                    }
                    $video->setTrick($trick);
                    $video->setUser($this->getUser());
                }


            $trickRepository->add($trick, true);
            $this->addFlash(
                'notice',
                "The trick was updated correctly"
            );
            return $this->redirectToRoute('app_trick', ['slug' =>$trick->getSlug()]);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[NoReturn]
    #[Route('/{slug}/status/{publicationStatus}/{originOfRequest}', name: 'app_trick_status', methods: ['GET', 'POST'])]
    public function updateStatus(string $originOfRequest, string $publicationStatus, Trick $trick, TrickRepository $trickRepository): Response
    {
        if($publicationStatus == 'Unpublished'){
            $message = 'the trick was unpublished';
        } else {
            $message = 'the trick was published';
        }

        $this->addFlash(
            'notice',
            $message
        );
        $trick->setPublicationStatusTrick($publicationStatus);
        $trickRepository->add($trick, true);

        return $this->redirectToRoute($originOfRequest, [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @param Trick $trick
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/{slug}/nextposts/{offsetPost}', name: 'app_post_loadMore', methods: ['GET', 'POST'])]
    public function loadMorePosts(PostRepository $postRepository,Trick $trick, int $offsetPost): Response
    {

        $posts = $postRepository->findBy(['trick'=> $trick],['created_at' => 'DESC'], 5, $offsetPost);

        return $this->render('trick/_postList_partial.html.twig', [
            'posts' => $posts
        ]);
    }
}
