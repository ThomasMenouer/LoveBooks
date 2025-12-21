<?php


namespace App\Presentation\Api\Provider\UserBooks;

use App\Domain\Users\Entity\Users;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Bundle\SecurityBundle\Security;
use ApiPlatform\Metadata\CollectionOperationInterface;
use App\Presentation\Web\Transformer\BookTransformer;
use App\Presentation\Api\Resource\UserBooks\UserBooksResource;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;

final class UserBooksProvider implements ProviderInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly BookTransformer $bookTransformer,
        private readonly UserBooksRepositoryInterface $userBooksRepository
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var Users $user */
        $user = $this->security->getUser();

        if (!$user) {
            return null;
        }

        if ($operation instanceof CollectionOperationInterface) {
            $userBooks = $user->getUserBooks()->toArray();
            return array_map(fn(UserBooks $ub) => $this->toResource($ub), $userBooks);
        }

        // Pour les opérations GET sur un élément spécifique
        $userBookId = $uriVariables['id'] ?? null;
        if (!$userBookId) {
            return null;
        }

        $userBooks = $user->getUserBooks();
        foreach ($userBooks as $userBook) {
            if ($userBook->getId() === (int)$userBookId) {
                // Vérification de sécurité : l'utilisateur doit être le propriétaire
                if ($userBook->getUser() !== $user) {
                    return null;
                }
                return $this->toResource($userBook);
            }
        }

        return null;
    }

    public function toResource(UserBooks $userBook): UserBooksResource
    {
        $book = $userBook->getBook();

        return new UserBooksResource(
            id: $userBook->getId(),
            bookId: $book->getId(),
            status: $userBook->getStatus()->value,
            pagesRead: $userBook->getPagesRead(),
            isPreferred: $userBook->GetIsPreferred(),
            userRating: $userBook->getUserRating(),
            book: $this->bookTransformer->transformToArray($book),
            review: $userBook->getReview(),
        );
    }
}
