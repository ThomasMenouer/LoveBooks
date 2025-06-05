<?php

namespace App\Domain\Books\Repository;

use App\Domain\Books\Entity\Books;


interface BooksRepositoryInterface
{
    public function getAllBooks(): array;

    public function deleteBook(Books $book): void;
}