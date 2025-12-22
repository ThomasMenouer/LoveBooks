<?php

namespace App\Tests\Factory\ReviewComments\Entity;

use App\Tests\Factory\Users\Entity\UsersFactory;

use App\Domain\ReviewComments\Entity\ReviewComments;
use App\Tests\Factory\Reviews\Entity\ReviewsFactory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ReviewComments>
 */
final class ReviewCommentsFactory extends PersistentProxyObjectFactory
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
        return ReviewComments::class;
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
            'review' => ReviewsFactory::randomOrCreate(),
            'user' => UsersFactory::randomOrCreate(),
            'content' => self::faker()->paragraph(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(ReviewComments $reviewComments): void {})
        ;
    }
}
