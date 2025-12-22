<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\ReviewComments\Entity\ReviewComments;
use App\Domain\ReviewComments\Repository\ReviewCommentsRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<ReviewComments>
 */
class ReviewCommentsRepository extends ServiceEntityRepository implements ReviewCommentsRepositoryInterface
{
    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, ReviewComments::class);
        $this->em = $em;
    }

    /**
     * Save a review comment
     * @param ReviewComments $comment
     * @return void
     */
    public function saveReviewComment(ReviewComments $comment): void
    {
        $this->em->persist($comment);
        $this->em->flush();
    }

    /**
     * Edit a comment
     * @param ReviewComments $comment
     * @return void
     */
    public function editReviewComment(ReviewComments $comment): void
    {
        $this->em->flush();
    }

    /**
     * Delete a comment
     * @param ReviewComments $comment
     * @return void
     */
    public function deleteReviewComment(ReviewComments $comment): void
    {
        $this->em->remove($comment);
        $this->em->flush();
    }
}
