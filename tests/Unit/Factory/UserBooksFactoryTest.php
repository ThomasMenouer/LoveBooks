<?php

namespace App\Tests\Unit\Factory;

use App\Domain\Books\Entity\Books;
use App\Domain\Users\Entity\Users;
use App\Domain\UserBooks\Entity\UserBooks;
use App\Domain\UserBooks\Enum\Status;
use App\Tests\Factory\Books\Entity\BooksFactory;
use App\Tests\Factory\Users\Entity\UsersFactory;
use App\Tests\Factory\UserBooks\Entity\UserBooksFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * Tests unitaires pour la factory UserBooksFactory.
 *
 * UserBooks est une entité pivot qui lie un User à un Book.
 * Elle représente "un livre dans la bibliothèque d'un utilisateur"
 * avec des infos supplémentaires : statut de lecture, pages lues, note, etc.
 *
 * C'est une table d'association enrichie (pas juste une relation ManyToMany simple).
 *
 * Ces tests vérifient :
 * - Les relations vers User et Book
 * - La validité du statut (enum Status)
 * - La cohérence des données (pages lues, note)
 */
class UserBooksFactoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    /**
     * Test de base : vérifie qu'on peut créer un UserBook.
     * Nécessite un User et un Book existants.
     */
    public function testCanCreateUserBook(): void
    {
        // Création des entités parentes
        UsersFactory::createOne();
        BooksFactory::createOne();

        $userBook = UserBooksFactory::createOne();

        $this->assertInstanceOf(UserBooks::class, $userBook->_real());
    }

    /**
     * Vérifie que chaque UserBook est lié à un utilisateur.
     */
    public function testUserBookHasUserRelation(): void
    {
        UsersFactory::createOne();
        BooksFactory::createOne();

        $userBook = UserBooksFactory::createOne();

        $this->assertInstanceOf(Users::class, $userBook->getUser());
    }

    /**
     * Vérifie que chaque UserBook est lié à un livre.
     */
    public function testUserBookHasBookRelation(): void
    {
        UsersFactory::createOne();
        BooksFactory::createOne();

        $userBook = UserBooksFactory::createOne();

        $this->assertInstanceOf(Books::class, $userBook->getBook());
    }

    /**
     * Vérifie que le statut est une valeur valide de l'enum Status.
     *
     * Les enums PHP 8.1+ permettent de définir un ensemble fini de valeurs possibles.
     * Ex: Status::READING, Status::FINISHED, Status::TO_READ, etc.
     */
    public function testStatusIsValid(): void
    {
        UsersFactory::createOne();
        BooksFactory::createOne();

        $userBook = UserBooksFactory::createOne();

        // assertInstanceOf fonctionne aussi pour les enums
        $this->assertInstanceOf(Status::class, $userBook->getStatus());
    }

    /**
     * Vérifie que le nombre de pages lues est réaliste.
     * Doit être entre 0 (pas encore commencé) et 500 (limite raisonnable).
     */
    public function testPagesReadIsRealistic(): void
    {
        UsersFactory::createOne();
        BooksFactory::createOne();

        $userBook = UserBooksFactory::createOne();

        // Le nombre de pages lues doit être >= 0 et <= 500
        $this->assertGreaterThanOrEqual(0, $userBook->getPagesRead());
        $this->assertLessThanOrEqual(500, $userBook->getPagesRead());
    }

    /**
     * Vérifie qu'on peut créer un UserBook avec des valeurs spécifiques.
     * Utile pour tester des scénarios précis.
     */
    public function testCanOverrideWithSpecificUserAndBook(): void
    {
        // Création avec des valeurs personnalisées
        $user = UsersFactory::createOne(['email' => 'test@example.com']);
        $book = BooksFactory::createOne(['title' => 'Test Book']);

        $userBook = UserBooksFactory::createOne([
            'user' => $user,
            'book' => $book,
            'status' => Status::READING,  // Enum : livre en cours de lecture
        ]);

        // Vérifie que les valeurs sont bien celles qu'on a passées
        $this->assertSame('test@example.com', $userBook->getUser()->getEmail());
        $this->assertSame('Test Book', $userBook->getBook()->getTitle());
        $this->assertSame(Status::READING, $userBook->getStatus());
    }

    /**
     * Vérifie que la note utilisateur (userRating) est optionnelle et valide.
     *
     * - null : l'utilisateur n'a pas encore noté le livre
     * - 1-5 : note sur 5 étoiles
     *
     * On crée 10 UserBooks pour avoir un échantillon statistique
     * (la factory génère des valeurs aléatoires).
     */
    public function testUserRatingIsOptionalAndValid(): void
    {
        // Crée 10 paires user/book distinctes
        $users = UsersFactory::createMany(10);
        $books = BooksFactory::createMany(10);

        // Crée 10 UserBooks (chacun avec un user et book différent)
        $userBooks = [];
        for ($i = 0; $i < 10; $i++) {
            $userBooks[] = UserBooksFactory::createOne([
                'user' => $users[$i],
                'book' => $books[$i],
            ]);
        }

        // Vérifie que chaque note (si elle existe) est entre 1 et 5
        foreach ($userBooks as $userBook) {
            $rating = $userBook->getUserRating();

            // Si la note n'est pas null, elle doit être valide
            if ($rating !== null) {
                $this->assertGreaterThanOrEqual(1, $rating);
                $this->assertLessThanOrEqual(5, $rating);
            }
            // Si null, c'est OK : l'utilisateur n'a simplement pas noté
        }
    }
}
