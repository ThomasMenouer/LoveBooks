<?php

namespace App\Controller;

use App\Form\BooksType;
use App\Form\SearchBookType;
use App\Repository\BooksRepository;
use App\Service\GoogleBooksService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/mybooks', name: 'mybooks_')]
final class MyBooksController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $books = $user->getBooks();

        return $this->render('my_books/index.html.twig', [
            'books' => $books
            
        ]);
    }

    #[Route('/book/{id}', name: 'book_details')]
    public function showBookDetails(BooksRepository $booksRepository, $id): Response
    {
        // Retrieve the book details from the database based on the provided $id
        $book = $booksRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('The book does not exist.');
        }

        // Render the book details page and pass the book object to it
        return $this->render('my_books/book/book_details.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/book/{id}/edit', name: 'book_edit')]
    public function editBook(Request $request, EntityManagerInterface $em, BooksRepository $booksRepository, $id): Response
    {
        // Retrieve the book details from the database based on the provided $id
        $book = $booksRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('The book does not exist.');
        }

        // Create the form for book editing
        $form = $this->createForm(BooksType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the updated book to the database
            $em->flush();

            return $this->redirectToRoute('mybooks_book_details', ['id' => $book->getId()]);
        }

        // Render the book editing form
        return $this->render('my_books/book/book_edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }
}
