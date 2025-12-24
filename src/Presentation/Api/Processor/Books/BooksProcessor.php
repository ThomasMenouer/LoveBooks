<?php

namespace App\Presentation\Api\Processor\Books;

use App\Domain\Books\Entity\Books;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Domain\Books\Repository\BooksRepositoryInterface;

final class BooksProcessor implements ProcessorInterface
{

    public function __construct(private readonly BooksRepositoryInterface $booksRepositoryInterface)
    {}
    

    /**
     * Summary of process
     * @param mixed $data # Instance de BooksResource (DTO)
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return JsonResponse
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        // Gestion du DELETE
        if ($operation instanceof \ApiPlatform\Metadata\Delete) {
            $bookId = $uriVariables['id'] ?? null;
            if ($bookId) {
                $book = $this->booksRepositoryInterface->findBook($bookId);
                if ($book) {
                    $this->booksRepositoryInterface->deleteBook($book);
                    return new JsonResponse(null, Response::HTTP_NO_CONTENT);
                }
            }
            return new JsonResponse(['error' => 'Livre non trouvé'], Response::HTTP_NOT_FOUND);
        }

        //  On crée une entité du domaine à partir du DTO
        $book = new Books(
            $data->getTitle(),
            $data->getAuthors(),
            $data->getPublisher(),
            $data->getDescription(),
            $data->getPageCount(),
            $data->getPublishedDate(),
            $data->getThumbnail() ?? ''
        );

        $this->booksRepositoryInterface->saveBook($book);

        return new JsonResponse(
            [
                'success' => true,
                'message' => "Le livre à bien été ajouté dans votre bibliothèque."
            ], Response::HTTP_CREATED);
    }


}