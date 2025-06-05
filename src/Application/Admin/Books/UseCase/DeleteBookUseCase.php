<?php

namespace App\Application\Admin\Books\UseCase;

use App\Domain\Books\Entity\Books;
use App\Domain\Books\Repository\BooksRepositoryInterface;


final class DeleteBookUseCase
{
    public function __construct(private BooksRepositoryInterface $booksRepository){}


    /**
     * Deletes a book.
     *
     * @param Books $book The book to delete.
     * @return void
     */

    public function deleteBook(Books $book): void
    {
        $this->booksRepository->deleteBook($book);
    }
}

