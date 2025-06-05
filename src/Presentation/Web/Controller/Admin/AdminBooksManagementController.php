<?php

namespace App\Presentation\Web\Controller\Admin;

use App\Domain\Books\Entity\Books;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Application\Admin\Books\UseCase\DeleteBookUseCase;
use App\Application\Admin\Books\UseCase\GetAllBooksUseCase;
use App\Application\Reviews\UseCase\GetReviewsOfBookUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN', message: 'Accès refusé.', statusCode: Response::HTTP_FORBIDDEN)]
#[Route('/', name: 'admin_')]
final class AdminBooksManagementController extends AbstractController
{
    #[Route('/admin/books_management', name: 'books_management_index')]
    public function index(Request $request, GetAllBooksUseCase $getAllBooksUseCase): Response
    {
        $pagination = $getAllBooksUseCase->getPaginatedBooks($request);
    
        return $this->render('admin/books/books_management.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/admin/books_management/book/{id}/edit', name: 'book_detail', methods: ['GET'])]
    public function edit(Books $books, GetReviewsOfBookUseCase $getReviewsOfBookUseCase): Response
    {
        $reviews = $getReviewsOfBookUseCase->getReviews($books);

        return $this->render('admin/books/book_detail.html.twig', [
            'book' => $books,
            'reviews' => $reviews,
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
