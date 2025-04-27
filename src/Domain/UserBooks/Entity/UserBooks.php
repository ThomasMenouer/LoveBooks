<?php

namespace App\Domain\UserBooks\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Books\Entity\Books;
use App\Domain\Users\Entity\Users;
use App\Domain\Reviews\Entity\Reviews;
use App\Domain\UserBooks\Enum\Status;
use App\Infrastructure\Persistence\Doctrine\Repository\UserBooksRepository;

#[ORM\Entity(repositoryClass: UserBooksRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_user_book', columns: ['user_id', 'book_id'])]
class UserBooks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'userBooks')]
    #[ORM\JoinColumn(nullable: false)]
    private Users $user;

    #[ORM\ManyToOne(inversedBy: 'userBooks')]
    #[ORM\JoinColumn(nullable: false)]
    private Books $book;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 0])]
    private int $pagesRead = 0;

    #[ORM\Column(type: "string", enumType: Status::class)]
    private Status $status = Status::NOT_READ;

    #[ORM\Column(nullable: true)]
    private ?int $userRating = null;

    #[ORM\OneToOne(mappedBy: 'userBook', targetEntity: Reviews::class, cascade: ['persist', 'remove'])]
    private ?Reviews $review = null;


    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getBook(): Books
    {
        return $this->book;
    }

    public function setBook(?Books $book): self
    {
        $this->book = $book;
        return $this;
    }

    public function getPagesRead(): int
    {
        return $this->pagesRead;
    }

    public function setPagesRead(?int $pagesRead): self
    {
        $this->pagesRead = $pagesRead;
        return $this;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getUserRating(): ?int
    {
        return $this->userRating;
    }

    public function setUserRating(?int $userRating): self
    {
        $this->userRating = $userRating;
        return $this;
    }

    public function getReview(): ?Reviews
    {
        return $this->review;
    }

    public function setReview(?Reviews $review): static
    {
        $this->review = $review;
        return $this;
    }

    public function markAsRead(): void
    {
        $this->status = Status::READ;
        $this->pagesRead = $this->book->getPageCount();
    }

    public function markAsAbandoned(): void
    {
        $this->status = Status::ABANDONED;
    }

    public function markAsReading(): void
    {
        $this->status = Status::READING;

    }

    public function markAsNotRead(): void
    {
        $this->status = Status::NOT_READ;
        $this->pagesRead = 0;
    }

}
