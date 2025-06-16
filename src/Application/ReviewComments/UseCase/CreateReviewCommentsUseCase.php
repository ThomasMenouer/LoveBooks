<?php

namespace App\Application\ReviewComments\UseCase;

use App\Domain\Users\Entity\Users;
use App\Domain\ReviewComments\Entity\ReviewComments;
use App\Application\ReviewComments\DTO\CreateCommentDTO;
use App\Domain\ReviewComments\Repository\ReviewCommentsRepositoryInterface;

final class CreateReviewCommentsUseCase
{
    public function __construct(private ReviewCommentsRepositoryInterface $reviewCommentsRepositoryInterface) {}

    public function createReviewComment(CreateCommentDTO $createCommentDTO): void
    {
        $comment = new ReviewComments();
        $comment->setReview($createCommentDTO->getReview());
        $comment->setUser($createCommentDTO->getUser());
        $comment->setContent($createCommentDTO->getContent());

        $this->reviewCommentsRepositoryInterface->saveReviewComment($comment);
    }
}