<?php

namespace App\Entity;

use App\Repository\BooksRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BooksRepository::class)]
class Books
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $authors;

    #[ORM\Column(length: 255)]
    private string $publisher;

    #[ORM\Column(length: 255)]
    private string $description;

    #[ORM\Column]
    private int $pageCount;

    #[ORM\Column(length: 255, nullable: true)]
    private string $status = 'Non lu';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private Users $user;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $publishedDate;

    public function __construct(
        string $title, 
        string $authors, 
        string $publisher, 
        string $description, 
        int $pageCount, 
        \DateTimeInterface $publishedDate,  
        string $thumbnail,
        Users $user)
    {
        $this->title = $title;
        $this->authors = $authors;
        $this->publisher = $publisher;
        $this->description = $description;
        $this->pageCount = $pageCount;
        $this->publishedDate = $publishedDate;
        $this->thumbnail = $thumbnail;
        $this->user = $user;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthors(): string
    {
        return $this->authors;
    }

    public function setAuthors(string $authors): static
    {
        $this->authors = $authors;

        return $this;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher): static
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    public function setPageCount(int $pageCount): static
    {
        $this->pageCount = $pageCount;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
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

    public function getPublishedDate(): \DateTimeInterface
    {
        return $this->publishedDate;
    }

    public function setPublishedDate(\DateTimeInterface $publishedDate): static
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }
    public function setThumbnail(string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }
}
