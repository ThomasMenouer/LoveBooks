<?php

namespace App\Application\Users\UseCase;

use App\Domain\Users\Entity\Users;
use App\Infrastructure\Persistence\Doctrine\Repository\UserBooksRepository;

final class SearchAbookUseCase
{
    public function __construct(private UserBooksRepository $userBooksRepository){}


    public function getSearchBook(Users $user, array $filters = []): array
    {
        return $this->userBooksRepository->searchABook($user, $filters);
    }
}