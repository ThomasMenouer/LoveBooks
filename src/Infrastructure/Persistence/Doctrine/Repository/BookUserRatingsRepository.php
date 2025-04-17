<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Entity\BookUserRatings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookUserRatings>
 */
class BookUserRatingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookUserRatings::class);
    }

}
