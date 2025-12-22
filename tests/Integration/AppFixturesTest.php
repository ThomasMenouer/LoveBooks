<?php

namespace App\Tests\Integration;

use App\Domain\Books\Entity\Books;
use App\Domain\ReviewComments\Entity\ReviewComments;
use App\Domain\Reviews\Entity\Reviews;
use App\Domain\UserBooks\Entity\UserBooks;
use App\Domain\Users\Entity\Users;
use App\Tests\DataFixtures\AppFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * Test d'intégration pour vérifier que les fixtures de l'application fonctionnent correctement.
 *
 * Ce test s'assure que :
 * - Les fixtures se chargent sans erreur
 * - Les entités sont créées avec les bonnes quantités
 * - Les relations entre entités sont valides
 * - Aucune entité orpheline n'existe
 *
 * Hérite de KernelTestCase : permet d'accéder au container Symfony et aux services (comme Doctrine)
 */
class AppFixturesTest extends KernelTestCase
{
    // Trait Factories : permet d'utiliser les factories Foundry pour créer des objets de test
    use Factories;

    // Trait ResetDatabase : réinitialise la base de données avant chaque test
    // Garantit que chaque test démarre avec une BDD propre et vide
    use ResetDatabase;

    // L'EntityManager de Doctrine pour interagir avec la base de données
    private EntityManagerInterface $entityManager;

    /**
     * Méthode exécutée AVANT chaque test.
     * Configure l'environnement de test.
     */
    protected function setUp(): void
    {
        // Démarre le kernel Symfony (charge la configuration, les services, etc.)
        self::bootKernel();

        // Récupère l'EntityManager depuis le container de services
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    /**
     * Test basique : vérifie que les fixtures se chargent sans lever d'exception.
     * Si le code arrive jusqu'à assertTrue(true), c'est que tout s'est bien passé.
     */
    public function testFixturesLoadSuccessfully(): void
    {
        $fixture = new AppFixtures();
        $fixture->load($this->entityManager);

        // Si on arrive ici sans exception, le test passe
        $this->assertTrue(true);
    }

    /**
     * Vérifie que exactement 5 utilisateurs sont créés par les fixtures.
     */
    public function testUsersAreCreated(): void
    {
        $fixture = new AppFixtures();
        $fixture->load($this->entityManager);

        // Récupère tous les utilisateurs en base
        $users = $this->entityManager->getRepository(Users::class)->findAll();

        // assertCount(expected, actual) : vérifie que le tableau contient exactement N éléments
        $this->assertCount(5, $users);
    }

    /**
     * Vérifie que exactement 10 livres sont créés par les fixtures.
     */
    public function testBooksAreCreated(): void
    {
        $fixture = new AppFixtures();
        $fixture->load($this->entityManager);

        $books = $this->entityManager->getRepository(Books::class)->findAll();

        $this->assertCount(10, $books);
    }

    /**
     * Vérifie que des UserBooks (association utilisateur-livre) sont créés.
     * Le nombre doit être entre 1 et 20 (car chaque user a 1-4 livres, et il y a 5 users).
     */
    public function testUserBooksAreCreated(): void
    {
        $fixture = new AppFixtures();
        $fixture->load($this->entityManager);

        $userBooks = $this->entityManager->getRepository(UserBooks::class)->findAll();

        // assertGreaterThan(min, actual) : vérifie que actual > min
        $this->assertGreaterThan(0, count($userBooks));

        // assertLessThanOrEqual(max, actual) : vérifie que actual <= max
        $this->assertLessThanOrEqual(20, count($userBooks));
    }

    /**
     * Vérifie que chaque UserBook a des relations valides vers User et Book.
     * S'assure que les clés étrangères sont correctement définies.
     */
    public function testUserBooksHaveValidRelations(): void
    {
        $fixture = new AppFixtures();
        $fixture->load($this->entityManager);

        $userBooks = $this->entityManager->getRepository(UserBooks::class)->findAll();

        foreach ($userBooks as $userBook) {
            // assertInstanceOf : vérifie que l'objet est bien du type attendu
            // Ici on s'assure que getUser() retourne bien un Users et getBook() un Books
            $this->assertInstanceOf(Users::class, $userBook->getUser());
            $this->assertInstanceOf(Books::class, $userBook->getBook());
        }
    }

    /**
     * Vérifie que chaque Review est correctement liée à un UserBook
     * et possède un contenu non vide.
     */
    public function testReviewsHaveValidRelations(): void
    {
        $fixture = new AppFixtures();
        $fixture->load($this->entityManager);

        $reviews = $this->entityManager->getRepository(Reviews::class)->findAll();

        foreach ($reviews as $review) {
            // Vérifie la relation vers UserBook
            $this->assertInstanceOf(UserBooks::class, $review->getUserBook());

            // assertNotEmpty : vérifie que la valeur n'est pas vide (null, "", [], etc.)
            $this->assertNotEmpty($review->getContent());
        }
    }

    /**
     * Vérifie que chaque commentaire de review a :
     * - Une relation valide vers la review parente
     * - Une relation valide vers l'utilisateur auteur
     * - Un contenu non vide
     */
    public function testReviewCommentsHaveValidRelations(): void
    {
        $fixture = new AppFixtures();
        $fixture->load($this->entityManager);

        $comments = $this->entityManager->getRepository(ReviewComments::class)->findAll();

        foreach ($comments as $comment) {
            $this->assertInstanceOf(Reviews::class, $comment->getReview());
            $this->assertInstanceOf(Users::class, $comment->getUser());
            $this->assertNotEmpty($comment->getContent());
        }
    }

    /**
     * Vérifie que chaque utilisateur possède entre 1 et 4 livres dans sa bibliothèque.
     * C'est une règle métier définie dans les fixtures.
     */
    public function testEachUserHasAtLeastOneBook(): void
    {
        $fixture = new AppFixtures();
        $fixture->load($this->entityManager);

        $users = $this->entityManager->getRepository(Users::class)->findAll();

        foreach ($users as $user) {
            // findBy(['user' => $user]) : trouve tous les UserBooks pour cet utilisateur
            $userBooks = $this->entityManager->getRepository(UserBooks::class)->findBy(['user' => $user]);

            // Chaque user doit avoir entre 1 et 4 livres
            $this->assertGreaterThanOrEqual(1, count($userBooks));
            $this->assertLessThanOrEqual(4, count($userBooks));
        }
    }

    /**
     * Test critique : vérifie qu'aucune entité n'est orpheline.
     * Une entité orpheline serait une entité dont les relations pointent vers des objets
     * qui n'existent pas en base (ID null = pas encore persisté).
     *
     * Ce test garantit l'intégrité référentielle des données.
     */
    public function testNoOrphanedEntities(): void
    {
        $fixture = new AppFixtures();
        $fixture->load($this->entityManager);

        // Vérifie que chaque UserBook a un User et un Book avec un ID valide
        $userBooks = $this->entityManager->getRepository(UserBooks::class)->findAll();
        foreach ($userBooks as $userBook) {
            // assertNotNull : vérifie que la valeur n'est pas null
            // Un ID null signifierait que l'entité n'est pas persistée en base
            $this->assertNotNull($userBook->getUser()->getId());
            $this->assertNotNull($userBook->getBook()->getId());
        }

        // Vérifie que chaque Review a un UserBook avec un ID valide
        $reviews = $this->entityManager->getRepository(Reviews::class)->findAll();
        foreach ($reviews as $review) {
            $this->assertNotNull($review->getUserBook()->getId());
        }

        // Vérifie que chaque commentaire a une Review et un User avec des IDs valides
        $comments = $this->entityManager->getRepository(ReviewComments::class)->findAll();
        foreach ($comments as $comment) {
            $this->assertNotNull($comment->getReview()->getId());
            $this->assertNotNull($comment->getUser()->getId());
        }
    }
}
