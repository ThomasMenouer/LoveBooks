<?php

namespace App\Application\Admin\Books\UseCase;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Pagination\PaginationInterface;
use App\Domain\Books\Repository\BooksRepositoryInterface;

final class GetAllBooksUseCase
{
    public function __construct(private BooksRepositoryInterface $booksRepository, private readonly PaginatorInterface $paginator){}

    public function getPaginatedBooks(Request $request): PaginationInterface
    {
        $books = $this->booksRepository->getAllBooks();
        
        $paginate = $this->paginator->paginate(
            $books,
            $request->query->getInt('page', 1), // Current page number
            $request->query->getInt('limit', 5) // Items per page
        );

        $paginate->setCustomParameters([
            'align' => 'center',
            'prev_message' => 'Précédent',
            'next_message' => 'Suivant',
            'page_out_of_range_message' => 'Page hors limite',
        ]);

        return $paginate;   
    }
}

