<?php

namespace App\Presentation\Web\Controller\Api;

use App\Application\Users\UseCase\GetUserLibraryStatsUseCase;
use App\Domain\Users\Entity\Users;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Domain\UserBooks\Repository\UserBooksRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted("ROLE_USER")]
#[Route("/api", name: "api_")]
final class ApiStatisticsController extends AbstractController
{
    // public function __construct(
    //     private UserBooksRepositoryInterface $userBooksRepositoryInterface,
    //     private readonly Security $security   
    // ) {}

    // #[Route("/stats/{id}", name: "statistics", methods: ["GET"])]
    // public function getReadingList(Users $user, GetUserLibraryStatsUseCase $getUserLibraryStatsUseCase): JsonResponse
    // {
    //     $stats = $getUserLibraryStatsUseCase->getStats($user);


    //     return new JsonResponse($stats, Response::HTTP_OK);
    // }
}
