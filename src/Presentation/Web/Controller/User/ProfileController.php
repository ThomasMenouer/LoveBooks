<?php

namespace App\Presentation\Web\Controller\User;

use App\Application\Users\UseCase\SearchAbookUseCase;
use App\Domain\Books\Entity\Books;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Presentation\Web\Form\SearchMyBookType;
use App\Presentation\Web\Form\BooksReadingUpdateType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Application\Users\UseCase\GetReadingListUserUseCase;
use App\Application\Users\UseCase\GetUserProfileStatsUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[IsGranted('ROLE_USER')]
#[Route('/profile', name: 'profile_')]
final class ProfileController extends AbstractController
{

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function profileHome(GetUserProfileStatsUseCase $getUserProfileStatsUseCase, GetReadingListUserUseCase $getReadingListUserUseCase, Security $security): Response
    {
        $user = $security->getUser();

        $userStats = $getUserProfileStatsUseCase->getStats($user);

        $books = $getReadingListUserUseCase->getReadingList($user);

        $bookForms = [];

        foreach ($books as $book) {
            $form = $this->createForm(BooksReadingUpdateType::class, $book);
            $bookForms[$book->getId()] = $form->createView();
        }
    
        return $this->render('profile/profile.html.twig', [
            ...$userStats, // -> spread operator, on insère les clefs/valeurs du tableau
            'readingList' => $books,
            'bookForms' => $bookForms,
        ]);
    }

    #[Route('/books', name: 'books', methods: ['GET', 'POST'])]
    public function profileBooks(Request $request, SearchAbookUseCase $searchAbookUseCase, Security $security): Response
    {
        /** @var \App\Domain\Users\Entity\Users $user */
        $user = $security->getUser();
        $books = $user->getBooks();

        $form = $this->createForm(SearchMyBookType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();
            $books = $searchAbookUseCase->getSearchBook($user, $filters ? ['query' => $filters['query']] : []);
        } else {

            $books = $user->getBooks();
        }

        return $this->render('profile/books/books.html.twig', [
            'books' => $books,
            'form' => $form->createView()
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
