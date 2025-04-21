<?php


namespace App\Twig\Components;

use App\Application\Books\Service\GoogleBooksService;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;


#[AsLiveComponent]
class BookSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';
    private GoogleBooksService $googleBooksService;

    public function __construct(GoogleBooksService $googleBooksService)
    {
        $this->googleBooksService = $googleBooksService;
    }

    public function getBooks(): array
    {

        return $this->googleBooksService->searchBooks($this->query);
    }
}
