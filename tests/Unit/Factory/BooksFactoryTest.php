<?php

namespace App\Tests\Unit\Factory;

use App\Domain\Books\Entity\Books;
use App\Tests\Factory\Books\Entity\BooksFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * Tests unitaires pour la factory BooksFactory.
 *
 * Ces tests vérifient que la factory génère des livres valides avec :
 * - Des données cohérentes (titre, auteur, éditeur, description)
 * - Des valeurs réalistes (nombre de pages, date de publication)
 * - La possibilité de surcharger les valeurs par défaut
 *
 * Une Factory (Foundry) est une classe qui génère des objets avec des données
 * aléatoires mais réalistes pour les tests. Cela évite de créer manuellement
 * des objets de test à chaque fois.
 */
class BooksFactoryTest extends KernelTestCase
{
    // Trait Factories : active les fonctionnalités Foundry
    use Factories;

    // Trait ResetDatabase : remet la BDD à zéro avant chaque test
    use ResetDatabase;

    /**
     * Test de base : vérifie qu'on peut créer un livre et que tous
     * les champs obligatoires sont remplis.
     */
    public function testCanCreateBook(): void
    {
        // createOne() : crée et persiste un seul objet en base
        $book = BooksFactory::createOne();

        // _real() : récupère l'entité Doctrine réelle (pas le proxy Foundry)
        $this->assertInstanceOf(Books::class, $book->_real());

        // Vérifie que les champs essentiels ne sont pas vides
        $this->assertNotEmpty($book->getTitle());
        $this->assertNotEmpty($book->getAuthors());
        $this->assertNotEmpty($book->getPublisher());
        $this->assertNotEmpty($book->getDescription());
    }

    /**
     * Vérifie que le nombre de pages généré est réaliste.
     * Un livre fait généralement entre 100 et 800 pages.
     */
    public function testPageCountIsRealistic(): void
    {
        $book = BooksFactory::createOne();

        // Le nombre de pages doit être dans une plage raisonnable
        $this->assertGreaterThanOrEqual(100, $book->getPageCount());
        $this->assertLessThanOrEqual(800, $book->getPageCount());
    }

    /**
     * Vérifie que la date de publication est dans le passé.
     * On ne peut pas avoir un livre publié dans le futur !
     */
    public function testPublishedDateIsInPast(): void
    {
        $book = BooksFactory::createOne();

        // assertLessThanOrEqual avec des dates : vérifie que la date est <= aujourd'hui
        $this->assertLessThanOrEqual(new \DateTime(), $book->getPublishedDate());
    }

    /**
     * Vérifie que chaque livre a une image de couverture (thumbnail).
     */
    public function testThumbnailIsSet(): void
    {
        $book = BooksFactory::createOne();

        // La couverture ne doit être ni null ni vide
        $this->assertNotNull($book->getThumbnail());
        $this->assertNotEmpty($book->getThumbnail());
    }

    /**
     * Vérifie qu'on peut surcharger les valeurs par défaut de la factory.
     * C'est utile quand on veut tester avec des données spécifiques.
     */
    public function testCanOverrideDefaults(): void
    {
        // On passe un tableau avec les valeurs qu'on veut imposer
        $book = BooksFactory::createOne([
            'title' => 'Mon Livre',
            'authors' => 'John Doe',
            'pageCount' => 250,
        ]);

        // assertSame : vérifie l'égalité stricte (valeur ET type)
        $this->assertSame('Mon Livre', $book->getTitle());
        $this->assertSame('John Doe', $book->getAuthors());
        $this->assertSame(250, $book->getPageCount());
    }

    /**
     * Vérifie qu'on peut créer plusieurs livres en une seule fois.
     * Utile pour tester des fonctionnalités qui manipulent des collections.
     */
    public function testCanCreateMultipleBooks(): void
    {
        // createMany(N) : crée et persiste N objets en base
        $books = BooksFactory::createMany(5);

        $this->assertCount(5, $books);
    }
}
