<?php

namespace App\Presentation\Web\Controller\Admin;

use App\Domain\Users\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Application\Admin\Users\UseCase\DeleteUserUseCase;
use App\Application\Admin\Users\UseCase\GetAllUsersUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN', message: 'Accès refusé.', statusCode: Response::HTTP_FORBIDDEN)]
#[Route('/', name: 'admin_')]
final class AdminUsersManagementController extends AbstractController
{
    #[Route('/admin/users_management', name: 'users_management')]
    public function index(GetAllUsersUseCase $getAllUsersUseCase): Response
    {
        $users = $getAllUsersUseCase->getAllUsers();

        return $this->render('admin/users_management.html.twig', [
            "users" => $users,
        ]);
    }

    #[Route('/admin/user/{id}/delete', name: 'user_delete', methods: ['POST'])]
    public function deleteUser(Users $user, DeleteUserUseCase $deleteUserUseCase): Response
    {
        $deleteUserUseCase->deleteUser($user);

        $this->addFlash('success', 'Utilisateur supprimé avec succès.');

        return $this->redirectToRoute('admin_users_management');

    }
}
