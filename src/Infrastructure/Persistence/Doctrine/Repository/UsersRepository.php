<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use Dom\Entity;
use  App\Domain\Users\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Users\Repository\UsersRepositoryInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @extends ServiceEntityRepository<Users>
 */
class UsersRepository extends ServiceEntityRepository implements UsersRepositoryInterface, PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * Deletes a user from the repository.
     * @return void
     */
    public function delete(Users $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * Saves a user to the repository.
     * @return void
     */
    public function save(Users $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Retrieves a user by their ID.
     * @return Users|null
     */
    public function getUserById(int $id): ?Users
    {
        return $this->find($id);
    }

    /**
     * Retrieves a user by their email address.
     * @return Users|null
     */
    public function getUserByEmail(string $email): ?Users
    {
        return $this->findOneBy(['email' => $email]);
    }


    /**
     * Retrieves all users from the repository.
     * @return Users[]
     */
    public function getAllUsers(): array
    {
        return $this->findAll();
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}
