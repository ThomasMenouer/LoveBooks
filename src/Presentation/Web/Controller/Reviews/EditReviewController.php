<?php

namespace App\Presentation\Web\Controller\Reviews;

use App\Application\Reviews\UseCase\EditReviewUseCase;
use App\Domain\Reviews\Entity\Reviews;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EditReviewController extends AbstractController
{

    #[Route('/reviews/edit/{id}', name: 'review_edit')]
    public function editReview(Request $request, Reviews $review, EditReviewUseCase $editReviewUseCase): Response
    {
        $submittedToken = $request->request->get('_token');

        if (!$this->isCsrfTokenValid('edit_review_'.$review->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $newContent = $request->request->get('content');

        if ($newContent !== null && trim($newContent) !== '') {
            $review->setContent($newContent);
            $editReviewUseCase->editReview($review);
    
            $this->addFlash('success', 'Votre avis a bien été modifié.');
        } else {
            $this->addFlash('warning', 'Le contenu de l\'avis ne peut pas être vide.');
        }
    
        return $this->redirectToRoute('book_index', ['id' => $review->getUserBook()->getBook()->getId()]);
    }

}