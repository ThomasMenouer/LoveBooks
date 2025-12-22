<?php

namespace App\Tests\Factory\Reviews\Entity;

use App\Domain\Reviews\Entity\Reviews;
use App\Tests\Factory\UserBooks\Entity\UserBooksFactory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Reviews>
 */
final class ReviewsFactory extends PersistentProxyObjectFactory
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
        return Reviews::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        $createdAt = \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-1 year', 'now'));

        return [
            'userBook' => UserBooksFactory::randomOrCreate(),
            'content' => self::faker()->paragraphs(self::faker()->numberBetween(2, 5), true),
            'createdAt' => $createdAt,
            'updatedAt' => self::faker()->dateTimeBetween($createdAt->format('Y-m-d'), 'now'),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Reviews $reviews): void {})
        ;
    }
}
