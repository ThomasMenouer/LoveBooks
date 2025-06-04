<?php

namespace App\Presentation\Web\Twig\Components;

use App\Application\Books\Service\GoogleBooksService;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent(name: 'book_search', template: 'components/book_search.html.twig')]
class BookSearchComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';
    private GoogleBooksService $googleBooksService;

    public function __construct(GoogleBooksService $googleBooksService)
    {
        $this->googleBooksService = $googleBooksService;
    }

    public function getResults(): array
    {
        $query = trim($this->query);

        if (empty($this->query) || $query === '') {
            return [];
        }

        $results = $this->googleBooksService->searchBooks($query);

        // $booksWithImages = array_filter($results['items'] ?? [], function ($item) {
        //     return isset($item['volumeInfo']['imageLinks']['thumbnail']);
        // });
    
        // Limiter à 5 résultats
        return array_slice($results['items'], 0, 5);
    }
}
