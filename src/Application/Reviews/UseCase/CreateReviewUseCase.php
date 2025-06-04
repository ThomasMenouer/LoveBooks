<?php

namespace App\Application\Reviews\UseCase;

use App\Domain\Reviews\Entity\Reviews;
use App\Domain\Reviews\Repository\ReviewsRepositoryInterface;
use App\Domain\UserBooks\Entity\UserBooks;

final class CreateReviewUseCase
{
    public function __construct(private ReviewsRepositoryInterface $reviewsRepository) {}

    public function createReview(UserBooks $userBook, string $content): void
    {

        $review = new Reviews();
        $review->setUserBook($userBook);
        $review->setContent($content);

        $this->reviewsRepository->saveReview($review);
    }
}