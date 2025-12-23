<?php


namespace App\Presentation\Api\Resource\UserBooks;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Presentation\Api\Resource\Books\BooksResource;
use App\Presentation\Api\Provider\UserBooks\UserBooksProvider;
use App\Presentation\Api\Processor\UserBooks\UserBooksProcessor;


/**
 * DTO pour l'entité UserBooks
 *
 * Les "groups" permettent de contrôler quelles propriétés sont :
 * - Lues (normalization) : quand l'API RENVOIE des données (GET, réponse après POST/PATCH)
 * - Écrites (denormalization) : quand l'API REÇOIT des données (body du POST/PATCH)
 */
#[ApiResource(
    shortName: 'UserBook',
    security: "is_granted('ROLE_USER')",
    normalizationContext: ['groups' => ['userbook:read']],
    operations: [
        // POST : création d'un UserBook
        // denormalizationContext : on dit à API Platform quelles propriétés il peut RECEVOIR
        // Le groupe 'userbook:create' permet d'accepter l'objet "book" imbriqué
        new Post(
            validationContext: ['groups' => ['Default', 'create']],
            denormalizationContext: ['groups' => ['userbook:create']]
        ),
        // GET collection : liste des UserBooks
        new GetCollection(
            uriTemplate: '/user_books',
            name: 'get_user_books',
            formats: ['json' => 'application/json']
        ),
        // Reading list : livres en cours de lecture
        new GetCollection(
            uriTemplate: '/user_books/reading-list',
            name: 'reading_list',
            formats: ['json']
        ),

        /**
         * Recherche un livre de l'utilisateur
         */
        new GetCollection(
            uriTemplate: '/user_books/search',
            name: 'search_user_books',
            formats: ['json' => 'application/json'],
            parameters: [
                'titre' => new QueryParameter(
                    description: 'Recherche dans le titre du livre',
                    required: false,
                    schema: ['type' => 'string']
                ),
            ],
        ),
        new Get(),
        new Patch(
            denormalizationContext: ['groups' => ['userbook:update']] //  Recevoir les données pour la mise à jour
        ),
        new Delete(),
    ],
    provider: UserBooksProvider::class,
    processor: UserBooksProcessor::class,
)]
final class UserBooksResource
{
    // userbook:create = accepter l'objet book imbriqué en POST
    // userbook:read = renvoyer l'objet book dans les réponses GET
    #[Assert\NotNull(message: 'Les informations du livre sont requises', groups: ['create'])]
    #[Assert\Valid(groups: ['create'])]
    #[Groups(['userbook:create', 'userbook:read'])]
    private ?BooksResource $book = null;

    public function __construct(

        #[Groups(['userbook:read'])]
        private ?int $id = null,
        private ?int $bookId = null,

        #[Groups(['userbook:read', 'userbook:update'])]
        private ?string $status = null,
        #[Groups(['userbook:read', 'userbook:update'])]
        private ?int $pagesRead = null,
        #[Groups(['userbook:read', 'userbook:update'])]
        private ?bool $isPreferred = null,
        #[Groups(['userbook:read', 'userbook:update'])]
        private ?int $userRating = null,
        ?BooksResource $book = null,
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

    public function getBook(): ?BooksResource
    {
        return $this->book;
    }

    public function getReview(): ?array
    {
        return $this->review;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function setPagesRead(?int $pagesRead): self
    {
        $this->pagesRead = $pagesRead;
        return $this;
    }

    public function setIsPreferred(?bool $isPreferred): self
    {
        $this->isPreferred = $isPreferred;
        return $this;
    }

    public function setUserRating(?int $userRating): self
    {
        $this->userRating = $userRating;
        return $this;
    }
}
