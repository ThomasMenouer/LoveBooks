<?php

namespace App\Presentation\Api\Resource\Users;


use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Presentation\Api\Provider\Users\UsersProvider;
use Symfony\Component\Validator\Constraints as Assert;
use App\Presentation\Api\Processor\Users\UsersProcessor;

// #[ApiResource(
//     shortName: 'User',
//     security: "is_granted('ROLE_USER')",
//     operations: [
//         new GetCollection(
//             formats: ['jsonld' => 'application/ld+json', 'json' => 'application/json']
//         ),
//         new Get(
//             name: 'get_user_by_id',
//             formats: ['jsonld' => 'application/ld+json', 'json' => 'application/json']
//         ),
//         new Delete(),
//     ],
//     normalizationContext: ['groups' => ['user:read']],
//     denormalizationContext: ['groups' => ['user:update']],
//     provider: UsersProvider::class,
//     processor: UsersProcessor::class,
// )]
final class UsersResource
{
    public function __construct(
        
        #[ApiProperty(identifier: true)]
        #[Groups(['user:read'])]
        private ?int $id = null,

        #[Assert\NotBlank(message: "L'email est requis", groups: ['user:create', 'user:update'])]
        #[Assert\Email()]
        #[Groups(['user:update', 'user:read'])]
        private ?string $email = null,

        #[Groups(['user:read'])]
        private ?string $name = null,

        #[Groups(['user:read'])]
        private ?array $roles = ['ROLE_USER'],

    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function getRoles(): ?array
    {
        return $this->roles;
    }
}
