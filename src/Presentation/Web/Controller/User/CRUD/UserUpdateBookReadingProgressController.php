<?php

namespace App\Presentation\Web\Controller\User\CRUD;

use App\Domain\Books\Entity\Books;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Presentation\Web\Form\BooksReadingUpdateType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\Books\UseCase\UpdateBookReadingProgressUseCase;

#[IsGranted('ROLE_USER')]
final class UserUpdateBookReadingProgressController extends AbstractController
{
    #[Route('/book/{id}/update', name: 'book_update', methods: ['POST'])]
    public function updateBook(Request $request, UpdateBookReadingProgressUseCase $UpdateBookReadingProgressUseCase, Books $book): Response
    {
        if (!$book) {
            throw $this->createNotFoundException('Livre introuvable.');
        }

        $form = $this->createForm(BooksReadingUpdateType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $UpdateBookReadingProgressUseCase->update($book);

            if ($request->headers->get('Turbo-Frame')) {
                return $this->render('profile/books/_book_card.html.twig', [
                    'book' => $book,
                    'form' => $this->createForm(BooksReadingUpdateType::class, $book)->createView(),
                ]);
            }

            return $this->redirectToRoute('profile_index');
        }

        return $this->render('profile/books/book_update.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }
}