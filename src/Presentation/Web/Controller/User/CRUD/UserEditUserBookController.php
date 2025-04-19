<?php

namespace App\Presentation\Web\Controller\User\CRUD;

use App\Domain\UserBooks\Entity\UserBooks;
use App\Presentation\Web\Form\UserBooksType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\UserBooks\UseCase\EditUserBookUseCase;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[IsGranted('ROLE_USER')]
final class UserEditUserBookController extends AbstractController
{
    #[Route('/book/{id}/edit', name: 'book_edit')]
    public function editBook(Request $request, UserBooks $book, EditUserBookUseCase $editUserBookUseCase): Response
    {
        if (!$book) {
            throw $this->createNotFoundException('Le livre n\'existe pas.');
        }
    
        $form = $this->createForm(UserBooksType::class, $book);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            $editUserBookUseCase->editBook($book);

            $this->addFlash('success', 'Livre modifié');
            
            return $this->redirectToRoute('profile_index');
        }
    
        return $this->render('profile/books/book_edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }
}