<?php

namespace App\Presentation\Web\Controller\Api;


use App\Domain\Books\Entity\Books;
use App\Domain\Users\Entity\Users;
use App\Domain\Reviews\Entity\Reviews;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\ReviewComments\DTO\CreateCommentDTO;
use App\Application\ReviewComments\UseCase\CreateReviewCommentsUseCase;
use App\Application\ReviewComments\UseCase\DeleteReviewCommentsUseCase;
use App\Presentation\Web\Transformer\ReviewTransformer;
use App\Application\Reviews\UseCase\CreateReviewUseCase;
use App\Application\Reviews\UseCase\DeleteReviewUseCase;
use App\Application\Reviews\UseCase\EditReviewUseCase;
use App\Presentation\Web\Transformer\CommentTransformer;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Application\Reviews\UseCase\GetUserReviewUseCase;
use App\Application\Reviews\UseCase\GetReviewsOfBookUseCase;
use App\Domain\ReviewComments\Entity\ReviewComments;
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

        if (!$review) 
        {
            return new JsonResponse(['error' => 'Aucune review trouvée.'], Response::HTTP_NOT_FOUND);
        }
        $reviewDTO = $this->reviewTransformer->transform($review);
        $data = $this->reviewTransformer->transformToArray($reviewDTO);

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

    #[Route("/reviews/{id}/comments", name: "review_comments", methods: ["GET"])]
    public function getComments(Reviews $review, CommentTransformer $commentTransformer): JsonResponse
    {
        /**
         * @var Users $user
         */
        $user = $this->security->getUser();

        $comments = $review->getComments()->toArray();
        $data = $commentTransformer->transformManyToArray($comments);

        return new JsonResponse([
            'comments' => $data,
            'currentUserId' => $user->getId()]);
    }

    #[Route("/review/{id}/comment/create", name: "create_comment", methods: ["POST"])]
    public function createComment(Reviews $review, Request $request,
        CreateReviewCommentsUseCase $createReviewCommentsUseCase): JsonResponse
    {
        $user = $this->security->getUser();
        $data = json_decode($request->getContent(), true);

        if (empty($data['content'])) {
            return new JsonResponse(['error' => 'Le contenu est obligatoire'], Response::HTTP_BAD_REQUEST);
        }

        $CommentDTO = new CreateCommentDTO($review, $user, $data['content']);
        $createReviewCommentsUseCase->createReviewComment($CommentDTO);

        return new JsonResponse(['success' => true], Response::HTTP_CREATED);
    }

    #[Route("/comment/{id}", "delete_comment", methods:["DELETE"])]
    public function deleteComment(ReviewComments $reviewComments, DeleteReviewCommentsUseCase $deleteReviewCommentsUseCase): JsonResponse
    {
        /** @var Users $user */
        $user = $this->security->getUser();

        if ($user->getId() !== $reviewComments->getUser()->getId())
        {
            return new JsonResponse(['message' => 'Accès interdit.'], Response::HTTP_FORBIDDEN);
        }

        $deleteReviewCommentsUseCase->deleteReviewComment($reviewComments);

        return new JsonResponse(['success' => true, 'message' => 'Votre commentaire à bien été supprimé.']);

    }
}
