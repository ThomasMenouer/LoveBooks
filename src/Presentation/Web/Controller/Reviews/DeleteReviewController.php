<?php 

namespace App\Presentation\Web\Controller\Reviews;

use App\Domain\Reviews\Entity\Reviews;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\Reviews\UseCase\DeleteReviewUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class DeleteReviewController extends AbstractController
{
    #[Route('/reviews/{id}/delete', name: 'delete_review', methods: ['POST'])]
    public function deleteReview(Reviews $review, DeleteReviewUseCase $deleteReviewUseCase, Request $request): Response
    {

        $reviewId = $review->getId();
        if (!$review) {
            throw $this->createNotFoundException('Review introuvable.');
        }

        if ($this->isCsrfTokenValid('delete-review-' . $reviewId, $request->request->get('_token'))) {

            $deleteReviewUseCase->deleteReview($review);
        }

        return $this->redirectToRoute('book_index', ['id' => $review->getUserBook()->getBook()->getId()]);
    }
}