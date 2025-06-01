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
        if (empty($this->query)) {
            return [];
        }

        return $this->googleBooksService->searchBooks($this->query);
    }
}
