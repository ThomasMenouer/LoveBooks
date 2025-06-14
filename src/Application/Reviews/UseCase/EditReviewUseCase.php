<?php

namespace App\Application\Reviews\UseCase;

use App\Domain\Reviews\Entity\Reviews;
use App\Domain\Reviews\Repository\ReviewsRepositoryInterface;
use DateTime;
use DateTimeImmutable;

class EditReviewUseCase
{
    public function __construct(private ReviewsRepositoryInterface $reviewsRepository){}

        public function editReview(Reviews $review, string $content): void 
        {
            $review->setContent($content);
            $review->setUpdatedAt(new DateTimeImmutable());
            
            $this->reviewsRepository->editReview($review);
        }
}