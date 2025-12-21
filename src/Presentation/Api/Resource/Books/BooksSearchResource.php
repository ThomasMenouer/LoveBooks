<?php

namespace App\Presentation\Api\Resource\Books;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use App\Presentation\Api\Provider\Books\BooksSearchProvider;


/**
 * Ressource pour la recherche de livres via l'API Google Books
 * NE FONCTIONNE PAS 
 */
#[ApiResource(
    shortName: 'BooksSearch',
    security: "is_granted('ROLE_USER')",
    operations: [
        new GetCollection(
            uriTemplate: '/books_resources/search',
            //name: 'api_search_books',
            formats: [
                'json' => ['application/json'],
                'jsonld' => ['application/ld+json']
            ],
            description: 'Returns books matching a query string from the Google Books API.',
            parameters: [
                'q' => new QueryParameter(
                    schema: ['type' => 'string'],
                    description: 'The search query string.',
                    required: true,
                )
            ],
        ),
    ],
    provider: BooksSearchProvider::class,
    paginationEnabled: false,
)]
final class BooksSearchResource
{
    // Cette ressource représente les résultats de Google Books
    // Vous pouvez la laisser vide ou ajouter des propriétés si besoin
    public function __construct(
        public readonly mixed $data = null
    ) {}
}