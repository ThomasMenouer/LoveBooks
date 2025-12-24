<?php

namespace App\Presentation\Web\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN', message: 'Accès refusé.', statusCode: Response::HTTP_FORBIDDEN)]
final class ApiAdminController extends AbstractController
{
    #[Route('/admin', name: 'api_admin')]
    public function index(): Response
    {
        return $this->render('admin/api_admin.html.twig');
    }
}
