<?php

namespace App\Application\UserBooks\UseCase;

use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;
use App\Domain\Users\Entity\Users;

final class GetPreferredBookUseCase
{
    public function __construct(private UserBooksRepositoryInterface $userBooksRepository)
    {
    }
    
    public function getPreferredBook(Users $user): array
    {
        return $this->userBooksRepository->getPreferredBookForUser($user);
    }
}