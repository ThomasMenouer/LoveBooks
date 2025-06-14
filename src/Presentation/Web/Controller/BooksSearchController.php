<?php

namespace App\Presentation\Web\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[IsGranted('ROLE_USER')]
#[Route('/search', name:'search_')]
final class BooksSearchController extends AbstractController
{

    /**
     * Search for books using Google Books API with the react_component

     */
    #[Route('/', name: 'book', methods: ['GET', 'POST'])]
    public function searchBooks(): Response
    {
        return $this->render('search/search.html.twig');
    }
}
