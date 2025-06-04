<?php

namespace App\Presentation\Web\Controller\User\CRUD;

use Symfony\UX\Turbo\TurboBundle;
use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\UserBooks\UseCase\DeleteBookUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class UserDeleteBookController extends AbstractController
{

    #[Route('/book/{id}/delete', name: 'book_delete', methods: ['GET', 'POST'])]
    public function deleteBook(Request $request, UserBooks $book, DeleteBookUseCase $deleteBookUseCase): Response
    {
    
        if (!$book) {
            throw $this->createNotFoundException('Livre introuvable.');
        }
    
        $bookId = $book->getId();
    
        if ($this->isCsrfTokenValid('delete-book-' . $bookId, $request->request->get('_token'))) {

            $deleteBookUseCase->execute($book);
        }
    
        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
    
            return $this->render('library/books/book_delete.stream.html.twig', [
                'bookId' => $bookId,
            ]);
        }

        return $this->redirectToRoute('library_books');
    }
}