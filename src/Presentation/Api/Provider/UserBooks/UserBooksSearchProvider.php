<?php

namespace App\Presentation\Api\Provider\UserBooks;

use App\Domain\Users\Entity\Users;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Presentation\Web\Transformer\BookTransformer;
use App\Application\Users\UseCase\SearchAbookUseCase;
use App\Presentation\Api\Resource\UserBooks\UserBooksSearchResource;

final class UserBooksSearchProvider implements ProviderInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly RequestStack $requestStack,
        private readonly BookTransformer $bookTransformer,
        private readonly SearchAbookUseCase $searchAbookUseCase
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var Users $user */
        $user = $this->security->getUser();

        if (!$user) {
            return [];
        }

        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return [];
        }

        $query = $request->query->get('query', '');

        $filters = [];
        if (!empty($query)) {
            $filters['query'] = $query;
        }

        try {
            $results = $this->searchAbookUseCase->getSearchBook($user, $filters);
            return array_map(fn(UserBooks $ub) => $this->toResource($ub), $results);
        } catch (\Throwable $th) {
            return [
                'error' => 'An error occurred during the search.',
                'message' => $th->getMessage()
            ];
        }
    }

    public function toResource(UserBooks $userBook): UserBooksSearchResource
    {
        $book = $userBook->getBook();

        return new UserBooksSearchResource(
            id: $userBook->getId(),
            bookId: $book->getId(),
            status: $userBook->getStatus()->value,
            pagesRead: $userBook->getPagesRead(),
            isPreferred: $userBook->GetIsPreferred(),
            userRating: $userBook->getUserRating(),
            book: $this->bookTransformer->transformToArray($book)
        );
    }
}
