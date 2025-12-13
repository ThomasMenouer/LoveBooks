<?php

namespace App\Presentation\Web\Controller\Api;

use App\Domain\Users\Entity\Users;
use App\Domain\UserBooks\Enum\Status;
use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Presentation\Web\Transformer\UserBooksTransformer;
use App\Application\Users\UseCase\GetReadingListUserUseCase;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\UserBooks\UseCase\UpdateUserBookReadingProgressUseCase;

#[IsGranted("ROLE_USER")]
#[Route("/api", name: "api_")]
final class ApiCurrentlyReadingController extends AbstractController
{
    public function __construct(
        private UserBooksRepositoryInterface $userBooksRepositoryInterface,
        private readonly Security $security,
        private readonly UpdateUserBookReadingProgressUseCase $updateUserBookReadingProgressUseCase
    ) {}

    #[Route("/reading-list/{id}", name: "reading_list", methods: ["GET"])]
    public function getReadingList(Users $user,GetReadingListUserUseCase $getReadingListUserUseCase, UserBooksTransformer $transformer): JsonResponse
    {

        $currentlyReading = $getReadingListUserUseCase->getReadingList($user);

        $data = $transformer->transformMany($currentlyReading);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route("/reading-list/{id}/update", name: "update", methods: ["PUT"])]
    public function update(UserBooks $userBook, Request $request): JsonResponse
    {

        if (!$userBook) {
            return new JsonResponse(['error' => 'Livre non trouvé'], 404);
        }

        // Sécurité : vérifier que c’est bien l’utilisateur propriétaire
        if ($userBook->getUser() !== $this->security->getUser()) {
            return new JsonResponse(['error' => 'Accès refusé'], 403);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['status'], $data['pagesRead'])) {
            return new JsonResponse(['error' => 'Données manquantes'], 400);
        }

        try {
            $statusEnum = Status::from($data['status']);
            $userBook->setStatus($statusEnum);
        } catch (\ValueError $e) {
            return new JsonResponse(['error' => 'Statut invalide'], 400);
        }

        $userBook->setPagesRead((int) $data['pagesRead']);


        $this->updateUserBookReadingProgressUseCase->update($userBook);

        return new JsonResponse(['success' => true]);
    }
}
