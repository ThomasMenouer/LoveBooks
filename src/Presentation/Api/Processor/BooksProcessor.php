<?php

namespace App\Presentation\Api\Processor;

use App\Domain\Books\Entity\Books;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Presentation\Api\Resource\BooksResource;
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
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): object
    {

        //  On crée une entité du domaine à partir du DTO
        $book = new Books(
            $data->title,
            $data->authors,
            $data->publisher,
            $data->description,
            $data->pageCount,
            $data->publishedDate,
            $data->thumbnail ?? ''
        );

        $this->booksRepositoryInterface->saveBook($book);

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


}