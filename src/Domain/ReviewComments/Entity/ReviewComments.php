<?php

namespace App\Domain\ReviewComments\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Users\Entity\Users;
use ApiPlatform\Metadata\ApiResource;
use App\Domain\Reviews\Entity\Reviews;
use App\Infrastructure\Persistence\Doctrine\Repository\ReviewCommentsRepository;

#[ApiResource]
#[ORM\Entity(repositoryClass: ReviewCommentsRepository::class)]
class ReviewComments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Reviews::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Reviews $review;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Users $user;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): int
    {

        return $this->id; 
    }

    public function getReview(): Reviews
    {
        return $this->review; 
    }
    public function setReview(Reviews $review): void
    {
        $this->review = $review;
    }

    public function getUser(): Users
    {
        return $this->user;
    }
    public function setUser(Users $user): void
    {
        $this->user = $user;
    }

    public function getContent(): string
    {
        return $this->content;
    }
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
