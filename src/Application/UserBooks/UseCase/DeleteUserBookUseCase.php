<?php

namespace App\Application\UserBooks\UseCase;

use App\Domain\UserBooks\Entity\UserBooks;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;

final class DeleteUserBookUseCase
{

    public function __construct(private UserBooksRepositoryInterface $userBooksRepository){}

    /**
     * Supprime un livre
     *
     * @param UserBooks $book
     * @return void
     */
    public function execute(UserBooks $book): void
    {
        $this->userBooksRepository->delete($book);
    }

}