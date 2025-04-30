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
use App\Application\ReviewComments\UseCase\CreateReviewCommentsUseCase;

#[IsGranted('ROLE_USER')]
final class CreateReviewCommentsController extends AbstractController
{
    #[Route('/reviews/{id}/comment', name: 'review_comment', methods: ['POST'])]
    public function createReviewComments(Reviews $review, Request $request, CreateReviewCommentsUseCase $createReviewCommentUseCase, Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ReviewCommentsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment = new ReviewComments();
            $comment->setUser($user);
            $comment->setReview($review);
            $comment->setContent($form->get('content')->getData());

            $createReviewCommentUseCase->createReviewComment($comment);

            $this->addFlash('success', 'Commentaire ajouté avec succès.');
        } else {
            $this->addFlash('error', 'Erreur lors de l\'ajout du commentaire.');
        }

        return $this->redirectToRoute('book_index', [
            'id' => $review->getUserBook()->getBook()->getId()
        ]);
    }
}
