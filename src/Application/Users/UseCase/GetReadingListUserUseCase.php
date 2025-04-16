<?php

namespace App\Application\Users\UseCase;

use App\Domain\Users\Entity\Users;
use App\Infrastructure\Persistence\Doctrine\Repository\BooksRepository;

final class GetReadingListUserUseCase
{

    public function __construct(private BooksRepository $booksRepository){}

    public function getReadingList(Users $user)
    {
        return $this->booksRepository->getReadingListForUser($user);
    }
}