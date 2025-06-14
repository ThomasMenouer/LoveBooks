<?php

namespace App\Application\Reviews\DTO;

use App\Application\Users\DTO\UsersDTO;


final class ReviewDTO
{
    public function __construct(

        private int $id,
        private string $content,
        private int $rating,
        private string $createdAt,
        private string $status,
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

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getUser(): UsersDTO
    {
        return $this->user;
    }

}
