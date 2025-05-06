<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Users\Entity\Users;
use App\Domain\UserBooks\Enum\Status;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\UserBooks\Entity\UserBooks;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<UserBooks>
 */
class UserBooksRepository extends ServiceEntityRepository implements UserBooksRepositoryInterface
{
    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, UserBooks::class);
        $this->em = $em;
    }

    public function save(UserBooks $book): void
    {
        $this->em->persist($book);
        $this->em->flush();
    }

    public function delete(UserBooks $book): void
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
            ->setParameter('status', Status::READING);

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

        // Initialise le tableau avec toutes les valeurs possibles de l'enum
        $counts = array_fill_keys(array_map(
            fn(Status $status) => $status->value,
            Status::cases()
        ), 0);

        foreach ($result as $row) {
            $status = $row['status'];
            $counts[$status->value] = (int)$row['count'];
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
        $qb = $this->createQueryBuilder('ub')
            ->join('ub.book', 'b')
            ->where('ub.user = :user')
            ->setParameter('user', $user);
    
        if (!empty($filters['query'])) {
            $qb->andWhere('b.title LIKE :q OR b.authors LIKE :q')
                ->setParameter('q', '%' . $filters['query'] . '%');
        }
    
        return $qb->getQuery()->getResult();
    }

    public function getPreferredBookForUser(Users $user): array
    {
        $qb = $this->createQueryBuilder('ub')
            ->join('ub.book', 'b')
            ->addSelect('b')
            ->where('ub.user = :user')
            ->andWhere('ub.isPreferred = true')
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();

    }

}
