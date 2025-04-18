<?php

namespace App\Domain\UserBooks\Repository;

use App\Domain\Users\Entity\Users;
use App\Domain\UserBooks\Entity\UserBooks;

interface UserBooksRepositoryInterface
{

    public function getReadingListForUser(Users $users): array;

    public function countByStatusForUser(Users $users): array;

    public function getTotalPagesReadForUser(Users $users): int;

    public function getTotalBooksForUser(Users $users): int;

    public function delete(UserBooks $book): void;

    public function save(UserBooks $books): void;
    
}
