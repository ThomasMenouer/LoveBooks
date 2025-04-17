<?php

namespace App\Domain\BookUserRatings\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Books\Entity\Books;
use App\Domain\Users\Entity\Users;

#[ORM\Entity]
class BookUserRatings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'bookRatings')]
    private Users $user;

    #[ORM\ManyToOne(inversedBy: 'userRatings')]
    private Books $book;

    #[ORM\Column(type: 'integer')]
    private int $rating;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): Users
    {
        return $this->user;
    }

    public function setUser(Users $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getBook(): ?Books
    {
        return $this->book;
    }

    public function setBook(Books $book): static
    {
        $this->book = $book;
        return $this;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;
        return $this;
    }
}
