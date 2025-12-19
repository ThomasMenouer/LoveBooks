<?php

namespace App\Domain\Books\Repository;

use App\Domain\Books\Entity\Books;


interface BooksRepositoryInterface
{
    public function saveBook(Books $book): void;
    public function getAllBooks(): array;

    public function findBook(int $id): ?Books;

    public function deleteBook(Books $book): void;
}