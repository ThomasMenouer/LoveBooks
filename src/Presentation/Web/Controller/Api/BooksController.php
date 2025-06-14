<?php

namespace App\Presentation\Web\Controller\Api;

use App\Domain\Books\Entity\Books;
use App\Domain\Users\Entity\Users;
use App\Domain\Reviews\Entity\Reviews;
use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Bundle\SecurityBundle\Security;
use App\Application\Books\Service\BookFacade;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Books\Service\GoogleBooksService;
use App\Application\Users\UseCase\SearchAbookUseCase;
use App\Presentation\Web\Transformer\ReviewTransformer;
use App\Presentation\Web\Transformer\CommentTransformer;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Presentation\Web\Transformer\UserBooksTransformer;
use App\Application\Reviews\UseCase\GetReviewsOfBookUseCase;
use App\Application\Reviews\UseCase\GetUserReviewUseCase;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted("ROLE_USER")]
#[Route("/api", name: "api_")]
final class BooksController extends AbstractController
{
    public function __construct(
        private readonly Security $security,
        private UserBooksTransformer $userBooksTransformer,
        private UserBooksRepositoryInterface $userBooksRepositoryInterface,
        private readonly SearchAbookUseCase $searchAbookUseCase,
        private readonly ReviewTransformer $reviewTransformer,
    ) {}

    #[Route('/books', name: 'search_books', methods: ['GET'])]
    public function searchBooks(Request $request, GoogleBooksService $googleBooksService): JsonResponse
    {
        $query = $request->query->get('q', '');

        if (trim($query) === '') {
            return new JsonResponse([]);
        }

        $results = $googleBooksService->searchBooks($query);

        return new JsonResponse($results, Response::HTTP_OK, [],  false);
    }

    #[Route("/user-books", name: "user_books", methods: ["GET"])]
    public function getUsersBooks(): JsonResponse
    {
        /** @var Users $user  */
        $user = $this->security->getUser();
        $data = $user->getUserBooks()->toArray();

        $booksArray = $this->userBooksTransformer->transformMany($data);

        return new JsonResponse($booksArray, Response::HTTP_OK);
    }
    
    #[Route("/user-books/add", name: "user_books_add",  methods: ["POST"])]
    public function addUserBook(Request $request, BookFacade $bookFacade): JsonResponse
    {

        /** @var Users $user */
        $user = $this->security->getUser();

        $content = $request->getContent();
        $data = json_decode($content, true);

        try {
            $bookDto = $bookFacade->getData($data);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['success' => false, 'error' => $e->getMessage()], 400);
        }
    
        $book = $bookFacade->saveBook($bookDto);
        $bookFacade->saveUserBook($book);

        return new JsonResponse(['success' => true, 'message' => "Le livre à bien été ajouté dans votre bibliothèque."]);
    }

    #[Route("/user-books/delete/{id}", name: "user_books_delete", methods: ["DELETE"])]
    public function deleteUserBook(UserBooks $userBook): JsonResponse
    {
        /** @var Users $user */
        $user = $this->security->getUser();

        if ($user  !== $userBook->getUser()) {
            return new JsonResponse(['message' => 'Accès interdit.'], Response::HTTP_FORBIDDEN);
        }

        $this->userBooksRepositoryInterface->delete($userBook);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


    #[Route("/user-books/search", name: "search_user_books", methods: ["GET"])]
    public function searchUserBook(Request $request, UserBooksTransformer $transformer): JsonResponse
    {
        /** @var Users $user */
        $user = $this->security->getUser();

        $query = $request->query->get('query', '');

        $filters = [];
        if (!empty($query)) {
            $filters['query'] = $query;
        }

        $results = $this->searchAbookUseCase->getSearchBook($user, $filters);

        return new JsonResponse($transformer->transformMany($results));
    }

}
