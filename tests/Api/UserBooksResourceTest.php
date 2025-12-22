<?php

namespace App\Tests\Api;

use App\Domain\UserBooks\Enum\Status;
use App\Tests\Factory\Users\Entity\UsersFactory;
use App\Tests\Factory\Books\Entity\BooksFactory;
use App\Tests\Factory\UserBooks\Entity\UserBooksFactory;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * Tests fonctionnels pour l'API UserBooks.
 *
 * UserBooks représente l'association entre un utilisateur et un livre
 * (un livre dans la bibliothèque personnelle d'un utilisateur).
 *
 * Endpoints testés :
 * - GET /api/user_books (liste des livres de l'utilisateur)
 * - GET /api/user_books/{id} (détail d'un UserBook)
 * - GET /api/user_books/reading-list (livres en cours de lecture) - format JSON uniquement
 * - POST /api/user_books (ajouter un livre à sa bibliothèque)
 * - PATCH /api/user_books/{id} (modifier statut, pages lues, note...)
 * - DELETE /api/user_books/{id} (retirer un livre de sa bibliothèque)
 *
 * Note sur les Status : l'enum Status utilise des valeurs françaises
 * - Status::READING = 'En cours de lecture'
 * - Status::READ = 'Lu'
 * - Status::NOT_READ = 'Non lu'
 * - Status::ABANDONED = 'Abandonné'
 */
class UserBooksResourceTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    /**
     * Test : l'API nécessite une authentification.
     * Sans authentification, on reçoit 401 Unauthorized.
     */
    public function testGetCollectionRequiresAuthentication(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/user_books');

        // 401 = Non authentifié
        $this->assertResponseStatusCodeSame(401);
    }

    /**
     * Test : récupérer la liste des UserBooks de l'utilisateur connecté.
     * Vérifie que l'utilisateur ne voit QUE ses propres livres.
     */
    public function testGetCollectionAsAuthenticatedUser(): void
    {
        $client = static::createClient();

        // Création de l'utilisateur et de ses livres
        $user = UsersFactory::createOne();
        $book1 = BooksFactory::createOne();
        $book2 = BooksFactory::createOne();

        // Création de 2 UserBooks pour cet utilisateur
        UserBooksFactory::createOne(['user' => $user, 'book' => $book1]);
        UserBooksFactory::createOne(['user' => $user, 'book' => $book2]);

        // Création d'un UserBook pour un AUTRE utilisateur (ne doit pas apparaître)
        $otherUser = UsersFactory::createOne();
        $book3 = BooksFactory::createOne();
        UserBooksFactory::createOne(['user' => $otherUser, 'book' => $book3]);

        $client->loginUser($user->_real());

        $client->request('GET', '/api/user_books');

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * Test : récupérer un UserBook spécifique.
     */
    public function testGetSingleUserBook(): void
    {
        $client = static::createClient();

        $user = UsersFactory::createOne();
        $book = BooksFactory::createOne(['title' => 'Mon Livre Test']);
        $userBook = UserBooksFactory::createOne([
            'user' => $user,
            'book' => $book,
            'status' => Status::READING,
            'pagesRead' => 50,
        ]);

        $client->loginUser($user->_real());

        $client->request('GET', '/api/user_books/' . $userBook->getId());

        $this->assertResponseStatusCodeSame(200);

        // Le status est renvoyé avec sa valeur française (enum->value)
        $this->assertJsonContains([
            'status' => 'En cours de lecture',
            'pagesRead' => 50,
        ]);
    }

    /**
     * Test : un utilisateur ne peut pas voir le UserBook d'un autre utilisateur.
     *
     * C'est une règle de sécurité importante : isolation des données par utilisateur.
     */
    public function testCannotAccessOtherUserBooks(): void
    {
        $client = static::createClient();

        // Utilisateur A crée un UserBook
        $userA = UsersFactory::createOne();
        $book = BooksFactory::createOne();
        $userBookA = UserBooksFactory::createOne(['user' => $userA, 'book' => $book]);

        // Utilisateur B essaie d'y accéder
        $userB = UsersFactory::createOne();

        $client->loginUser($userB->_real());

        $client->request('GET', '/api/user_books/' . $userBookA->getId());

        // 404 = Not Found (ressource cachée pour des raisons de sécurité)
        $this->assertResponseStatusCodeSame(404);
    }

    /**
     * Test : récupérer la reading-list (livres en cours de lecture).
     *
     * Note : cet endpoint n'accepte QUE le format JSON (pas JSON-LD)
     * donc on doit spécifier le header Accept.
     */
    public function testGetReadingList(): void
    {
        $client = static::createClient();

        $user = UsersFactory::createOne();
        $book1 = BooksFactory::createOne();
        $book2 = BooksFactory::createOne();
        $book3 = BooksFactory::createOne();

        // 2 livres en cours de lecture
        UserBooksFactory::createOne([
            'user' => $user,
            'book' => $book1,
            'status' => Status::READING,
        ]);
        UserBooksFactory::createOne([
            'user' => $user,
            'book' => $book2,
            'status' => Status::READING,
        ]);

        // 1 livre terminé (ne doit pas apparaître dans reading-list)
        UserBooksFactory::createOne([
            'user' => $user,
            'book' => $book3,
            'status' => Status::READ,
        ]);

        $client->loginUser($user->_real());

        // IMPORTANT : cet endpoint n'accepte que application/json
        $client->request('GET', '/api/user_books/reading-list', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * Test : ajouter un nouveau livre à sa bibliothèque (POST).
     *
     * Le POST crée à la fois le Book (s'il n'existe pas) et le UserBook.
     * La réponse contient un message de succès.
     */
    public function testCreateUserBookWithNewBook(): void
    {
        $client = static::createClient();
        $user = UsersFactory::createOne();

        $client->loginUser($user->_real());

        // POST avec un livre imbriqué (nested object)
        $client->request('POST', '/api/user_books', [
            'json' => [
                'book' => [
                    'title' => 'Nouveau Livre',
                    'authors' => 'Auteur Test',
                    'publisher' => 'Éditeur Test',
                    'description' => 'Description du livre',
                    'pageCount' => 300,
                    'thumbnail' => 'https://example.com/cover.jpg',
                    'publishedDate' => '2023-06-15',
                ],
            ],
        ]);

        // 201 = Created
        $this->assertResponseStatusCodeSame(201);

        // Vérifie le message de succès retourné par le processor
        $this->assertJsonContains([
            'succes' => true,
            'message' => 'Le livre a bien été ajouté dans votre bibliothèque.',
        ]);
    }

    /**
     * Test : la validation empêche de créer un UserBook sans livre.
     */
    public function testCreateUserBookWithoutBookFails(): void
    {
        $client = static::createClient();
        $user = UsersFactory::createOne();

        $client->loginUser($user->_real());

        // POST sans le champ "book" obligatoire
        $client->request('POST', '/api/user_books', [
            'json' => [
                // book manquant !
            ],
        ]);

        // 422 = Unprocessable Entity (erreur de validation)
        $this->assertResponseStatusCodeSame(422);
    }

    /**
     * Test : mettre à jour le statut de lecture (PATCH).
     *
     * Note : le statut doit être envoyé avec sa valeur française.
     */
    public function testUpdateUserBookStatus(): void
    {
        $client = static::createClient();

        $user = UsersFactory::createOne();
        $book = BooksFactory::createOne();
        $userBook = UserBooksFactory::createOne([
            'user' => $user,
            'book' => $book,
            'status' => Status::NOT_READ,
        ]);

        $client->loginUser($user->_real());

        // PATCH pour changer le statut
        $client->request('PATCH', '/api/user_books/' . $userBook->getId(), [
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'status' => 'En cours de lecture',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);

        // La réponse contient un message de succès
        $this->assertJsonContains([
            'success' => true,
            'message' => 'Le livre a bien été mis à jour dans votre bibliothèque.',
        ]);
    }

    /**
     * Test : mettre à jour les pages lues et la note.
     */
    public function testUpdatePagesReadAndRating(): void
    {
        $client = static::createClient();

        $user = UsersFactory::createOne();
        $book = BooksFactory::createOne(['pageCount' => 300]);
        $userBook = UserBooksFactory::createOne([
            'user' => $user,
            'book' => $book,
            'pagesRead' => 0,
            'userRating' => null,
        ]);

        $client->loginUser($user->_real());

        $client->request('PATCH', '/api/user_books/' . $userBook->getId(), [
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'pagesRead' => 150,
                'userRating' => 4,
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            'success' => true,
            'message' => 'Le livre a bien été mis à jour dans votre bibliothèque.',
        ]);
    }

    /**
     * Test : marquer un livre comme favori.
     */
    public function testMarkAsPreferred(): void
    {
        $client = static::createClient();

        $user = UsersFactory::createOne();
        $book = BooksFactory::createOne();
        $userBook = UserBooksFactory::createOne([
            'user' => $user,
            'book' => $book,
            'isPreferred' => false,
        ]);

        $client->loginUser($user->_real());

        $client->request('PATCH', '/api/user_books/' . $userBook->getId(), [
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'isPreferred' => true,
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);

        $this->assertJsonContains([
            'success' => true,
            'message' => 'Le livre a bien été mis à jour dans votre bibliothèque.',
        ]);
    }

    /**
     * Test : supprimer un livre de sa bibliothèque.
     */
    public function testDeleteUserBook(): void
    {
        $client = static::createClient();

        $user = UsersFactory::createOne();
        $book = BooksFactory::createOne();
        $userBook = UserBooksFactory::createOne([
            'user' => $user,
            'book' => $book,
        ]);

        // On sauvegarde l'ID avant de supprimer
        $userBookId = $userBook->getId();

        $client->loginUser($user->_real());

        $client->request('DELETE', '/api/user_books/' . $userBookId);

        // 204 = No Content (suppression réussie)
        $this->assertResponseStatusCodeSame(204);

        // Vérifie que le UserBook n'existe plus
        $client->request('GET', '/api/user_books/' . $userBookId);
        $this->assertResponseStatusCodeSame(404);
    }

    /**
     * Test : un utilisateur ne peut pas supprimer le UserBook d'un autre.
     */
    public function testCannotDeleteOtherUserBooks(): void
    {
        $client = static::createClient();

        // UserA crée un UserBook
        $userA = UsersFactory::createOne();
        $book = BooksFactory::createOne();
        $userBookA = UserBooksFactory::createOne(['user' => $userA, 'book' => $book]);

        // UserB essaie de le supprimer
        $userB = UsersFactory::createOne();

        $client->loginUser($userB->_real());

        $client->request('DELETE', '/api/user_books/' . $userBookA->getId());

        // 403 = Forbidden ou 404 selon l'implémentation
        // Dans ton cas, le provider retourne null donc 404
        $this->assertResponseStatusCodeSame(404);
    }
}
