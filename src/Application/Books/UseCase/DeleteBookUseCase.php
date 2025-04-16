<?php

namespace App\Application\Books\UseCase;

use App\Domain\Books\Entity\Books;
use App\Domain\Books\Repository\BooksRepositoryInterface;

final class DeleteBookUseCase
{

    public function __construct(private BooksRepositoryInterface $booksRepository)
    {

    }

    /**
     * Supprime un livre
     *
     * @param Books $book
     * @return void
     */
    public function execute(Books $book): void
    {
        $this->booksRepository->delete($book);
    }

}