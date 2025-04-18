<?php

namespace App\Application\UserBooks\UseCase;

use App\Domain\UserBooks\Entity\UserBooks;
use App\Infrastructure\Persistence\Doctrine\Repository\UserBooksRepository;

final class EditBookUseCase
{
    public function __construct(private UserBooksRepository $userBooksRepository){
    }

    public function editBook(UserBooks $book): void
    {
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
        else {
            $book->setStatus('En cours de lecture');
        }
    
        $this->userBooksRepository->save($book);

    }
}