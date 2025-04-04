<?php

namespace App\DataTransformer;

use App\Dto\BookDto;
use App\DataTransformer\TransformerInterface;
use PHPUnit\Util\Xml\SuccessfulSchemaDetectionResult;

class BookTransformer implements TransformerInterface
{
    public function transform(array $data): BookDto
    {

        
        // Gérer les erreurs, voir comment faire, exception ?

        $title = $data['title']  ? $data['title'] : 'Titre inconnu';
        $authors = $data['authors'] ? $data['authors'] : 'Auteurs inconnu';
        $publisher = $data['publisher'] ? $data['publisher'] : 'Editeur inconnu';
        $description = $data['description'] ? $data['description'] : 'description inconnu';
        $pageCount = $data['pageCount'] ? $data['pageCount'] : 0;
        $publishedDate = new \DateTime($data['publishedDate']) ? new \DateTime($data['publishedDate']) : new \DateTime('now');

        $dataBook = new BookDto(
            $title, 
            $authors, 
            $publisher,
            $description,
            $pageCount,
            $publishedDate
            );

        return $dataBook;
    }

}