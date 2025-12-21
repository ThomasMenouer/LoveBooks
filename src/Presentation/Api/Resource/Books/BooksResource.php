<?php


namespace App\Presentation\Api\Resource\Books;

use DateTimeInterface;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Presentation\Api\Provider\Books\BooksProvider;
use App\Presentation\Api\Processor\Books\BooksProcessor;


/**
 * DTO pour l'entité Books
 */
#[ApiResource(

    security: "is_granted('ROLE_USER')",
    operations: [
        new Post(),
        new GetCollection(),
        new Get(),
        new Patch(),
        new Delete(),
    ],
    provider: BooksProvider::class,
    processor: BooksProcessor::class,
)]
final class BooksResource
{
    public function __construct(
        private int $id,
        private string $title,
        private string $authors,
        private string $publisher,
        private string $description,
        private int $pageCount,
        private ?string $thumbnail,
        private DateTimeInterface $publishedDate,
        private ?float $globalRating
    ) {}


    public function getId(): int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getAuthors(): string
    {
        return $this->authors;
    }
    public function getPublisher(): string
    {
        return $this->publisher;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getPageCount(): int
    {
        return $this->pageCount;
    }
    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }
    public function getPublishedDate(): DateTimeInterface
    {
        return $this->publishedDate;
    }
    public function getGlobalRating(): ?float
    {
        return $this->globalRating;
    }
}
