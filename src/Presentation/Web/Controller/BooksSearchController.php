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
     * Search for books using Google Books API
     *
     * @param Request $request
     * @param GoogleBooksService $googleBooksService
     * @return Response
     */
    #[Route('/', name: 'book', methods: ['GET', 'POST'])]
    public function searchBooks(Request $request, GoogleBooksService $googleBooksService): Response
    {
        $form = $this->createForm(SearchBookType::class);
        $form->handleRequest($request);

        $books = [];

        // Cas POST
        if ($form->isSubmitted() && $form->isValid()) {

            $title = $form->get('title')->getData();
            $data = $googleBooksService->searchBooks($title);
            $books = $data['items'];

            return $this->render('search/results.html.twig', [
                'books' => $books
            ], new Response('', Response::HTTP_OK, ['Turbo-Frame' => 'books_results']));

        }

        // Cas GET : titre passé dans l'URL (depuis la navbar)
        $title = trim($request->query->get('title', ''));
        if ($title !== '') {
            $form->get('title')->setData($title);
            $result = $googleBooksService->searchBooks($title);
            $books = $result['items'];
        }

        return $this->render('search/search.html.twig', [
            'form' => $form,
            'books' => $books
            
        ]);
    }

    #[Route('/add', name:'add_book', methods:['GET'])]
    public function addBook(Request $request, BookFacade $bookFacade)
    {
        $data = [
            'title' => $request->query->get('title', 'Titre inconnu'),
            'authors' => $request->query->get('authors', 'Auteur inconnu'),
            'publisher' => $request->query->get('publisher', 'Editeur inconnu'),
            'description' => $request->query->get('description', 'Pas de description'),
            'pageCount' => $request->query->get('pageCount', 0),
            'publishedDate' => $request->query->get('publishedDate', null),
            'thumbnail' => $request->query->get('thumbnail', 'Pas d\'image'),
        ];
        
        $bookDto = $bookFacade->getData($data);

        $book = $bookFacade->saveBook($bookDto);

        $bookFacade->saveUserBook($book);

        $this->addFlash('success', 'Le livre a bien été ajouté à votre bibliothèque');

        return $this->redirectToRoute('book_index', [
            'id' => $book->getId(),
        ]);
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
