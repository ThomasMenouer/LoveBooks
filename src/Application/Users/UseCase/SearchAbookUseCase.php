<?php

namespace App\Application\Users\UseCase;

use App\Domain\Users\Entity\Users;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;

final class SearchAbookUseCase
{
    public function __construct(private UserBooksRepositoryInterface $userBooksRepository){}

    /**
     * Retrieves a list of books based on the user's search criteria.
     *
     * @param Users $user The user performing the search.
     * @param array $filters An associative array of filters to apply to the search.
     * @return array An array of books that match the search criteria.
     */
    public function getSearchBook(Users $user, array $filters = []): array
    {
        return $this->userBooksRepository->searchABook($user, $filters);
    }
}