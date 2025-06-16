<?php

namespace App\Presentation\Web\Transformer;

use App\Domain\Reviews\Entity\Reviews;
use App\Application\Users\DTO\UsersDTO;
use App\Application\Reviews\DTO\ReviewDTO;

class ReviewTransformer
{
    public function transform(Reviews $review): ReviewDTO
    {
        $userBook = $review->getUserBook();
        $user = $userBook->getUser();

        return new ReviewDTO(
            id: $review->getId(),
            content: $review->getContent(),
            rating: $userBook->getUserRating() ?? 0,
            createdAt: $review->getCreatedAt()->format('Y-m-d H:i:s'),
            status: $userBook->getStatus()?->value ?? 'inconnu',
            user: new UsersDTO(
                id: $user->getId(),
                name: $user->getName(),
                avatar: $user->getAvatar()
            ),
            commentsCount: $review->getComments()->count(),
        );
    }

    public function transformToArray(ReviewDTO $review): array
    {
        return [
            'id' => $review->getId(),
            'content' => $review->getContent(),
            'rating' => $review->getRating(),
            'status' => $review->getStatus(),
            'createdAt' => $review->getCreatedAt(),
            'user' => [
                'id' => $review->getUser()->getId(),
                'name' => $review->getUser()->getName(),
                'avatar' => $review->getUser()->getAvatar()
            ],
            'commentsCount' => $review->getCommmentsCount()
        ];
    }

    public function transformMany(array $reviews): array
    {
        return array_map([$this, 'transform'], $reviews);
    }


    public function transformManyToArray(array $reviews): array
    {
        return array_map(fn(Reviews $review) => $this->transformToArray($this->transform($review)), $reviews);
    }
}
