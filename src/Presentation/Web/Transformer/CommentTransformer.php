<?php

namespace App\Presentation\Web\Transformer;

use App\Application\Users\DTO\UsersDTO;
use App\Application\ReviewComments\DTO\CommentsDTO;
use App\Domain\ReviewComments\Entity\ReviewComments;

class CommentTransformer
{
    public function transform(ReviewComments $comment): CommentsDTO
    {
        $user = $comment->getUser();

        return new CommentsDTO(
            id: $comment->getId(),
            content: $comment->getContent(),
            createdAt: $comment->getCreatedAt()->format('Y-m-d H:i:s'),
            user: new UsersDTO(
                id: $user->getId(),
                name: $user->getName(),
                avatar: $user->getAvatar()
            )
        );
    }

    public function transformToArray(CommentsDTO $comment): array
    {
        return [
            'id' => $comment->getId(),
            'content' => $comment->getContent(),
            'createdAt' => $comment->getCreatedAt(),
            'user' => [
                'id' => $comment->getUser()->getId(),
                'name' => $comment->getUser()->getName(),
                'avatar' => $comment->getUser()->getAvatar()
            ],
        ];
    }

    public function transformMany(array $comments): array
    {
        return array_map([$this, 'transform'], $comments);
    }

    public function transformManyToArray(array $comments): array
    {
        return array_map(fn($comment) => $this->transformToArray($this->transform($comment)), $comments);
    }
}
