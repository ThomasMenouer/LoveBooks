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

    #[Route('/home', name: 'index', methods: ['GET'])]
    public function profileHome(): Response 
    {
        $user = $this->security->getUser();

        return $this->render('library/library.html.twig', [
            "user" => $user,
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
