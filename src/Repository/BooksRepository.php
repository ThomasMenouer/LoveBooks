<?php

namespace App\Repository;

use App\Entity\Books;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Books>
 */
class BooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Books::class);
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

}
