<?php

namespace App\Application\ReviewComments\UseCase;

use App\Domain\ReviewComments\Entity\ReviewComments;
use App\Domain\ReviewComments\Repository\ReviewCommentsRepositoryInterface;

final class DeleteReviewCommentsUseCase
{
    public function __construct(private ReviewCommentsRepositoryInterface $reviewCommentsRepositoryInterface) {}

    public function deleteReviewComment(ReviewComments $comment): void
    {

        $this->reviewCommentsRepositoryInterface->deleteReviewComment($comment);
    }
}