<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;


use Dom\Entity;
use App\Domain\Books\Entity\Books;
use App\Domain\Reviews\Entity\Reviews;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Reviews\Repository\ReviewsRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Reviews>
 */
class ReviewsRepository extends ServiceEntityRepository implements ReviewsRepositoryInterface
{
    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Reviews::class);
        $this->em = $em;
    }

    /**
     * Save a review
     * @param Reviews $review
     * @return void
     */
    public function saveReview(Reviews $review): void
    {
        $this->em->persist($review);
        $this->em->flush();
    }

    /**
     * Edit a review
     * @param Reviews $review
     * @return void
     */
    public function editReview(Reviews $review): void
    {
        $this->em->flush();
    }

    /**
     * Delete a review
     * @param Reviews $review
     * @return void
     */
    public function deleteReview(Reviews $review): void
    {
        $this->em->remove($review);
        $this->em->flush();
    }

    /**
     * Get reviews of a book
     * @param Books $book
     * @return array
     */
    public function getReviewsOfBook(Books $book): array
    {
        $qb = $this->createQueryBuilder('r')
        ->innerJoin('r.userBook', 'ub')  // Jointure avec UserBooks
        ->innerJoin('ub.book', 'b')      // Jointure avec Book
        ->where('b.id = :bookId')
        ->setParameter('bookId', $book->getId());

        
        return $qb->getQuery()->getResult();
    }

    public function getAllReviews(): array
    {
        return $this->findAll();
    }



}
