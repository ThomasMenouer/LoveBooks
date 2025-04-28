<?php

namespace App\Presentation\Web\Controller\Reviews;

use App\Domain\Reviews\Entity\Reviews;
use App\Presentation\Web\Form\ReviewType;
use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Application\Reviews\CreateReviewUseCase;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/review', name: 'review_')]
final class ReviewController extends AbstractController
{
    public function __construct(private CreateReviewUseCase $createReviewUseCase) {}

    #[Route('/create/{id}', name: 'create', methods: ['POST'])]
    public function create(UserBooks $userBook, Request $request): Response
    {
        // Vérifier si l'utilisateur a déjà laissé une critique
        $existingReview = $userBook->getReview();

        // Si une review existe déjà, on l'affiche dans le formulaire
        $form = $this->createForm(ReviewType::class, $existingReview ?? new Reviews());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le contenu du formulaire
            $content = $form->get('content')->getData();

            // Exécuter la logique de création ou mise à jour
            $this->createReviewUseCase->execute($userBook, $content);

            // Ajouter un message de succès
            $this->addFlash('success', 'Votre critique a été enregistrée avec succès.');

            // Rediriger vers la page du livre
            return $this->redirectToRoute('book_index', ['id' => $userBook->getBook()->getId()]);
        }

        // Si le formulaire n'est pas soumis ou non valide, rediriger
        return $this->redirectToRoute('book_index', ['id' => $userBook->getBook()->getId()]);
    }
}
