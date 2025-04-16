<?php

namespace App\Presentation\Web\Transformer\Interface;

use App\Application\Books\DTO\BookDto;



interface TransformerInterface
{

    public function transform(array $data): BookDto;
}