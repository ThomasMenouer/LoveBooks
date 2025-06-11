<?php

namespace App\Application\Users\UseCase;

use App\Domain\Users\Entity\Users;
use App\Domain\UserBooks\Enum\Status;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;

final class GetUserLibraryStatsUseCase
{
    public function __construct(private UserBooksRepositoryInterface $userBooksRepository){}

    public function getStats(Users $user): array
    {
        // Récupère les stats depuis le repository
        $bookStats = $this->userBooksRepository->countByStatusForUser($user);

        // Transforme les statuts en objets Status
        $bookStatsWithEnum = [];
        foreach ($bookStats as $status => $count) {
            $statusEnum = Status::from($status);  // Convertit le string en Enum
            $bookStatsWithEnum[] = [
            'status' => $statusEnum->name,
            'label' => $statusEnum->label(),
            'color' => $statusEnum->color(),
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