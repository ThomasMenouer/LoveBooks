<?php

namespace App\Application\ReviewComments\DTO;

use App\Application\Users\DTO\UsersDTO;


final class CommentsDTO
{
    public function __construct(
        private int $id,
        private string $content,
        private string $createdAt,
        private UsersDTO $user
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
    public function getContent(): string
    {
        return $this->content;
    }
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    public function getUser(): UsersDTO
    {
        return $this->user;
    }
}
