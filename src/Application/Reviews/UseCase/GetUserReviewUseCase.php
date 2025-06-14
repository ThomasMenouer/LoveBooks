<?php

namespace App\Application\Reviews\UseCase;

use App\Domain\Books\Entity\Books;
use App\Domain\Users\Entity\Users;
use App\Domain\Reviews\Entity\Reviews;
use App\Domain\Reviews\Repository\ReviewsRepositoryInterface;


class GetUserReviewUseCase
{
    public function __construct(
        private readonly ReviewsRepositoryInterface $reviewRepository
    ) {}

    public function getUserReview(Books $book, Users $user): ?Reviews
    {
        return $this->reviewRepository->getUserReview($book, $user);

    }
}
