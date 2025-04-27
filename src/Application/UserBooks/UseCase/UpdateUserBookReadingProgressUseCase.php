<?php 

namespace App\Application\UserBooks\UseCase;

use App\Domain\UserBooks\Entity\UserBooks;
use App\Domain\UserBooks\Enum\Status;
use App\Infrastructure\Persistence\Doctrine\Repository\UserBooksRepository;

use function Symfony\Component\String\b;

final class UpdateUserBookReadingProgressUseCase
{

    public function __construct(private UserBooksRepository $userBooksRepository)
    {
    }
    
    public function update(UserBooks $book): void
    {
        $pageCount = $book->getBook()->getPageCount();

        match ($book->getStatus()) {
            Status::READ => $book->markAsRead(),
            Status::READING => $book->markAsReading(),
            Status::ABANDONED => $book->markAsAbandoned(),
            Status::NOT_READ => $book->markAsNotRead(),
        };

        // On s'assure que pagesRead ne dépasse pas le max
        if ($book->getPagesRead() >= $pageCount) {
            $book->markAsRead();
        }

        $this->userBooksRepository->save($book);
    }
}