<?php


namespace App\Application\UserBooks\UseCase;

use App\Domain\UserBooks\Entity\UserBooks;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;


final class GetUserBookByIdUseCase
{
    public function __construct( private UserBooksRepositoryInterface $userBooksRepository ) {}


    public function execute(int $id): ?UserBooks
    {
        return $this->userBooksRepository->getUserBooksById($id);
    }
}

