<?php

namespace App\Controller\User;

use App\Form\BooksType;
use App\Form\SearchBookType;
use App\Repository\BooksRepository;
use App\Service\GoogleBooksService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/mybooks', name: 'mybooks_')]
final class MyBooksController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $books = $user->getBooks();

        return $this->render('my_books/index.html.twig', [
            'books' => $books
            
        ]);
    }

}
