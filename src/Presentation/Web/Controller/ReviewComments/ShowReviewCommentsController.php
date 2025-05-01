<?php

namespace App\Presentation\Web\Controller\ReviewComments;

use App\Domain\Reviews\Entity\Reviews;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class ShowReviewCommentsController extends AbstractController
{
    #[Route('/review/{id}/comments', name: 'review_comments_frame', methods: ['GET'])]
    public function list(Reviews $review): Response
    {

        return $this->render('components/_comments_frame.html.twig', [
            'review' => $review,
        ]);
    }
}
