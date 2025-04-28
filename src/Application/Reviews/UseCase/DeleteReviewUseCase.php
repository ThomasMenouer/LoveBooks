<?php

namespace App\Application\Reviews\UseCase;

use App\Domain\Reviews\Entity\Reviews;
use App\Domain\Reviews\Repository\ReviewsRepositoryInterface;

class DeleteReviewUseCase
{
    public function __construct(private ReviewsRepositoryInterface $reviewsRepositoryInterface) {}

    public function deleteReview(Reviews $review): void
    {
        $this->reviewsRepositoryInterface->deleteReview($review);
    }
}