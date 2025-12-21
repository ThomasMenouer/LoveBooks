<?php

namespace App\Presentation\Api\Processor\UserBooks;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Domain\Books\Entity\Books;
use App\Domain\Users\Entity\Users;
use ApiPlatform\Metadata\Operation;
use App\Domain\UserBooks\Enum\Status;
use App\Application\Books\DTO\BookDto;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Application\Books\Service\BookFacade;
use App\Presentation\Web\Transformer\BookTransformer;
use App\Application\UserBooks\UseCase\EditUserBookUseCase;
use App\Application\UserBooks\UseCase\DeleteUserBookUseCase;
use App\Application\UserBooks\UseCase\GetUserBookByIdUseCase;
use App\Presentation\Api\Resource\UserBooks\UserBooksResource;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;

final class UserBooksProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly GetUserBookByIdUseCase $getUserBookByIdUseCase,
        private readonly DeleteUserBookUseCase $deleteUserBookUseCase,
        private readonly BookFacade $bookFacade,
        private readonly BookTransformer $bookTransformer,

    ) {}

    /**
     * Summary of process
     * @param mixed $data # Instance de UserBooksResource (DTO)
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?object
    {
        /** @var Users $user */
        $user = $this->security->getUser();

        if (!$user) {
            throw new \RuntimeException('User not authenticated');
        }

        $bookDto = new BookDto(
            $data->getBook()['title'],
            $data->getBook()['authors'],
            $data->getBook()['publisher'],
            $data->getBook()['description'],
            $data->getBook()['pageCount'] ?? 0,
            new \DateTime($data->getBook()['publishedDate']),
            $data->getBook()['thumbnail'],
        );

        

        // // POST - Ajouter un nouveau livre à la bibliothèque de l'utilisateur
        if ($operation instanceof Post) {

            try {
                $book = $this->bookFacade->saveBook($bookDto);
                $this->bookFacade->saveUserBook($book);
            } catch (\Throwable $e) {

                throw $e;
            }

            // Récupérer le UserBook nouvellement créé
            $userBooks = $user->getUserBooks();
            $newUserBook = null;
            foreach ($userBooks as $ub) {
                if ($ub->getBook()->getId() === $book->getId()) {
                    $newUserBook = $ub;
                    break;
                }
            }

            if (!$newUserBook) {
                throw new \RuntimeException('Failed to create user book');
            }

            return new UserBooksResource(
                id: $newUserBook->getId(),
                bookId: $book->getId(),
                status: $newUserBook->getStatus()->value,
                pagesRead: $newUserBook->getPagesRead(),
                isPreferred: $newUserBook->GetIsPreferred(),
                userRating: $newUserBook->getUserRating(),
                book: $this->bookTransformer->transformToArray($book),
                review: $newUserBook->getReview(),
            );

        }

        // // PATCH - Modifier un UserBook existant
        // if ($operation instanceof Patch) {
        //     return $this->editUserBook($data, $uriVariables, $user);
        // }

        // DELETE - Supprimer un UserBook
        if ($operation instanceof Delete) {

            // Récupérer le UserBook
            $userBook = $this->getUserBookByIdUseCase->execute($data->getId());

            // Si le UserBook n'existe pas
            if (!$userBook) {
                throw new \RuntimeException('UserBook not found');
            }

           // Vérification de sécurité : l'utilisateur doit être le propriétaire
            if ($user  !== $userBook->getUser()) {

                throw new \RuntimeException('Access denied');
            }

            // Supprimer le UserBook
            return $this->deleteUserBookUseCase->execute($userBook);

        }

        return null;
    }

    // private function editUserBook(UserBooksResource $data, array $uriVariables, Users $user): UserBooksResource
    // {
    //     $userBookId = $uriVariables['id'] ?? null;
    //     if (!$userBookId) {
    //         throw new \RuntimeException('UserBook ID not provided');
    //     }

    //     // Récupérer le UserBook
    //     $userBook = null;
    //     foreach ($user->getUserBooks() as $ub) {
    //         if ($ub->getId() === (int)$userBookId) {
    //             $userBook = $ub;
    //             break;
    //         }
    //     }

    //     if (!$userBook) {
    //         throw new \RuntimeException('UserBook not found');
    //     }

    //     // Vérification de sécurité
    //     if ($userBook->getUser() !== $user) {
    //         throw new \RuntimeException('Access denied');
    //     }

    //     // Préparer les données pour EditUserBookUseCase
    //     $editData = [
    //         'status' => $data->status ?? $userBook->getStatus()->value,
    //         'pagesRead' => $data->pagesRead ?? $userBook->getPagesRead(),
    //         'isPreferred' => $data->isPreferred ?? $userBook->GetIsPreferred(),
    //         'userRating' => $data->userRating ?? $userBook->getUserRating(),
    //     ];

    //     $this->editUserBookUseCase->editBook($userBook, $editData);

    //     $book = $userBook->getBook();
    //     return new UserBooksResource(
    //         id: $userBook->getId(),
    //         bookId: $book->getId(),
    //         status: $userBook->getStatus()->value,
    //         pagesRead: $userBook->getPagesRead(),
    //         isPreferred: $userBook->GetIsPreferred(),
    //         userRating: $userBook->getUserRating(),
    //         book: $this->bookTransformer->transformToArray($book)
    //     );
    // }
}
