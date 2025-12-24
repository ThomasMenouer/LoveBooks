<?php


namespace App\Application\Admin\Users\UseCase;

use App\Domain\Users\Entity\Users;
use App\Domain\Users\Repository\UsersRepositoryInterface;

final class GetUserByIdUseCase
{
    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
    ) {}

    public function getUserById(int $id): ?Users
    {
        return $this->usersRepository->getUserById($id);
    }
}