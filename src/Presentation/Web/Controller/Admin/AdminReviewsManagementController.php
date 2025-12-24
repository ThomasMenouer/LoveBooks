<?php

namespace App\Presentation\Web\Controller\Admin;


use App\Domain\Reviews\Entity\Reviews;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\Reviews\UseCase\DeleteReviewUseCase;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Application\Admin\Reviews\UseCase\GetAllReviewsUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN', message: 'Accès refusé.', statusCode: Response::HTTP_FORBIDDEN)]
#[Route('/custom', name: 'admin_')]
final class AdminReviewsManagementController extends AbstractController
{
    #[Route('/admin/review_management', name: 'reviews_management_index')]
    public function index(Request $request, GetAllReviewsUseCase $getAllReviewsUseCase): Response
    {
        $pagination = $getAllReviewsUseCase->getPaginatedReviews($request);
    
        return $this->render('admin/reviews/reviews_management.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/admin/reviews_management/review/{id}/delete', name: 'review_delete', methods: ['POST'])]
    public function delete(Reviews $review, DeleteReviewUseCase $deleteReviewUseCase): Response
    {
        $deleteReviewUseCase->deleteReview($review);
        
        $this->addFlash('success', 'Review supprimée avec succès.');

        return $this->redirectToRoute('admin_reviews_management_index');

    }
}
