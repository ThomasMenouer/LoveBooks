<?php 

namespace App\Application\UserBooks\UseCase;

use App\Domain\UserBooks\Entity\UserBooks;
use App\Infrastructure\Persistence\Doctrine\Repository\UserBooksRepository;

final class UpdateUserBookReadingProgressUseCase
{

    public function __construct(private UserBooksRepository $userBooksRepository)
    {
    }
    
    public function update(UserBooks $book): void
    {
        $pageCount = $book->getBook()->getPageCount();

        // Si l'utilisateur a marqué "Lu", on force pagesRead à 100 %
        if ($book->getStatus() === 'Lu') {
            $book->setPagesRead($pageCount);
        }

        // Sinon, on s'assure juste que pagesRead ne dépasse pas le max
        if ($book->getPagesRead() > $pageCount) {
            $book->setPagesRead($pageCount);
            $book->setStatus('Lu');
        }

        $this->userBooksRepository->save($book);
    }
}