<?php

namespace App\Presentation\Web\Controller;

use App\Application\Books\Service\BookFacade;
use App\Presentation\Web\Form\SearchBookType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Books\Service\GoogleBooksService;
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


    #[Route('/api/books', name: 'api_books', methods: ['GET'])]
    public function search(Request $request, GoogleBooksService $googleBooksService): JsonResponse
    {
        $query = $request->query->get('q', '');

        if (trim($query) === '') {
            return new JsonResponse([]);
        }

        $results = $googleBooksService->searchBooks($query);

        return new JsonResponse($results, Response::HTTP_OK, [],  false);
    }
}
