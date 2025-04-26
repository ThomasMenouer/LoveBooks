<?php

namespace App\Presentation\Web\Controller\User;

use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Presentation\Web\Form\SearchMyBookType;
use App\Application\Users\UseCase\SearchAbookUseCase;
use App\Presentation\Web\Form\UserBooksReadingUpdateType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Application\Users\UseCase\GetReadingListUserUseCase;
use App\Application\Users\UseCase\GetUserProfileStatsUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/profile', name: 'profile_')]
final class ProfileController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function profileHome(
        GetUserProfileStatsUseCase $getUserProfileStatsUseCase, 
        GetReadingListUserUseCase $getReadingListUserUseCase, 
        Security $security
    ): Response {
        $user = $security->getUser();

        $userStats = $getUserProfileStatsUseCase->getStats($user);
        $books = $getReadingListUserUseCase->getReadingList($user);

        $bookForms = [];

        foreach ($books as $book) {
            $form = $this->createForm(UserBooksReadingUpdateType::class, $book);
            $bookForms[$book->getId()] = $form->createView();
        }

        return $this->render('profile/profile.html.twig', [
            ...$userStats, // spread operator, on insère les clefs/valeurs du tableau
            'readingList' => $books,
            'bookForms' => $bookForms,
        ]);
    }

    #[Route('/books', name: 'books', methods: ['GET', 'POST'])]
    public function profileBooks(Request $request, SearchAbookUseCase $searchAbookUseCase, Security $security): Response
    {
        /** @var \App\Domain\Users\Entity\Users $user */
        $user = $security->getUser();
        $books = $user->getUserBooks();

        $form = $this->createForm(SearchMyBookType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();
            $books = $searchAbookUseCase->getSearchBook($user, $filters ? ['query' => $filters['query']] : []);
        } else {
            $books = $user->getUserBooks();
        }

        return $this->render('profile/books/books.html.twig', [
            'books' => $books,
            'form' => $form->createView()
        ]);
    }
}
