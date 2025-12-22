<?php

namespace App\Presentation\Api\Processor\UserBooks;


use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Domain\Users\Entity\Users;
use ApiPlatform\Metadata\Operation;
use App\Application\Books\DTO\BookDto;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Application\Books\Service\BookFacade;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\UserBooks\UseCase\EditUserBookUseCase;
use App\Application\UserBooks\UseCase\DeleteUserBookUseCase;
use App\Application\UserBooks\UseCase\GetUserBookByIdUseCase;

final class UserBooksProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly GetUserBookByIdUseCase $getUserBookByIdUseCase,
        private readonly DeleteUserBookUseCase $deleteUserBookUseCase,
        private readonly BookFacade $bookFacade,
        private readonly EditUserBookUseCase $editUserBookUseCase,
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
            return new JsonResponse(['message' => 'Accès interdit'], Response::HTTP_FORBIDDEN);
        }



        // // POST - Ajouter un nouveau livre à la bibliothèque de l'utilisateur
        if ($operation instanceof Post) {
            
            $bookResource = $data->getBook();
            $bookDto = new BookDto(
                $bookResource->getTitle(),
                $bookResource->getAuthors(),
                $bookResource->getPublisher(),
                $bookResource->getDescription(),
                $bookResource->getPageCount() ?? 0,
                $bookResource->getPublishedDate(),
                $bookResource->getThumbnail(),
            );

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

                return new  JsonResponse(['message' => 'Erreur lors de la création du UserBook'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return new JsonResponse(
                [
                    'succes' => true,
                    'message' => 'Le livre a bien été ajouté dans votre bibliothèque.'
                ],
                Response::HTTP_CREATED
            );
        }

        // PATCH - Modifier un UserBook existant
        if ($operation instanceof Patch) {

            $userBook = $this->getUserBookByIdUseCase->execute($uriVariables['id'] ?? null);

            if (!$userBook) {
                return new JsonResponse(['message' => "Le livre de l'utilisateur non trouvé."], Response::HTTP_NOT_FOUND);
            }

            if ($user !== $userBook->getUser()) {
                return new JsonResponse(['message' => 'Accès interdit'], Response::HTTP_FORBIDDEN);
            }

            try {

                $this->editUserBookUseCase->editBook($userBook, [
                    'status' => $data->getStatus(),
                    'pagesRead' => $data->getPagesRead(),
                    'isPreferred' => $data->getIsPreferred(),
                    'userRating' => $data->getUserRating(),
                ]);
            } catch (\Throwable $e) {
                throw new \RuntimeException("Erreur lors de la mise à jour du UserBook : " . $e->getMessage());
            }

            return new JsonResponse(
                [
                    'success' => true,
                    'message' => 'Le livre a bien été mis à jour dans votre bibliothèque.'
                ],
                Response::HTTP_OK
            );
        }

        // DELETE - Supprimer un UserBook
        if ($operation instanceof Delete) {

            // Récupérer le UserBook
            $userBook = $this->getUserBookByIdUseCase->execute($data->getId());

            // Si le UserBook n'existe pas
            if (!$userBook) {
                return new JsonResponse(['message' => "Le livre de l'utilisateur non trouvé."], Response::HTTP_NOT_FOUND);
            }

            // Vérification de sécurité : l'utilisateur doit être le propriétaire
            if ($user  !== $userBook->getUser()) {

                return new JsonResponse(['message' => 'Accès interdit.'], Response::HTTP_FORBIDDEN);
            }

            // Supprimer le UserBook
            return $this->deleteUserBookUseCase->execute($userBook);
        }

        return null;
    }
}
