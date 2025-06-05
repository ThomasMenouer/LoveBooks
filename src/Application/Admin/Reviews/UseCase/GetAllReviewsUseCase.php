<?php

namespace App\Application\Admin\Reviews\UseCase;


use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Pagination\PaginationInterface;
use App\Domain\Reviews\Repository\ReviewsRepositoryInterface;

final class GetAllReviewsUseCase
{
    public function __construct(private ReviewsRepositoryInterface $reviewsRepository, private readonly PaginatorInterface $paginator){}

    public function getPaginatedReviews(Request $request): PaginationInterface
    {
        $reviews = $this->reviewsRepository->getAllReviews();
        
        $paginate = $this->paginator->paginate(
            $reviews,
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