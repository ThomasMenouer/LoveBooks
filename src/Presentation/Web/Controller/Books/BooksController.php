<?php

namespace App\Presentation\Web\Controller\Books;

use App\Domain\Books\Entity\Books;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/book', name:'book_')]
final class BooksController extends AbstractController
{

    #[Route('/books/{id}', name: 'index')]
    public function index(Books $book, Security $security): Response
    {
        /** @var \App\Domain\Users\Entity\Users $user */
        $user = $security->getUser();

        // Récuperer la note de l'utilisateur sur le livre
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
}
    