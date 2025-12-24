<?php

namespace App\Presentation\Api\Processor\Users;

use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Metadata\Delete;
use App\Domain\Users\Entity\Users;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Admin\Users\UseCase\DeleteUserUseCase;
use App\Application\Admin\Users\UseCase\GetUserByIdUseCase;
use App\Presentation\Api\Resource\Users\UsersResource;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Convertit UserResource ↔ Users (écriture)
 *
 * @implements ProcessorInterface<UsersResource, Users>
 */
final readonly class UsersProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly DeleteUserUseCase $deleteUserUseCase,
        private readonly GetUserByIdUseCase $getUserByIdUseCase
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {

        if ($operation instanceof Delete) {

            $user = $this->getUserByIdUseCase->getUserById((int) $uriVariables['id'] ?? null);

            if (!$user) {
                return new JsonResponse(['message' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
            }

            $this->deleteUserUseCase->deleteUser($user);
        }

        return null;
    }
}
