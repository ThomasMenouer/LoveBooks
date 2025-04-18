<?php

namespace App\Application\Users\UseCase;

use App\Domain\Users\Entity\Users;
use App\Infrastructure\Persistence\Doctrine\Repository\UserBooksRepository;

final class GetUserProfileStatsUseCase
{
    public function __construct(private UserBooksRepository $userBooksRepository){}

    public function getStats(Users $user): array
    {
        return [
            'bookStats' =>  $this->userBooksRepository->countByStatusForUser($user),
            'totalBooks' => $this->userBooksRepository->getTotalBooksForUser($user),
            'totalPagesRead' =>  $this->userBooksRepository->getTotalPagesReadForUser($user)
        ];

    }

}