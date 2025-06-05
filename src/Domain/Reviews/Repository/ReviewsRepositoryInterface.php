<?php

namespace App\Domain\Reviews\Repository;

use App\Domain\Books\Entity\Books;
use App\Domain\Reviews\Entity\Reviews;

interface ReviewsRepositoryInterface
{

    public function getReviewsOfBook(Books $book): array;

    public function getAllReviews(): array;

    public function deleteReview(Reviews $review): void;

    public function saveReview(Reviews $review): void;

    public function editReview(Reviews $review): void;

}
