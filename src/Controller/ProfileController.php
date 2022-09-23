<?php

namespace App\Controller;

use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profileFile = $form->get('profilePicture')->getData();

            if ($profileFile) {
                $originalFilename = pathinfo($profileFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $profileFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $profileFile->move(
                        $this->getParameter('ProfilePictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
                }

                // updates the 'profilePicture' property to store the jpg file name
                // instead of its contents
                $user->setLinkUserPicture($newFilename);

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('notice', 'Your profil has been updates.');
                return $this->redirectToRoute('app_profile');
            };
        }
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfilController',
            'form' => $form->createView(),
        ]);
    }
}

