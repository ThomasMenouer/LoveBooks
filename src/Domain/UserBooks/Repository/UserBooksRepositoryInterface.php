<?php

namespace App\Domain\UserBooks\Repository;

use App\Domain\Users\Entity\Users;
use App\Domain\UserBooks\Entity\UserBooks;

interface UserBooksRepositoryInterface
{
    public function searchABook(Users $users, array $filters = []): array;

    public function getReadingListForUser(Users $users): array;

    public function countByStatusForUser(Users $users): array;

    public function getTotalPagesReadForUser(Users $users): int;

    public function getTotalBooksForUser(Users $users): int;

    public function getPreferredBookForUser(Users $users): array;

    public function delete(UserBooks $book): void;

    public function save(UserBooks $books): void;
    
}
