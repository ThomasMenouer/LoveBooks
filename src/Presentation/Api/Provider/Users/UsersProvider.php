<?php

namespace App\Presentation\Api\Provider\Users;

use ApiPlatform\Metadata\CollectionOperationInterface;
use App\Domain\Users\Entity\Users;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Application\Admin\Users\UseCase\GetAllUsersUseCase;
use App\Presentation\Api\Resource\Users\UsersResource;
use App\Application\Admin\Users\UseCase\GetUserByIdUseCase;

/**
 * Fournit les données UserResource à partir de l'entité Users.
 *
 * @implements ProviderInterface<UsersResource>
 */
final readonly class UsersProvider implements ProviderInterface
{
    public function __construct(
        private readonly GetAllUsersUseCase $getAllUsersUseCase,
        private readonly GetUserByIdUseCase $getUserByIdUseCase,
        ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable|object|null
    {

        // Recupère un utilisateur par ID
        if ($operation->getName() === 'get_user_by_id')
        {
            $user =$this->getUserByIdUseCase->getUserById((int) $uriVariables['id'] ?? null);

            return $user ? $this->toResource($user) : null;

        }

        // Récupère tous les utilisateurs
        if($operation instanceof CollectionOperationInterface) {

            $users = $this->getAllUsersUseCase->getAllUsers();

            return array_map(fn(Users $user) => $this->toResource($user), $users);
        }

        return null;
    }

    private function toResource(Users $user): UsersResource
    {
        return new UsersResource(
            id: $user->getId(),
            email: $user->getEmail(),
            name: $user->getName(),
            roles: $user->getRoles(),
        );
    }
}
