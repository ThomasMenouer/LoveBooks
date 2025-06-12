<?php

namespace App\Presentation\Web\Controller\User;


use App\Domain\Users\Entity\Users;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/library/{reactRouting}', name: 'library', methods: ['GET'], requirements: ['reactRouting' => '.*'], defaults: ['reactRouting' => ''])]
final class LibraryController extends AbstractController
{
    public function __construct(private readonly Security $security){}

    public function __invoke(): Response 
    {
        $user = $this->security->getUser();

        return $this->render('library/library.html.twig', [
            "user" => $user,
        ]);
    }
}
