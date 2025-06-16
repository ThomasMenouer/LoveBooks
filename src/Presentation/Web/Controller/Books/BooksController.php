<?php

namespace App\Presentation\Web\Controller\Books;

use App\Domain\Books\Entity\Books;
use App\Domain\Users\Entity\Users;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Application\UserBooks\UseCase\GetPreferredBookUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/book', name:'book_')]
final class BooksController extends AbstractController
{
    public function __construct(private readonly Security $security) {}

    #[Route('/books/{id}', name: 'index')]
    public function index(Books $book): Response {
        /** @var Users $user */
        $user = $this->security->getUser();

        // Récupération du UserBook s'il existe
        $userBook = $user->getUserBooks()->filter(function ($userBook) use ($book) {
            return $userBook->getBook()->getId() === $book->getId();
        })->first();

        if (!$book) {
            throw $this->createNotFoundException('Le livre n\'existe pas');
        }
        
        return $this->render('books/book_details.html.twig', [
            'book' => $book,
            'userBook' => $userBook,
        ]);
    }


    #[Route('/books_preferred', name: 'preferred')]
    public function bookPreferred(GetPreferredBookUseCase $getPreferredBook): Response
    {
        /** @var \App\Domain\Users\Entity\Users $user */
        $user = $this->security->getUser();

        return $this->render('books/preferred_books.html.twig', [
            'preferredBooks' => $getPreferredBook->getPreferredBook($user),

        ]);
    }

}