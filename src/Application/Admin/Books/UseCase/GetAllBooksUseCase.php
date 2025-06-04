<?php

namespace App\Application\Admin\Books\UseCase;

use App\Domain\Books\Repository\BooksRepositoryInterface;


final class GetAllBooksUseCase
{
    public function __construct(private BooksRepositoryInterface $booksRepository){}

    /**
     * Retrieves all users from the repository.
     *
     * @return array An array of all users.
     */
    public function getAllBooks(): array
    {
        return $this->booksRepository->getAllBooks();

    }
}

