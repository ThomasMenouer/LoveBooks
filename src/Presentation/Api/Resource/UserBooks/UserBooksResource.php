<?php


namespace App\Presentation\Api\Resource\UserBooks;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Presentation\Api\Provider\UserBooks\UserBooksProvider;
use App\Presentation\Api\Processor\UserBooks\UserBooksProcessor;


/**
 * DTO pour l'entité UserBooks
 */
#[ApiResource(
    shortName: 'UserBook',
    security: "is_granted('ROLE_USER')",
    operations: [
        new Post(),
        new GetCollection(),
        new Get(),
        new Patch(),
        new Delete(),
    ],
    provider: UserBooksProvider::class,
    processor: UserBooksProcessor::class,
)]
final class UserBooksResource
{
    #[Assert\NotNull(message: 'Les informations du livre sont requises')]
    #[Assert\Collection(
        fields: [
            'title' => [
                new Assert\NotBlank(message: 'Le titre est requis'),
                new Assert\Type('string'),
            ],
            'authors' => [
                new Assert\NotBlank(message: "L'auteur est requis"),
                new Assert\Type('string'),
            ],
            'publisher' => [
                new Assert\NotBlank(message: "L'éditeur est requis"),
                new Assert\Type('string'),
            ],
            'description' => [
                new Assert\NotBlank(message: 'La description est requise'),
                new Assert\Type('string'),
            ],
            'pageCount' => [
                new Assert\NotNull(message: 'Le nombre de pages est requis'),
                new Assert\Type('integer'),
            ],
            'publishedDate' => [
                new Assert\NotBlank(message: 'La date de publication est requise'),
                new Assert\Type('string'),
            ],
            'thumbnail' => [
                new Assert\NotBlank(message: "L'image de couverture est requise"),
                new Assert\Type('string'),
            ],
        ],
        allowExtraFields: true,
    )]
    private ?array $book = null;

    public function __construct(
        private ?int $id = null,
        private ?int $bookId = null,
        private ?string $status = null,
        private ?int $pagesRead = null,
        private ?bool $isPreferred = null,
        private ?int $userRating = null,
        ?array $book = null,
        private ?array $review = null,
    ) {
        $this->book = $book;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): ?int
    {
        return $this->bookId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getPagesRead(): ?int
    {
        return $this->pagesRead;
    }

    public function getIsPreferred(): ?bool
    {
        return $this->isPreferred;
    }

    public function getUserRating(): ?int
    {
        return $this->userRating;
    }

    public function getBook(): ?array
    {
        return $this->book;
    }

    public function getReview(): ?array
    {
        return $this->review;
    }


}
