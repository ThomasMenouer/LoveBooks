<?php

namespace App\Controller\User;

use App\Form\BooksType;
use Symfony\UX\Turbo\TurboBundle;
use App\Repository\BooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/profile', name: 'profile_')]
final class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/profile.html.twig', [
            
        ]);
    }

    #[Route('/home', name: 'home')]
    public function profileHome(): Response
    {
        return $this->render('profile/home.html.twig');
    }

    #[Route('/books', name: 'books')]
    public function profileBooks(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $books = $user->getBooks();

        return $this->render('profile/books/books.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/book/{id}', name: 'book_details')]
    public function showBookDetails(BooksRepository $booksRepository, $id): Response
    {
        $book = $booksRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('The book does not exist.');
        }

        return $this->render('profile/books/book_details.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/book/{id}/edit', name: 'book_edit')]
    public function editBook(Request $request, EntityManagerInterface $em, BooksRepository $booksRepository, $id): Response
    {
        $book = $booksRepository->find($id);
    
        if (!$book) {
            throw $this->createNotFoundException('Le livre n\'existe pas.');
        }
    
        $form = $this->createForm(BooksType::class, $book);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
    
            $this->addFlash('success', 'Livre mis à jour avec succès.');
    
            return $this->redirectToRoute('profile_book_details', ['id' => $book->getId()]);
        }
    
        return $this->render('profile/books/book_edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }
    
    #[Route('/book/{id}/delete', name: 'book_delete', methods: ['GET', 'POST'])]
    public function deleteBook(Request $request, EntityManagerInterface $em, BooksRepository $booksRepository, $id): Response
    {
        $book = $booksRepository->find($id);
    
        if (!$book) {
            throw $this->createNotFoundException('Livre introuvable.');
        }
    
        $bookId = $book->getId();
    
        if ($this->isCsrfTokenValid('delete-book-' . $bookId, $request->request->get('_token'))) {
            $em->remove($book);
            $em->flush();
        }
    
        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
    
            return $this->render('profile/books/book_delete.stream.html.twig', [
                'bookId' => $bookId,
            ]);
        }
    
        // Redirection classique
        return $this->redirectToRoute('profile_books');
    }
    
}
