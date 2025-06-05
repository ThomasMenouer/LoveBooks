<?php

namespace App\Presentation\Web\Controller\User;


use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Presentation\Web\Form\SearchMyBookType;
use App\Application\Users\UseCase\SearchAbookUseCase;
use App\Presentation\Web\Form\UserBooksReadingUpdateType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Application\Users\UseCase\GetReadingListUserUseCase;
use App\Application\Users\UseCase\GetUserLibraryStatsUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/library', name: 'library_')]
final class LibraryController extends AbstractController
{
    public function __construct(private readonly Security $security){}

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function profileHome(
        GetUserLibraryStatsUseCase $getUserLibraryStatsUseCase, 
        GetReadingListUserUseCase $getReadingListUserUseCase): Response 
    {
        $user = $this->security->getUser();

        $userStats = $getUserLibraryStatsUseCase->getStats($user);
        $books = $getReadingListUserUseCase->getReadingList($user);

        $bookForms = [];

        foreach ($books as $book) {
            $form = $this->createForm(UserBooksReadingUpdateType::class, $book);
            $bookForms[$book->getId()] = $form->createView();
        }

        return $this->render('library/library.html.twig', [
            ...$userStats, // spread operator, on insère les clefs/valeurs du tableau
            'readingList' => $books,
            'bookForms' => $bookForms,
        ]);
    }

    #[Route('/books', name: 'books', methods: ['GET', 'POST'])]
    public function libraryBooks(Request $request, SearchAbookUseCase $searchAbookUseCase): Response
    {
        /**
         * @var \App\Domain\Users\Entity\Users $user
         */
        $user = $this->security->getUser();
        $books = $user->getUserBooks();

        $form = $this->createForm(SearchMyBookType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();
            $books = $searchAbookUseCase->getSearchBook($user, $filters ? ['query' => $filters['query']] : []);
        } else {
            $books = $user->getUserBooks();
        }

        return $this->render('library/books/books.html.twig', [
            'books' => $books,
            'form' => $form->createView()
        ]);
    }
}
