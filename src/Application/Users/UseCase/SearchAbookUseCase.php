<?php

namespace App\Application\Users\UseCase;

use App\Domain\Users\Entity\Users;
use App\Infrastructure\Persistence\Doctrine\Repository\BooksRepository;

final class SearchAbookUseCase
{
    public function __construct(private BooksRepository $booksRepository){}


    public function getSearchBook(Users $user, array $filters = []): array
    {
        return $this->booksRepository->searchABook($user, $filters);
    }
}