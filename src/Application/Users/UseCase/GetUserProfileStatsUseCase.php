<?php

namespace App\Application\Users\UseCase;

use App\Domain\Users\Entity\Users;
use App\Infrastructure\Persistence\Doctrine\Repository\BooksRepository;

final class GetUserProfileStatsUseCase
{
    public function __construct(private BooksRepository $booksRepository){}

    public function getStats(Users $user): array
    {
        return [
            'bookStats' =>  $this->booksRepository->countByStatusForUser($user),
            'totalBooks' => $this->booksRepository->getTotalBooksForUser($user),
            'totalPagesRead' =>  $this->booksRepository->getTotalPagesReadForUser($user)
        ];

    }

}