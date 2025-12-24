<?php

namespace App\Domain\Users\Repository;

use App\Domain\Users\Entity\Users;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;



interface UsersRepositoryInterface
{

    /**
     * Summary of delete
     * @param Users $user
     * @return void
     */
    public function delete(Users $user): void;

    /**
     * Summary of getAllUsers
     * @return array<Users>
     */
    public function getAllUsers(): array;

    /**
     * Summary of getUserById
     * @param int $id
     * @return ?Users
     */
    public function getUserById(int $id): ?Users;


    /**
     * Summary of getUserByEmail
     * @param string $email
     * @return ?Users
     */
    public function getUserByEmail(string $email): ?Users;

    
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void;
}