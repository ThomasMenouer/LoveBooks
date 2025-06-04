<?php

namespace App\Application\Admin\Users\UseCase;

use App\Domain\Users\Repository\UsersRepositoryInterface;

final class GetAllUsersUseCase
{
    public function __construct(private UsersRepositoryInterface $usersRepository){}

    /**
     * Retrieves all users from the repository.
     *
     * @return array An array of all users.
     */
    public function getAllUsers(): array
    {
        return $this->usersRepository->getAllUsers();

    }
}

