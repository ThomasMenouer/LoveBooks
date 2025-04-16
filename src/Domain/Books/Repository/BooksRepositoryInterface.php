<?php

namespace App\Domain\Books\Repository;

use App\Domain\Books\Entity\Books;
use App\Domain\Users\Entity\Users;

interface BooksRepositoryInterface
{

    public function getReadingListForUser(Users $users): array;

    public function countByStatusForUser(Users $users): array;

    public function getTotalPagesReadForUser(Users $users): int;

    public function getTotalBooksForUser(Users $users): int;

    public function delete(Books $book): void;
    
}
