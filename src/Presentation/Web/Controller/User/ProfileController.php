<?php

namespace App\Presentation\Web\Controller\User;

use App\Application\Users\UseCase\GetUserProfileStatsUseCase;
use App\Domain\Books\Entity\Books;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Presentation\Web\Form\BooksReadingUpdateType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Infrastructure\Persistence\Doctrine\Repository\BooksRepository;

#[IsGranted('ROLE_USER')]
#[Route('/profile', name: 'profile_')]
final class ProfileController extends AbstractController
{

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function profileHome(GetUserProfileStatsUseCase $getUserProfileStatsUseCase, BooksRepository $bookRepository, Security $security): Response
    {
        $user = $security->getUser();

        $userStats = $getUserProfileStatsUseCase->getStats($user);

        $books = $bookRepository->getReadingListForUser($user);

        $bookForms = [];

        foreach ($books as $book) {
            $form = $this->createForm(BooksReadingUpdateType::class, $book);
            $bookForms[$book->getId()] = $form->createView();
        }
    
        return $this->render('profile/profile.html.twig', [
            ...$userStats, // -> spread operator, on déplie les clefs/valeurs du tableau
            'readingList' => $books,
            'bookForms' => $bookForms,
        ]);
    }

    #[Route('/books', name: 'books')]
    public function profileBooks(): Response
    {
        /** @var \App\Domain\Users\Entity\Users $user */
        $user = $this->getUser();
        $books = $user->getBooks();

        return $this->render('profile/books/books.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/book/{id}', name: 'book_details')]
    public function showBookDetails(Books $book): Response
    {

        if (!$book) {
            throw $this->createNotFoundException('Le livre n\'existe pas');
        }

        return $this->render('profile/books/book_details.html.twig', [
            'book' => $book,
        ]);
    }
}
