<?php

namespace App\Application\ReviewComments\UseCase;

use App\Domain\ReviewComments\Entity\ReviewComments;
use App\Domain\ReviewComments\Repository\ReviewCommentsRepositoryInterface;

final class CreateReviewCommentsUseCase
{
    public function __construct(private ReviewCommentsRepositoryInterface $reviewCommentsRepositoryInterface) {}

    public function createReviewComment(ReviewComments $comment): void
    {

        $this->reviewCommentsRepositoryInterface->saveReviewComment($comment);
    }
}