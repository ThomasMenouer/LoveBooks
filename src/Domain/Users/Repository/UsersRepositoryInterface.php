<?php

namespace App\Domain\Users\Repository;

use App\Domain\Users\Entity\Users;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;



interface UsersRepositoryInterface
{

    public function delete(Users $user): void;
    public function getAllUsers(): array;
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void;
}