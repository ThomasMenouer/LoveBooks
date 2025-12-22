<?php

namespace App\Tests\Factory\UserBooks\Entity;

use App\Domain\UserBooks\Enum\Status;
use App\Domain\UserBooks\Entity\UserBooks;
use App\Tests\Factory\Books\Entity\BooksFactory;
use App\Tests\Factory\Users\Entity\UsersFactory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<UserBooks>
 */
final class UserBooksFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return UserBooks::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'user' => UsersFactory::randomOrCreate(),
            'book' => BooksFactory::randomOrCreate(),
            'status' => self::faker()->randomElement(Status::cases()),
            'pagesRead' => self::faker()->numberBetween(0, 500),
            'isPreferred' => self::faker()->boolean(20),
            'userRating' => self::faker()->optional(0.7)->numberBetween(1, 5),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(UserBooks $userBooks): void {})
        ;
    }
}
