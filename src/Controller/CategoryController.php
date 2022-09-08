<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/category', name: 'app_category_')]
class CategoryController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function indexCategory(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addCategory(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($slugger->slug($category->getName()));
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Your category has been created.');
            return $this->redirectToRoute('app_category_index');
        };

        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
