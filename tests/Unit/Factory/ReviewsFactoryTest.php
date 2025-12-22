<?php

namespace App\Tests\Unit\Factory;

use App\Domain\Reviews\Entity\Reviews;
use App\Domain\UserBooks\Entity\UserBooks;
use App\Tests\Factory\Books\Entity\BooksFactory;
use App\Tests\Factory\Users\Entity\UsersFactory;
use App\Tests\Factory\UserBooks\Entity\UserBooksFactory;
use App\Tests\Factory\Reviews\Entity\ReviewsFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * Tests unitaires pour la factory ReviewsFactory.
 *
 * Une Review (critique) est liée à un UserBook, qui lui-même lie un User à un Book.
 * C'est une relation "un utilisateur donne son avis sur un livre de sa bibliothèque".
 *
 * Ces tests vérifient :
 * - La création correcte des reviews
 * - Les relations avec UserBook
 * - La cohérence des dates (createdAt <= updatedAt)
 */
class ReviewsFactoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    /**
     * Test de base : vérifie qu'on peut créer une review.
     *
     * IMPORTANT : On doit d'abord créer les dépendances (User, Book, UserBook)
     * car la Review a besoin d'un UserBook existant.
     */
    public function testCanCreateReview(): void
    {
        // Création des dépendances dans l'ordre hiérarchique
        UsersFactory::createOne();      // 1. Un utilisateur
        BooksFactory::createOne();       // 2. Un livre
        UserBooksFactory::createOne();   // 3. L'association user-livre

        // Maintenant on peut créer la review
        $review = ReviewsFactory::createOne();

        $this->assertInstanceOf(Reviews::class, $review->_real());
    }

    /**
     * Vérifie que chaque review est bien liée à un UserBook.
     * Sans UserBook, on ne sait pas qui a écrit la review ni sur quel livre.
     */
    public function testReviewHasUserBookRelation(): void
    {
        UsersFactory::createOne();
        BooksFactory::createOne();
        UserBooksFactory::createOne();

        $review = ReviewsFactory::createOne();

        // La review doit pointer vers un UserBook valide
        $this->assertInstanceOf(UserBooks::class, $review->getUserBook());
    }

    /**
     * Vérifie que la review a du contenu.
     * Une review vide n'a pas de sens !
     */
    public function testReviewHasContent(): void
    {
        UsersFactory::createOne();
        BooksFactory::createOne();
        UserBooksFactory::createOne();

        $review = ReviewsFactory::createOne();

        $this->assertNotEmpty($review->getContent());
    }

    /**
     * Vérifie que la date de création est dans le passé.
     * On utilise DateTimeImmutable (immuable) plutôt que DateTime (mutable)
     * pour éviter les modifications accidentelles.
     */
    public function testCreatedAtIsInPast(): void
    {
        UsersFactory::createOne();
        BooksFactory::createOne();
        UserBooksFactory::createOne();

        $review = ReviewsFactory::createOne();

        // Vérifie le type et que la date n'est pas dans le futur
        $this->assertInstanceOf(\DateTimeImmutable::class, $review->getCreatedAt());
        $this->assertLessThanOrEqual(new \DateTimeImmutable(), $review->getCreatedAt());
    }

    /**
     * Vérifie que updatedAt >= createdAt.
     *
     * Logique métier : une review ne peut pas être modifiée AVANT d'être créée.
     * On compare les timestamps (secondes depuis 1970) pour éviter les problèmes
     * de comparaison d'objets DateTime.
     */
    public function testUpdatedAtIsAfterOrEqualCreatedAt(): void
    {
        UsersFactory::createOne();
        BooksFactory::createOne();
        UserBooksFactory::createOne();

        $review = ReviewsFactory::createOne();

        // getTimestamp() convertit la date en nombre de secondes
        $this->assertGreaterThanOrEqual(
            $review->getCreatedAt()->getTimestamp(),
            $review->getUpdatedAt()->getTimestamp()
        );
    }

    /**
     * Vérifie qu'on peut créer une review avec un UserBook spécifique.
     * Utile pour tester des scénarios précis (ex: "la review du livre X par l'utilisateur Y").
     */
    public function testCanOverrideWithSpecificUserBook(): void
    {
        // Création d'une chaîne de données spécifiques
        $user = UsersFactory::createOne();
        $book = BooksFactory::createOne(['title' => 'Test Book']);
        $userBook = UserBooksFactory::createOne([
            'user' => $user,
            'book' => $book,
        ]);

        // Création de la review avec des valeurs imposées
        $review = ReviewsFactory::createOne([
            'userBook' => $userBook,
            'content' => 'Great book!',
        ]);

        // Vérifie qu'on peut remonter la chaîne de relations
        // Review -> UserBook -> Book -> title
        $this->assertSame('Test Book', $review->getUserBook()->getBook()->getTitle());
        $this->assertSame('Great book!', $review->getContent());
    }
}
