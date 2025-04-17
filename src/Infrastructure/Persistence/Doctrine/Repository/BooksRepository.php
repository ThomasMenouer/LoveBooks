<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Books\Entity\Books;
use App\Domain\Books\Repository\BooksRepositoryInterface;
use  App\Domain\Users\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Books>
 */
class BooksRepository extends ServiceEntityRepository implements BooksRepositoryInterface
{

    private EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Books::class);
        $this->em = $em;
    }


    public function save(Books $book): void
    {
        $this->em->persist($book);
        $this->em->flush();
    }

    public function delete(Books $book): void
    {
        $this->em->remove($book);
        $this->em->flush();
    }

    public function getReadingListForUser(Users $users): array
    {
        $qb = $this->createQueryBuilder('b')
            ->where('b.user = :user')
            ->andWhere('b.status = :status')
            ->setParameter('user', $users)
            ->setParameter('status', 'En cours de lecture');

        return $qb->getQuery()->getResult();
    }

    public function countByStatusForUser(Users $users): array
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b.status, COUNT(b.id) as count')
            ->where('b.user = :user')
            ->setParameter('user', $users)
            ->groupBy('b.status');

        $result = $qb->getQuery()->getResult();

        $counts = [
            'Lu' => 0,
            'En cours de lecture' => 0,
            'Non lu' => 0,
        ];

        foreach ($result as $row) {
            $counts[$row['status']] = $row['count'];
        }

        return $counts;
    }

    public function getTotalPagesReadForUser(Users $users): int
    {
        $qb = $this->createQueryBuilder('b')
            ->select('SUM(b.pagesRead) as totalPagesRead')
            ->where('b.user = :user')
            ->setParameter('user', $users);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function getTotalBooksForUser(Users $users): int
    {
        $qb = $this->createQueryBuilder('b')
            ->select('COUNT(b.id) as totalBooks')
            ->where('b.user = :user')
            ->setParameter('user', $users);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function searchABook(Users $user, array $filters = []): array
    {
        $qb = $this->createQueryBuilder('b')
            ->where('b.user = :user')
            ->setParameter('user', $user);

        if (!empty($filters['query'])) {
            $qb->andWhere('b.title LIKE :q OR b.authors LIKE :q')
                ->setParameter('q', '%' . $filters['query'] . '%');
        }

        return $qb->getQuery()->getResult();
    }

}
