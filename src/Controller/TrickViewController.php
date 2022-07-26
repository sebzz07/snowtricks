<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickViewController extends AbstractController
{
    #[Route('/trickView', name: 'app_trickView')]
    public function index(): Response
    {
        return $this->render('trickView/index.html.twig', [
            'controller_name' => 'TrickViewController',
        ]);
    }
}
