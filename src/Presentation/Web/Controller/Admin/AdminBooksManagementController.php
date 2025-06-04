<?php

namespace App\Presentation\Web\Controller\Admin;

use App\Application\Admin\Books\UseCase\DeleteBookUseCase;
use App\Application\Admin\Books\UseCase\GetAllBooksUseCase;
use App\Domain\Books\Entity\Books;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN', message: 'Accès refusé.', statusCode: Response::HTTP_FORBIDDEN)]
#[Route('/', name: 'admin_')]
final class AdminBooksManagementController extends AbstractController
{
    #[Route('/admin/books_management', name: 'books_management_index')]
    public function index(GetAllBooksUseCase $getAllBooksUseCase): Response
    {
        $books = $getAllBooksUseCase->getAllBooks();

        return $this->render('admin/books_management.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/admin/books_management/book/{id}/delete', name: 'book_delete', methods: ['POST'])]
    public function delete(Books $books, DeleteBookUseCase $deleteBookUseCase): Response
    {
        $deleteBookUseCase->deleteBook($books);
        
        $this->addFlash('success', 'Livre supprimé avec succès.');

        return $this->redirectToRoute('admin_books_management_index');
    }
}
