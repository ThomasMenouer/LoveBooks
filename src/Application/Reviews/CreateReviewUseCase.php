<?php

namespace App\Application\Reviews;

use App\Domain\Reviews\Entity\Reviews;
use App\Domain\UserBooks\Entity\UserBooks;
use App\Infrastructure\Persistence\Doctrine\Repository\ReviewsRepository;

final class CreateReviewUseCase
{
    public function __construct(private ReviewsRepository $reviewsRepository) {}

    public function execute(UserBooks $userBook, string $content): Reviews
    {
        // Vérifier si l'utilisateur a déjà laissé une critique pour ce livre
        $review = $userBook->getReview();

        if ($review) {
            // Si une critique existe déjà, on la met à jour
            $review->setContent($content);
            $review->setUpdatedAt(new \DateTimeImmutable());
        } else {
            // Sinon, on crée une nouvelle critique
            $review = new Reviews();
            $review->setUserBook($userBook);
            $review->setContent($content);
        }

        $this->reviewsRepository->saveReview($review);

        return $review;
    }
}
