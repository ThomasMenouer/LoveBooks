<?php


namespace App\Presentation\Web\Controller\ReviewComments;

use App\Domain\Reviews\Entity\Reviews;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Presentation\Web\Form\ReviewCommentsType;
use App\Domain\ReviewComments\Entity\ReviewComments;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\ReviewComments\UseCase\DeleteReviewCommentsUseCase;

#[IsGranted('ROLE_USER')]
final class DeleteReviewCommentsController extends AbstractController
{
    #[Route('/comment/{id}/delete', name: 'delete_comment', methods: ['POST'])]
    public function deleteReviewComments(ReviewComments $reviewComment, Request $request, DeleteReviewCommentsUseCase $deleteReviewCommentUseCase, Security $security): Response
    {

        $reviewCommentsId = $reviewComment->getId();

        if ($this->isCsrfTokenValid('delete-comment-' . $reviewCommentsId, $request->request->get('_token'))) {

            $deleteReviewCommentUseCase->deleteReviewComment($reviewComment);
        }

        if ($reviewComment->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer ce commentaire.');
        }


        if (!$reviewCommentsId) {
            throw $this->createNotFoundException('Commentaire introuvable.');
        }

        return $this->redirectToRoute('book_index', [
            'id' => $reviewComment->getReview()->getUserBook()->getBook()->getId()
        ]);
    }
}
