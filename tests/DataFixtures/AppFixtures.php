<?php

namespace App\Tests\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Tests\Factory\Books\Entity\BooksFactory;
use App\Tests\Factory\Users\Entity\UsersFactory;
use App\Tests\Factory\Reviews\Entity\ReviewsFactory;
use App\Tests\Factory\UserBooks\Entity\UserBooksFactory;
use App\Tests\Factory\ReviewComments\Entity\ReviewCommentsFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 1. Créer les entités de base (sans dépendances)
        $users = UsersFactory::createMany(5);
        $books = BooksFactory::createMany(10);

        // 2. Créer les UserBooks (dépend de Users et Books)
        $userBooks = [];
        foreach ($users as $user) {
            // Chaque utilisateur a entre 1 et 4 livres
            $userBookCount = random_int(1, 4);
            $selectedBooks = array_rand(array_flip(range(0, count($books) - 1)), $userBookCount);

            foreach ((array) $selectedBooks as $bookIndex) {
                $userBooks[] = UserBooksFactory::createOne([
                    'user' => $user,
                    'book' => $books[$bookIndex],
                ]);
            }
        }

        // 3. Créer les Reviews (dépend de UserBooks) - environ 50% des UserBooks ont une review
        $reviews = [];
        foreach ($userBooks as $userBook) {
            if (random_int(0, 1) === 1) {
                $reviews[] = ReviewsFactory::createOne([
                    'userBook' => $userBook,
                ]);
            }
        }

        // 4. Créer les ReviewComments (dépend de Reviews et Users)
        foreach ($reviews as $review) {
            // Chaque review a entre 0 et 3 commentaires
            $commentCount = random_int(0, 3);
            for ($i = 0; $i < $commentCount; $i++) {
                ReviewCommentsFactory::createOne([
                    'review' => $review,
                    'user' => $users[array_rand($users)],
                ]);
            }
        }
    }
}
