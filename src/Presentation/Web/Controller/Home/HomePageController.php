<?php

namespace App\Presentation\Web\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/', name: 'home_')]
final class HomePageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('library_index');
        }


        return $this->render('home/home.html.twig', [
        ]);
    }
}
