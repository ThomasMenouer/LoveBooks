<?php

namespace App\Controller;

use App\Form\SearchBookType;
use App\Service\GoogleBooksService;
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

            return $this->render('books_search/results.html.twig', [
                'books' => $data['items'],
            ], new Response('', Response::HTTP_OK, ['Turbo-Frame' => 'books_results']));

        }

        return $this->render('books_search/search.html.twig', [
            'form' => $form,
            'books' => $data
            
        ]);
    }

    // #[Route('/results', name: 'results')]
    // public function results(Request $request){

    //     return $this->render('books_search/results.html.twig', [
            
            
    //     ]);
    // }
}
