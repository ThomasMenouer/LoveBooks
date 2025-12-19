<?php


namespace App\Presentation\Api\Resource;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Domain\Books\Entity\Books;
use ApiPlatform\Metadata\ApiResource;
use App\Presentation\Api\Provider\BooksProvider;
use App\Presentation\Api\Processor\BooksProcessor;


/**
 * Sert de DTO
 */
#[ApiResource(

    operations: [
        new Get(),
        new Post(),
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
        private \DateTimeInterface $publishedDate,
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
    public function getPublishedDate(): \DateTimeInterface
    {
        return $this->publishedDate;
    }
    public function getGlobalRating(): ?float
    {
        return $this->globalRating;
    }
}
