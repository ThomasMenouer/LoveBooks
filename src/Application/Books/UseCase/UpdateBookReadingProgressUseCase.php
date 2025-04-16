<?php 

namespace App\Application\Books\UseCase;

use App\Domain\Books\Entity\Books;
use App\Infrastructure\Persistence\Doctrine\Repository\BooksRepository;

final class UpdateBookReadingProgressUseCase
{

    public function __construct(private BooksRepository $booksRepository)
    {
    }
    
    public function update(Books $book): void
    {
        if ($book->getStatus() === 'Lu') {
            $book->setPagesRead($book->getPageCount());
        } elseif ($book->getStatus() === 'En cours de lecture') {
            $book->setPagesRead($book->getPagesRead());
        } else {
            $book->setPagesRead(0);
        }

        if ($book->getPagesRead() >= $book->getPageCount()) {
            $book->setStatus('Lu');
            $book->setPagesRead($book->getPageCount());
        } else {
            $book->setStatus('En cours de lecture');
        }

        $this->booksRepository->save($book);
    }
}