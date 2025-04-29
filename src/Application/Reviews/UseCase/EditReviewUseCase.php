<?php

namespace App\Application\Reviews\UseCase;

use App\Domain\Reviews\Entity\Reviews;
use App\Domain\Reviews\Repository\ReviewsRepositoryInterface;

class EditReviewUseCase
{
    public function __construct(private ReviewsRepositoryInterface $reviewsRepository){}

        public function editReview(Reviews $review): void 
        {
            
            $this->reviewsRepository->editReview($review);
        }
}