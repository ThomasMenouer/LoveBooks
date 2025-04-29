<?php

namespace App\Presentation\Web\Controller\Books;

use App\Application\Reviews\UseCase\GetReviewsOfBookUseCase;
use App\Domain\Books\Entity\Books;
use App\Presentation\Web\Form\ReviewType;
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
    public function index(Books $book, Security $security, GetReviewsOfBookUseCase $getReviewsOfBookUseCase): Response
    {
        /** @var \App\Domain\Users\Entity\Users $user */
        $user = $security->getUser();

        // Récuperer la note de l'utilisateur sur le livre
        // TODO : Revoir la logique de récupération de la note
        $userBook = $user->getUserBooks()->filter(function ($userBook) use ($book) {
            return $userBook->getBook()->getId() === $book->getId();
        })->first();

        if (!$book) {
            throw $this->createNotFoundException('Le livre n\'existe pas');
        }

        // Récupération des reviews du livre
        $reviews = $getReviewsOfBookUseCase->getReviews($book);

        return $this->render('books/book_details.html.twig', [
            'book' => $book,
            'userBook' => $userBook,
            'reviews' => $reviews,
            'createReviewForm' => $this->createForm(ReviewType::class)->createView(), // formulaire vide
            'editReviewForm' => $this->createForm(ReviewType::class)->createView(), // formulaire vide 
        ]);
    }
}
    