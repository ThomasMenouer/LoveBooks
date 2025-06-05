<?php

namespace App\Application\Admin\Users\UseCase;

use App\Domain\Users\Entity\Users;
use App\Domain\Users\Repository\UsersRepositoryInterface;

final class DeleteUserUseCase
    {
    public function __construct(private UsersRepositoryInterface $usersRepository){}

    public function deleteUser(Users $user): void
    {
        $this->usersRepository->delete($user);
    }
}