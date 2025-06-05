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
final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'index')]
    public function index(): Response
    {

        return $this->render('admin/index.html.twig', [
        ]);
    }
}
