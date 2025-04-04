<?php

namespace App\DataTransformer;

use App\Dto\BookDto;


interface TransformerInterface
{

    public function transform(array $data): BookDto;
}