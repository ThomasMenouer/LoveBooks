<?php


namespace App\Presentation\Api\Provider\UserBooks;

use App\Domain\Users\Entity\Users;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Bundle\SecurityBundle\Security;
use ApiPlatform\Metadata\CollectionOperationInterface;
use App\Presentation\Api\Resource\Books\BooksResource;
use App\Application\Users\UseCase\GetReadingListUserUseCase;
use App\Application\Users\UseCase\SearchAbookUseCase;
use App\Presentation\Api\Resource\UserBooks\UserBooksResource;
use App\Presentation\Web\Transformer\UserBooksTransformer;


final class UserBooksProvider implements ProviderInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly GetReadingListUserUseCase $getReadingListUserUseCase,
        private readonly UserBooksTransformer $transformer,
        private readonly SearchAbookUseCase $searchABooksUseCase,
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var Users $user */
        $user = $this->security->getUser();

        if (!$user) {
            return null;
        }

        //Récupération de la reading list
        if ($operation->getName() === 'reading_list') {

            $currentlyReading = $this->getReadingListUserUseCase->getReadingList($user);

            $data = $this->transformer->transformMany($currentlyReading);

            return $data;
        }

        // Recherche un livre de l'utilisateur
        if($operation->getNAme() === 'search_user_books') {

            $filters = [];

            if(!empty($context['filters']['titre'])) {
                $filters['query'] = $context['filters']['titre'];
            }

            $books = $this->searchABooksUseCase->getSearchBook($user, $filters);

            return $this->transformer->transformMany($books);
        }
        
        // Pour les opérations GET sur une collection
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

        // getGlobalRating() renvoie un array ['rating' => float, 'count' => int] ou null
        $globalRatingData = $book->getGlobalRating();
        $globalRating = is_array($globalRatingData) ? $globalRatingData['rating'] : null;

        $bookResource = new BooksResource(
            id: $book->getId(),
            title: $book->getTitle(),
            authors: $book->getAuthors(),
            publisher: $book->getPublisher(),
            description: $book->getDescription(),
            pageCount: $book->getPageCount(),
            thumbnail: $book->getThumbnail(),
            publishedDate: $book->getPublishedDate(),
            globalRating: $globalRating,
        );

        return new UserBooksResource(
            id: $userBook->getId(),
            bookId: $book->getId(),
            status: $userBook->getStatus()->value,
            pagesRead: $userBook->getPagesRead(),
            isPreferred: $userBook->GetIsPreferred(),
            userRating: $userBook->getUserRating(),
            book: $bookResource,
            review: $userBook->getReview(),
        );
    }
}
