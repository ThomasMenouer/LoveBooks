<?php

namespace App\Presentation\Web\Controller\User;

use App\Domain\Books\Entity\Books;
use App\Presentation\Web\Form\BooksType;
use Symfony\UX\Turbo\TurboBundle;
use App\Presentation\Web\Form\BooksReadingUpdateType;
use App\Infrastructure\Persistence\Doctrine\Repository\BooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
#[Route('/profile', name: 'profile_')]
final class ProfileController extends AbstractController
{

    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function profileHome(BooksRepository $bookRepository, Security $security): Response
    {
        $user = $security->getUser();

        $books = $bookRepository->getReadingListForUser($user);

        $bookForms = [];

        foreach ($books as $book) {
            $form = $this->createForm(BooksReadingUpdateType::class, $book);
            $bookForms[$book->getId()] = $form->createView();
        }
    
        return $this->render('profile/profile.html.twig', [
            'bookStats' => $bookRepository->countByStatusForUser($user),
            'totalBooks' => $bookRepository->getTotalBooksForUser($user),
            'totalPagesRead' => $bookRepository->getTotalPagesReadForUser($user),
            'readingList' => $books,
            'bookForms' => $bookForms,
        ]);
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

            if ($book->getStatus() === 'Lu') {
                $book->setPagesRead($book->getPageCount());
            }
            elseif ($book->getStatus() === 'En cours de lecture') {
                $book->setPagesRead($book->getPagesRead());
            } 
            else {
                $book->setPagesRead(0);
            }

            if ($book->getPagesRead() >= $book->getPageCount()) {

                $book->setStatus('Lu');
                $book->setPagesRead($book->getPageCount());
            } 
            else {
                $book->setStatus('En cours de lecture');
            }
            $em->flush();

            $this->addFlash('success', 'Livre modifié');
            return $this->redirectToRoute('profile_index');
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

    #[Route('/book/{id}/update', name: 'book_update', methods: ['POST'])]
    public function updateBook(Request $request, EntityManagerInterface $em, BooksRepository $booksRepository, int $id): Response
    {
        $book = $booksRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Livre introuvable.');
        }

        $form = $this->createForm(BooksReadingUpdateType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($book->getStatus() === 'Lu') {
                $book->setPagesRead($book->getPageCount());
            } elseif ($book->getStatus() === 'En cours de lecture') {
                $book->setPagesRead($book->getPagesRead());
            } else {
                $book->setPagesRead(0);
            }

            if ($book->getPagesRead() >= $book->getPageCount()) {
                $book->setStatus('Lu');
                $book->setPagesRead($book->getPageCount());
            } else {
                $book->setStatus('En cours de lecture');
            }

            $em->flush();

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
