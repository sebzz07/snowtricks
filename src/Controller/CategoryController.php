<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'app_category_')]
class CategoryController extends AbstractController
{


    #[Route('/add', name: 'add')]
    public function addCategory(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);


        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
