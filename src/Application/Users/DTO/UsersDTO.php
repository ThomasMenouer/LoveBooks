<?php

namespace App\Application\Users\DTO;

class UsersDTO
{
    public function __construct(private int $id, private string $name, private string $avatar){}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }
}
