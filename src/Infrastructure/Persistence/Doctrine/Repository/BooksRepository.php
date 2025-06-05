<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Books\Entity\Books;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Books\Repository\BooksRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Books>
 */
class BooksRepository extends ServiceEntityRepository implements BooksRepositoryInterface
{

    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, Books::class);
    }

    public function deleteBook(Books $book): void
    {
        $this->em->remove($book);
        $this->em->flush();
    }

    public function getAllBooks(): array
    {
        return $this->findAll();
    }

}
