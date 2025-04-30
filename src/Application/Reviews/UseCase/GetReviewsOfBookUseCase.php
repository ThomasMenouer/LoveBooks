<?php

namespace App\Application\Reviews\UseCase;

use App\Domain\Books\Entity\Books;
use App\Domain\Reviews\Repository\ReviewsRepositoryInterface;

final class GetReviewsOfBookUseCase
{

    public function __construct(private ReviewsRepositoryInterface $reviewsRepository){}

    public function getReviews(Books $book): array
    {
        return $this->reviewsRepository->getReviewsOfBook($book);
    }


}