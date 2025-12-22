<?php

namespace App\Tests\Unit\Factory;

use App\Domain\Reviews\Entity\Reviews;
use App\Domain\Users\Entity\Users;
use App\Domain\ReviewComments\Entity\ReviewComments;
use App\Tests\Factory\Books\Entity\BooksFactory;
use App\Tests\Factory\Users\Entity\UsersFactory;
use App\Tests\Factory\UserBooks\Entity\UserBooksFactory;
use App\Tests\Factory\Reviews\Entity\ReviewsFactory;
use App\Tests\Factory\ReviewComments\Entity\ReviewCommentsFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * Tests unitaires pour la factory ReviewCommentsFactory.
 *
 * Un ReviewComment est un commentaire laissé par un utilisateur sur une review.
 * C'est le niveau le plus profond de la hiérarchie :
 * User -> UserBook -> Review -> ReviewComment
 *
 * Ces tests vérifient :
 * - La création correcte des commentaires
 * - Les relations vers Review et User (l'auteur du commentaire)
 * - La possibilité d'avoir plusieurs commentaires sur une même review
 */
class ReviewCommentsFactoryTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    /**
     * Test de base : vérifie qu'on peut créer un commentaire.
     *
     * On doit créer toute la chaîne de dépendances avant :
     * User -> Book -> UserBook -> Review -> puis enfin le Comment
     */
    public function testCanCreateReviewComment(): void
    {
        // Création de toutes les dépendances
        UsersFactory::createOne();
        BooksFactory::createOne();
        UserBooksFactory::createOne();
        ReviewsFactory::createOne();

        // Maintenant on peut créer le commentaire
        $comment = ReviewCommentsFactory::createOne();

        $this->assertInstanceOf(ReviewComments::class, $comment->_real());
    }

    /**
     * Vérifie que le commentaire est bien lié à une review.
     * Un commentaire sans review parente n'a pas de sens.
     */
    public function testCommentHasReviewRelation(): void
    {
        UsersFactory::createOne();
        BooksFactory::createOne();
        UserBooksFactory::createOne();
        ReviewsFactory::createOne();

        $comment = ReviewCommentsFactory::createOne();

        $this->assertInstanceOf(Reviews::class, $comment->getReview());
    }

    /**
     * Vérifie que le commentaire a un auteur (User).
     *
     * Note : l'auteur du commentaire peut être différent de l'auteur de la review !
     * C'est le principe des commentaires : n'importe qui peut commenter.
     */
    public function testCommentHasUserRelation(): void
    {
        UsersFactory::createOne();
        BooksFactory::createOne();
        UserBooksFactory::createOne();
        ReviewsFactory::createOne();

        $comment = ReviewCommentsFactory::createOne();

        $this->assertInstanceOf(Users::class, $comment->getUser());
    }

    /**
     * Vérifie que le commentaire a du contenu.
     * Un commentaire vide n'apporte rien !
     */
    public function testCommentHasContent(): void
    {
        UsersFactory::createOne();
        BooksFactory::createOne();
        UserBooksFactory::createOne();
        ReviewsFactory::createOne();

        $comment = ReviewCommentsFactory::createOne();

        $this->assertNotEmpty($comment->getContent());
    }

    /**
     * Vérifie que le commentaire a une date de création.
     * Utilise DateTimeImmutable pour l'immutabilité.
     */
    public function testCommentHasCreatedAt(): void
    {
        UsersFactory::createOne();
        BooksFactory::createOne();
        UserBooksFactory::createOne();
        ReviewsFactory::createOne();

        $comment = ReviewCommentsFactory::createOne();

        $this->assertInstanceOf(\DateTimeImmutable::class, $comment->getCreatedAt());
    }

    /**
     * Vérifie qu'on peut créer un commentaire avec une review et un auteur spécifiques.
     *
     * Scénario : "Autre Utilisateur" commente la review écrite par "Commentateur".
     * Cela simule le cas où quelqu'un répond à la critique d'un autre.
     */
    public function testCanOverrideWithSpecificReviewAndUser(): void
    {
        // L'utilisateur qui a écrit la review
        $user = UsersFactory::createOne(['name' => 'Commentateur']);
        $book = BooksFactory::createOne();
        $userBook = UserBooksFactory::createOne(['user' => $user, 'book' => $book]);
        $review = ReviewsFactory::createOne(['userBook' => $userBook]);

        // Un autre utilisateur qui va commenter
        $otherUser = UsersFactory::createOne(['name' => 'Autre Utilisateur']);

        // Le commentaire est écrit par otherUser sur la review de user
        $comment = ReviewCommentsFactory::createOne([
            'review' => $review,
            'user' => $otherUser,
            'content' => 'Super review!',
        ]);

        // Vérifie que c'est bien l'autre utilisateur qui a commenté
        $this->assertSame('Autre Utilisateur', $comment->getUser()->getName());
        $this->assertSame('Super review!', $comment->getContent());
    }

    /**
     * Vérifie qu'une review peut avoir plusieurs commentaires.
     *
     * C'est une relation OneToMany : une review peut avoir N commentaires.
     * Chaque commentaire référence la même review parente.
     */
    public function testCanCreateMultipleCommentsOnSameReview(): void
    {
        // Crée 3 utilisateurs (pour avoir des auteurs différents)
        UsersFactory::createMany(3);
        BooksFactory::createOne();
        UserBooksFactory::createOne();
        $review = ReviewsFactory::createOne();

        // Crée 3 commentaires sur la même review
        $comments = [];
        for ($i = 0; $i < 3; $i++) {
            $comments[] = ReviewCommentsFactory::createOne(['review' => $review]);
        }

        // Vérifie qu'on a bien 3 commentaires
        $this->assertCount(3, $comments);

        // Vérifie que tous les commentaires pointent vers la même review
        foreach ($comments as $comment) {
            $this->assertSame($review->getId(), $comment->getReview()->getId());
        }
    }
}
