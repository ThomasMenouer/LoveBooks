<?php

namespace App\Presentation\Web\Controller\User\CRUD;


use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Presentation\Web\Form\UserBooksReadingUpdateType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Application\UserBooks\UseCase\UpdateUserBookReadingProgressUseCase;


#[IsGranted('ROLE_USER')]
final class UserUpdateBookReadingProgressController extends AbstractController
{
    #[Route('/book/{id}/update', name: 'book_update', methods: ['POST'])]
    public function updateBook(Request $request, UpdateUserBookReadingProgressUseCase $UpdateUserBookReadingProgressUseCase, UserBooks $book): Response
    {
        if (!$book) {
            throw $this->createNotFoundException('Livre introuvable.');
        }

        $form = $this->createForm(UserBooksReadingUpdateType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $UpdateUserBookReadingProgressUseCase->update($book);

            if ($request->headers->get('Turbo-Frame')) {
                return $this->render('profile/books/_book_card.html.twig', [
                    'book' => $book,
                    'form' => $this->createForm(UserBooksReadingUpdateType::class, $book)->createView(),
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