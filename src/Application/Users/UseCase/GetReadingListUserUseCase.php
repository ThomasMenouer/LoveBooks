<?php

namespace App\Application\Users\UseCase;

use App\Domain\Users\Entity\Users;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;


final class GetReadingListUserUseCase
{
    public function __construct(private UserBooksRepositoryInterface $userBooksRepository){}

    public function getReadingList(Users $user): array
    {
        return $this->userBooksRepository->getReadingListForUser($user);
    }
}