<?php

namespace App\Application\ReviewComments\DTO;

use App\Domain\Users\Entity\Users;
use App\Domain\Reviews\Entity\Reviews;

final class CreateCommentDTO
{
    public function __construct(

        private Reviews $reviews,
        private Users $user,
        private string $content
    ) {}


    public function getReview(): Reviews
    {
        return $this->reviews;
    }
    public function getUser(): Users
    {
        return $this->user;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
