<?php

namespace App\Presentation\Web\Controller\Reviews;

use App\Presentation\Web\Form\ReviewType;
use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Application\Reviews\UseCase\CreateReviewUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class CreateReviewController extends AbstractController
{
    #[Route('review/create/{id}', name: 'create_review', methods: ['POST'])]
    public function create(UserBooks $userBook, Request $request, Security $security, CreateReviewUseCase $createReviewUseCase): Response
    {

        if ($userBook->getUser() !== $security->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas écrire une review pour ce livre.');
        }

        $form = $this->createForm(ReviewType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $createReviewUseCase->createReview($userBook, $form->get('content')->getData());

            $this->addFlash('success', 'Votre review a bien été ajoutée.');

            return $this->redirectToRoute('book_index', [
                'id' => $userBook->getBook()->getId(),
            ]);

        } else {

            $this->addFlash('error', 'Erreur lors de l\'ajout de votre review.');

            return $this->redirectToRoute('book_index', [
                'id' => $userBook->getBook()->getId(),
            ]);

        }

    }
}