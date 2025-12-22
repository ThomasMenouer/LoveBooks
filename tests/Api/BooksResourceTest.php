<?php

namespace App\Tests\Api;

use App\Tests\Factory\Users\Entity\UsersFactory;
use App\Tests\Factory\Books\Entity\BooksFactory;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * Tests fonctionnels pour l'API Books.
 *
 * Ces tests vérifient le comportement des endpoints REST de l'API Books :
 * - GET /api/books_resources (liste)
 * - GET /api/books_resources/{id} (détail)
 *
 * Note : Les routes sont /api/books_resources (pas /api/books)
 * car API Platform génère les routes à partir du nom de la classe Resource.
 *
 * ApiTestCase : classe de base d'API Platform pour les tests fonctionnels.
 * Elle fournit un client HTTP et des assertions spécifiques aux APIs REST.
 */
class BooksResourceTest extends ApiTestCase
{
    // Traits Foundry pour les fixtures de test
    use Factories;
    use ResetDatabase;

    /**
     * Test : un utilisateur non authentifié ne peut pas accéder à l'API.
     *
     * L'API est protégée par security: "is_granted('ROLE_USER')"
     * donc un visiteur anonyme doit recevoir une erreur 401 Unauthorized.
     */
    public function testGetCollectionRequiresAuthentication(): void
    {
        // static::createClient() crée un client HTTP pour tester l'API
        $client = static::createClient();

        // Requête GET sans authentification
        $client->request('GET', '/api/books_resources');

        // 401 = Unauthorized (non authentifié)
        $this->assertResponseStatusCodeSame(401);
    }

    /**
     * Test : un utilisateur authentifié peut récupérer la liste des livres.
     */
    public function testGetCollectionAsAuthenticatedUser(): void
    {
        $client = static::createClient();

        // Création d'un utilisateur de test
        $user = UsersFactory::createOne();

        // Création de quelques livres
        BooksFactory::createMany(3);

        // loginUser() : simule une connexion (session) pour ce client
        $client->loginUser($user->_real());

        // Requête GET authentifiée
        $response = $client->request('GET', '/api/books_resources');

        // 200 = OK (succès)
        $this->assertResponseStatusCodeSame(200);

        // assertResponseIsSuccessful : vérifie que le code est 2xx
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test : récupération d'un livre par son ID.
     */
    public function testGetSingleBook(): void
    {
        $client = static::createClient();
        $user = UsersFactory::createOne();

        // Crée un livre avec des données spécifiques pour vérifier la réponse
        $book = BooksFactory::createOne([
            'title' => 'Le Petit Prince',
            'authors' => 'Antoine de Saint-Exupéry',
        ]);

        $client->loginUser($user->_real());

        // Requête GET sur un livre spécifique
        $response = $client->request('GET', '/api/books_resources/' . $book->getId());

        $this->assertResponseStatusCodeSame(200);

        // Vérifie que les données du livre sont correctes
        $this->assertJsonContains([
            'title' => 'Le Petit Prince',
            'authors' => 'Antoine de Saint-Exupéry',
        ]);
    }

    /**
     * Test : récupération d'un livre inexistant renvoie 404.
     */
    public function testGetNonExistentBookReturns404(): void
    {
        $client = static::createClient();
        $user = UsersFactory::createOne();

        $client->loginUser($user->_real());

        // ID 99999 n'existe probablement pas
        $client->request('GET', '/api/books_resources/99999');

        // 404 = Not Found (ressource non trouvée)
        $this->assertResponseStatusCodeSame(404);
    }
}
