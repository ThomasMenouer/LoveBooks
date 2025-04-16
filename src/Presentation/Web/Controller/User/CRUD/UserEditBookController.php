<?php

namespace App\Presentation\Web\Controller\User\CRUD;

use App\Domain\Books\Entity\Books;
use App\Presentation\Web\Form\BooksType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\Books\UseCase\EditBookUseCase;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[IsGranted('ROLE_USER')]
final class UserEditBookController extends AbstractController
{
    #[Route('/book/{id}/edit', name: 'book_edit')]
    public function editBook(Request $request, Books $book, EditBookUseCase $editBookUseCase): Response
    {
        if (!$book) {
            throw $this->createNotFoundException('Le livre n\'existe pas.');
        }
    
        $form = $this->createForm(BooksType::class, $book);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            $editBookUseCase->editBook($book);

            $this->addFlash('success', 'Livre modifié');
            
            return $this->redirectToRoute('profile_index');
        }
    
        return $this->render('profile/books/book_edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }
}