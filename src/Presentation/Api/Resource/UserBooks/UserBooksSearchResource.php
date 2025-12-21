<?php

namespace App\Presentation\Api\Resource\UserBooks;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use App\Presentation\Api\Provider\UserBooks\UserBooksSearchProvider;


/**
 * Ressource pour la recherche de livres de l'utilisateur
 */
#[ApiResource(
    shortName: 'UserBooksSearch',
    security: "is_granted('ROLE_USER')",
    operations: [
        new GetCollection(
            uriTemplate: '/user_books_resources/search',
            formats: [
                'json' => ['application/json'],
                'jsonld' => ['application/ld+json']
            ],
            description: 'Returns user books matching a query string.',
            parameters: [
                'query' => new QueryParameter(
                    schema: ['type' => 'string'],
                    description: 'The search query string.',
                    required: false,
                )
            ],
        ),
    ],
    provider: UserBooksSearchProvider::class,
    paginationEnabled: false,
)]
final class UserBooksSearchResource
{
    public function __construct(
        public ?int $id = null,
        public ?int $bookId = null,
        public ?string $status = null,
        public ?int $pagesRead = null,
        public ?bool $isPreferred = null,
        public ?int $userRating = null,
        public ?array $book = null,
    ) {}
}
