<?php

namespace App\Application\UserBooks\UseCase;

use App\Domain\UserBooks\Entity\UserBooks;
use App\Infrastructure\Persistence\Doctrine\Repository\UserBooksRepository;

final class EditUserBookUseCase
{
    public function __construct(private UserBooksRepository $userBooksRepository){
    }

    public function editBook(UserBooks $book): void
    {

        // TODO : VOIR pour un formulaire dynamique
        // Si statut = non Lu, alors input pagesRead est caché
        $pageCount = $book->getBook()->getPageCount();

        if ($book->getStatus() === 'Lu') {
            $book->setPagesRead($pageCount);
        }
        elseif ($book->getStatus() === 'En cours de lecture') {
            $book->setPagesRead($book->getPagesRead());
        } 
        else {
            $book->setPagesRead(0);
        }

        if ($book->getPagesRead() >= $pageCount) {

            $book->setStatus('Lu');
            $book->setPagesRead($pageCount);
        }
    
        $this->userBooksRepository->save($book);

    }
}