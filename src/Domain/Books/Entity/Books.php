<?php

namespace App\Domain\Books\Entity;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use ApiPlatform\Metadata\ApiResource;
use App\Domain\UserBooks\Entity\UserBooks;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Infrastructure\Persistence\Doctrine\Repository\BooksRepository;


// #[ApiResource]
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

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: Types::INTEGER)]
    private int $pageCount;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $publishedDate;

    #[OneToMany(mappedBy: 'book', targetEntity: UserBooks::class)]
    private Collection $userBooks;

    private ?float $globalRating = null;

    public function __construct(
        string $title, 
        string $authors, 
        string $publisher, 
        string $description, 
        int $pageCount, 
        \DateTimeInterface $publishedDate,  
        string $thumbnail)
    {
        $this->title = $title;
        $this->authors = $authors;
        $this->publisher = $publisher;
        $this->description = $description;
        $this->pageCount = $pageCount;
        $this->publishedDate = $publishedDate;
        $this->thumbnail = $thumbnail;
        $this->userBooks = new ArrayCollection();
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

    public function getGlobalRating()
    {

        $userBooks = $this->getUserBooks();

        if($userBooks->isEmpty()) {
            return $this->globalRating = null;
        }

        $totalRating = 0;
        $count = 0;

        foreach ($userBooks as $userBook) {
            $userBookRating = $userBook->getUserRating();
            if ($userBookRating !== null) {
                $totalRating += $userBookRating;
                $count++;
            }
        }

        if ($count === 0) {
            return $this->globalRating = null;
        }
        
        $this->globalRating = $totalRating / $count;


        return [
            'rating' => round($this->globalRating, 2),
            'count' => $count
        ];
    }

    public function setGlobalRating(float $globalRating): static
    {
        $this->globalRating = $globalRating;
        return $this;
    }

        public function getUserBooks(): Collection
    {
        return $this->userBooks;
    }

    public function addUserBook(UserBooks $userBook): void
    {
        if (!$this->userBooks->contains($userBook)) {
            $this->userBooks[] = $userBook;
            $userBook->setBook($this);
        }
    }

    public function removeUserBook(UserBooks $userBook): void
    {
        if ($this->userBooks->removeElement($userBook)) {
            if ($userBook->getBook() === $this) {
                $userBook->setBook(null);
            }
        }
    }
}
