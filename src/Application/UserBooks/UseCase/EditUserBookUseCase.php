<?php

namespace App\Application\UserBooks\UseCase;

use App\Domain\UserBooks\Enum\Status;
use App\Domain\UserBooks\Entity\UserBooks;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;

final class EditUserBookUseCase
{
    public function __construct(private UserBooksRepositoryInterface $userBooksRepository){
    }

    public function editBook(UserBooks $book): void
    {

        // TODO : VOIR pour un formulaire dynamique
        // Si statut = non Lu, alors input pagesRead est caché
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