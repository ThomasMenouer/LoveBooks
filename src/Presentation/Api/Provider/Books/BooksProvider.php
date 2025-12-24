<?php


namespace App\Presentation\Api\Provider\Books;

use App\Domain\Books\Entity\Books;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Presentation\Api\Resource\Books\BooksResource;
use ApiPlatform\Metadata\CollectionOperationInterface;
use App\Application\Books\Service\GoogleBooksService;
use App\Domain\Books\Repository\BooksRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BooksProvider implements ProviderInterface
{
    public function __construct(
        private readonly RequestStack $requestStack, 
        private readonly GoogleBooksService $googleBooksService,
        private readonly BooksRepositoryInterface $booksRepositoryInterface) {}
    
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        
        if ($operation->getName() === 'search_books') {
            $request = $this->requestStack->getCurrentRequest();
            $query = $request->query->get('q', '');
            
            if (trim($query) === '') {
                return [];
            }
            
            return $this->googleBooksService->searchBooks($query);
        }
        if ($operation instanceof CollectionOperationInterface) {
            $books = $this->booksRepositoryInterface->getAllBooks();
            $data = array_map(fn(Books $b) => $this->toArray($b), $books);
            return new JsonResponse($data, Response::HTTP_OK);
        }

        $book = $this->booksRepositoryInterface->findBook($uriVariables['id'] ?? null);
        return $book ? $this->toResource($book) : null;
    }


    public function toResource(Books $book): BooksResource
    {
        return new BooksResource(
            $book->getId(),
            $book->getTitle(),
            $book->getAuthors(),
            $book->getPublisher(),
            $book->getDescription(),
            $book->getPageCount(),
            $book->getThumbnail(),
            $book->getPublishedDate(),
            $book->getGlobalRating()['rating'] ?? null
        );
    }

    public function toArray(Books $book): array
    {
        return [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'authors' => $book->getAuthors(),
            'publisher' => $book->getPublisher(),
            'description' => $book->getDescription(),
            'pageCount' => $book->getPageCount(),
            'thumbnail' => $book->getThumbnail(),
            'publishedDate' => $book->getPublishedDate()?->format('Y-m-d'),
            'globalRating' => $book->getGlobalRating()['rating'] ?? null,
        ];
    }
}
