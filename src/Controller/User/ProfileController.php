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
            throw $this->createNotFoundException('The book does not exist.');
        }


        $form = $this->createForm(BooksType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
    
            // 🔥 Vérifier si Turbo Stream est utilisé
            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
    
                return $this->render('profile/books/book_edit.stream.html.twig', [
                    'book' => $book,
                ], new Response('', Response::HTTP_OK, ['Content-Type' => 'text/vnd.turbo-stream.html']));
            }
    
            // Redirection classique si Turbo n'est pas actif
            return $this->redirectToRoute('profile_books');
        }

        return $this->render('profile/books/book_edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }
}
