<?php

namespace App\Application\Users\UseCase;

use App\Domain\Users\Entity\Users;
use App\Domain\UserBooks\Enum\Status;
use App\Infrastructure\Persistence\Doctrine\Repository\UserBooksRepository;

final class GetUserLibraryStatsUseCase
{
    public function __construct(private UserBooksRepository $userBooksRepository){}

    public function getStats(Users $user): array
    {

        // Récupère les stats depuis le repository
        $bookStats = $this->userBooksRepository->countByStatusForUser($user);

        // Transforme les statuts en objets Status
        $bookStatsWithEnum = [];
        foreach ($bookStats as $status => $count) {
            $statusEnum = Status::from($status);  // Convertit le string en Enum
            $bookStatsWithEnum[] = [
                'status' => $statusEnum,
                'count' => $count,
            ];
        }

        return [
            'bookStats' => $bookStatsWithEnum,
            'totalBooks' => $this->userBooksRepository->getTotalBooksForUser($user),
            'totalPagesRead' =>  $this->userBooksRepository->getTotalPagesReadForUser($user)
        ];

    }

}