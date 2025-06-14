<?php

namespace App\Presentation\Web\Controller\Api;

use PHPUnit\Util\Json;
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
use App\Application\Reviews\UseCase\CreateReviewUseCase;
use App\Application\Reviews\UseCase\DeleteReviewUseCase;
use App\Application\Reviews\UseCase\EditReviewUseCase;
use App\Presentation\Web\Transformer\CommentTransformer;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Application\Reviews\UseCase\GetUserReviewUseCase;
use App\Presentation\Web\Transformer\UserBooksTransformer;
use App\Application\Reviews\UseCase\GetReviewsOfBookUseCase;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Infrastructure\Persistence\Doctrine\Repository\UserBooksRepository;

#[IsGranted("ROLE_USER")]
#[Route("/api", name: "api_")]
final class ApiReviewsController extends AbstractController
{
    public function __construct(
        private readonly Security $security,
        private readonly ReviewTransformer $reviewTransformer,
    ) {}

    #[Route("/reviews/{id}/review", name: "reviews", methods: ["GET"])]
    public function getReviewList(Books $book, GetReviewsOfBookUseCase $getReviewsOfBookUseCase ): JsonResponse
    {
        $reviews = $getReviewsOfBookUseCase->getReviews($book);
        $data = $this->reviewTransformer->transformManyToArray($reviews);

        return new JsonResponse($data);
    }

    #[Route("/reviews/{id}/user-review", name: "user_review", methods: ["GET"])]
    public function getReview(Books $book, GetUserReviewUseCase $getUserReviewUseCase): JsonResponse
    {
        $user = $this->security->getUser();

        $review = $getUserReviewUseCase->getUserReview($book, $user);
        $reviewDTO = $this->reviewTransformer->transform($review);
        $data = $this->reviewTransformer->transformToArray($reviewDTO);

        //dd($data);

        return new JsonResponse($data);
    }

    #[Route("/reviews/{id}/comments", name: "review_comments", methods: ["GET"])]
    public function getComments(Reviews $review, CommentTransformer $commentTransformer): JsonResponse
    {
        $comments = $review->getComments()->toArray();
        $data = $commentTransformer->transformManyToArray($comments);

        return new JsonResponse($data);
    }

    #[Route("/review/create", name: "create_review", methods: ["POST"])]
    public function createReview(Request $request, UserBooksRepository $userBooksRepository, 
        CreateReviewUseCase $createReviewUseCase): JsonResponse 
    {
        $user = $this->security->getUser();

        $data = json_decode($request->getContent(), true);

        // Ici on récupère l'UserBooks lié (tu peux aussi passer juste BookId et chercher UserBooks en interne)
        $userBook = $userBooksRepository->findOneBy([
            'user' => $user,
            'book' => $data['bookId'],
        ]);

        if (!$userBook) {
            return $this->json(['error' => 'Aucun lien UserBooks trouvé'], 404);
        }

        $createReviewUseCase->createReview(
            $userBook,
            $data['content'],
        );

        return $this->json(['success' => true], 201);
    }

    #[Route("/review/{id}/edit", name:"review_edit", methods:['POST'])]
    public function editReview(Reviews $review, Request $request, EditReviewUseCase $editReviewUseCase): JsonResponse
    {
        /** @var Users $user */
        $user = $this->security->getUser();

        if ($user  !== $review->getUserBook()->getUser()) {
            return new JsonResponse(['message' => 'Accès interdit.'], Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);

        $editReviewUseCase->editReview(
            $review,
            $data['content'],
        );

        return new JsonResponse(['success' => true, 'message' => 'Votre review a bien été modifiée.']);
    }



    #[Route("/review/{id}/delete", name:"review_delete", methods:['DELETE'])]
    public function deleteReview(Reviews $review, DeleteReviewUseCase $deleteReviewUseCase): JsonResponse
    {
        /** @var Users $user */
        $user = $this->security->getUser();

        if ($user  !== $review->getUserBook()->getUser()) {
            return new JsonResponse(['message' => 'Accès interdit.'], Response::HTTP_FORBIDDEN);
        }

        $deleteReviewUseCase->deleteReview($review);

        return new JsonResponse(['success' => true, 'message' => 'Votre review à bien été supprimée.']);
    }

}
