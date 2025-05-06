<?php

namespace App\Application\Users\UseCase;

use App\Domain\Users\Entity\Users;
use App\Infrastructure\Persistence\Doctrine\Repository\UserBooksRepository;


final class GetReadingListUserUseCase
{

    public function __construct(private UserBooksRepository $userBooksRepository){}

    public function getReadingList(Users $user): array
    {
        return $this->userBooksRepository->getReadingListForUser($user);
    }
}