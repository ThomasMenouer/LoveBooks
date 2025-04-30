<?php

namespace App\Presentation\Web\Controller\Reviews;

use App\Domain\Reviews\Entity\Reviews;
use App\Presentation\Web\Form\ReviewType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\Reviews\UseCase\EditReviewUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EditReviewController extends AbstractController
{

    #[Route('/reviews/edit/{id}', name: 'review_edit', methods: ['GET', 'POST'])]
    public function editReview(Request $request, Reviews $review, EditReviewUseCase $editReviewUseCase): Response
    {
        if ($review->getUserBook()->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier cette review.');
        }

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUpdatedAt(new \DateTimeImmutable());
            
            $editReviewUseCase->editReview($review);

            $this->addFlash('success', 'Votre review a été mise à jour.');

            return $this->redirectToRoute('book_index', [
                'id' => $review->getUserBook()->getBook()->getId(),
            ]);

        } else {

            $this->addFlash('error', 'Erreur lors de la mise à jour de votre review.');

            return $this->redirectToRoute('book_index', [
                'id' => $review->getUserBook()->getBook()->getId(),
            ]);
        }
    }

}