<?php


namespace App\Presentation\Api\Resource\Books;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
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
        // userbook:read = visible dans les réponses GET
        #[Groups(['userbook:read'])]
        private ?int $id = null,

        #[Assert\NotBlank(message: 'Le titre est requis')]
        #[Groups(['userbook:create', 'userbook:read'])]
        private string $title = '',

        #[Assert\NotBlank(message: "L'auteur est requis")]
        #[Groups(['userbook:create', 'userbook:read'])]
        private string $authors = '',

        #[Assert\NotBlank(message: "L'éditeur est requis")]
        #[Groups(['userbook:create', 'userbook:read'])]
        private string $publisher = '',

        #[Assert\NotBlank(message: 'La description est requise')]
        #[Groups(['userbook:create', 'userbook:read'])]
        private string $description = '',

        #[Assert\NotNull(message: 'Le nombre de pages est requis')]
        #[Assert\PositiveOrZero(message: 'Le nombre de pages doit être positif ou nul')]
        #[Groups(['userbook:create', 'userbook:read'])]
        private int $pageCount = 0,

        #[Assert\NotBlank(message: "L'image de couverture est requise")]
        #[Groups(['userbook:create', 'userbook:read'])]
        private ?string $thumbnail = '',

        #[Assert\NotBlank(message: 'La date de publication est requise')]
        #[Assert\Type("\DateTimeInterface", message: 'La date de publication doit être une date valide')]
        #[Groups(['userbook:create', 'userbook:read'])]
        private ?\DateTimeInterface $publishedDate = null,

        #[Groups(['userbook:read'])]
        private ?float $globalRating = null
    ) {}


    public function getId(): ?int
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
    public function getPublishedDate(): ?\DateTimeInterface
    {
        return $this->publishedDate;
    }
    public function getGlobalRating(): ?float
    {
        return $this->globalRating;
    }
}
