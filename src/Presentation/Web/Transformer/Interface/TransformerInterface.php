<?php

namespace App\Presentation\Web\Transformer\Interface;

use App\Domain\Books\Entity\Books;
use App\Application\Books\DTO\BookDto;



interface TransformerInterface
{
    public function transform(array $data): BookDto;

    public function transformToArray(Books $books): array;

}