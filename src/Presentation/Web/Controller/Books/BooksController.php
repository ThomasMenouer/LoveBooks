<?php

namespace App\Presentation\Web\Controller\Books;

use App\Domain\Books\Entity\Books;
use App\Presentation\Web\Form\ReviewType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use App\Presentation\Web\Form\ReviewCommentsType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Application\Reviews\UseCase\GetReviewsOfBookUseCase;
use App\Application\UserBooks\UseCase\GetPreferredBookUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/book', name:'book_')]
final class BooksController extends AbstractController
{
    public function __construct(private readonly Security $security) {}

    #[Route('/books/{id}', name: 'index')]
    public function index(Books $book, GetReviewsOfBookUseCase $getReviewsOfBookUseCase, FormFactoryInterface $formFactory): Response
    {
        /** @var \App\Domain\Users\Entity\Users $user */
        $user = $this->security->getUser();

        $userBook = $user->getUserBooks()->filter(function ($userBook) use ($book) {
            return $userBook->getBook()->getId() === $book->getId();
        })->first();

        if (!$book) {
            throw $this->createNotFoundException('Le livre n\'existe pas');
        }

        // Récupération des reviews du livre
        $reviews = $getReviewsOfBookUseCase->getReviews($book);

        $commentForms = [];
        foreach ($reviews as $review) {
            $commentForms[$review->getId()] = $formFactory
                ->create( ReviewCommentsType::class)
                ->createView();
        }


        return $this->render('books/book_details.html.twig', [
            'book' => $book,
            'userBook' => $userBook,
            'reviews' => $reviews,
            'createReviewForm' => $this->createForm(ReviewType::class)->createView(), // formulaire vide
            'editReviewForm' => $this->createForm(ReviewType::class, $userBook->getReview())->createView(), // formulaire vide
            'commentForms' => $commentForms,
        ]);
    }

    #[Route('/books_preferred', name: 'preferred')]
    function bookPreferred(GetPreferredBookUseCase $getPreferredBook): Response
    {
        /** @var \App\Domain\Users\Entity\Users $user */
        $user = $this->security->getUser();

        return $this->render('books/preferred_books.html.twig', [
            'preferredBooks' => $getPreferredBook->getPreferredBook($user),

        ]);
    }
}