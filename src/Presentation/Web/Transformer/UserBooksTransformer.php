<?php

namespace App\Presentation\Web\Transformer;

use App\Domain\UserBooks\Entity\UserBooks;
use App\Presentation\Web\Transformer\BookTransformer;

class UserBooksTransformer
{
    public function __construct(private BookTransformer $bookTransformer) {}

    public function transform(UserBooks $userBooks): array
    {
        $book = $userBooks->getBook();

        return [
            'id' => $userBooks->getId(),
            'pagesRead' => $userBooks->getPagesRead(),
            'status' => $userBooks->getStatus()->value,
            'book' => $this->bookTransformer->transformToArray($book),

        ];
    }

    public function transformMany(array $userBooksList): array
    {
        return array_map([$this, 'transform'], $userBooksList);
    }
}
