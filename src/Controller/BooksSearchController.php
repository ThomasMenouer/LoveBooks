<?php

namespace App\Controller;

use App\Dto\BookDto;
use App\Entity\Books;
use App\Form\SearchBookType;
use App\Service\BookFacade;
use App\Service\GoogleBooksService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/search', name:'search_')]
final class BooksSearchController extends AbstractController
{

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param GoogleBooksService $googleBooksService
     * @return Response
     */
    #[Route('/', name: 'book')]
    public function searchBooks(Request $request, GoogleBooksService $googleBooksService): Response
    {
        $form = $this->createForm(SearchBookType::class);
        $form->handleRequest($request);

        $data = [];

        if ($form->isSubmitted() && $form->isValid()) {

            $title = $form->get('title')->getData();
            $data = $googleBooksService->searchBooks($title);

            return $this->render('search/results.html.twig', [
                'books' => $data['items'],
            ], new Response('', Response::HTTP_OK, ['Turbo-Frame' => 'books_results']));

        }

        return $this->render('search/search.html.twig', [
            'form' => $form,
            'books' => $data
            
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
        ];
        
        $book = $bookFacade->getData($data);

        $bookFacade->saveBook($book);

        $this->addFlash('success', 'Le livre a bien été ajouté à votre bibliothèque');

        return $this->redirectToRoute('search_book');

        // return $this->render('search/search.html.twig', [
        //     'book' => $book
        // ]);
    }
}
