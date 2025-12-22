<?php

namespace App\Tests\Factory\Books\Entity;

use App\Domain\Books\Entity\Books;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Books>
 */
final class BooksFactory extends PersistentProxyObjectFactory
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
        return Books::class;
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
            'title' => self::faker()->sentence(3),
            'authors' => self::faker()->name(),
            'publisher' => self::faker()->company(),
            'description' => self::faker()->paragraphs(3, true),
            'pageCount' => self::faker()->numberBetween(100, 800),
            'publishedDate' => self::faker()->dateTimeBetween('-50 years', 'now'),
            'thumbnail' => self::faker()->imageUrl(128, 192, 'books'),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Books $books): void {})
        ;
    }
}
