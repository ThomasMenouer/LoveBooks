<?php

namespace App\Application\UserBooks\UseCase;

use App\Domain\Users\Entity\Users;
use App\Infrastructure\Persistence\Doctrine\Repository\UserBooksRepository;

final class GetPreferredBookUseCase
{
    public function __construct(private UserBooksRepository $userBooksRepository)
    {
    }
    
    public function getPreferredBook(Users $user): array
    {
        return $this->userBooksRepository->getPreferredBookForUser($user);
    }
}