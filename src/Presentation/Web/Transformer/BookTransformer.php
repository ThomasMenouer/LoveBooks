<?php

namespace App\Presentation\Web\Transformer;


use App\Domain\Books\Entity\Books;
use App\Application\Books\DTO\BookDto;
use App\Presentation\Web\Transformer\Interface\TransformerInterface;

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
        $thumbnail = $data['thumbnail'] ? $data['thumbnail'] : 'image inconnu';

        $dataBook = new BookDto(
            $title, 
            $authors, 
            $publisher,
            $description,
            $pageCount,
            $publishedDate,
            $thumbnail,
            );

        return $dataBook;
    }

    public function transformToArray(Books $book): array
    {

        return [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'authors' => $book->getAuthors(),
            'pageCount' => $book->getPageCount(),
            'thumbnail' => $book->getThumbnail(),
            'globalRating' => $book->getGlobalRating(),

            // Ajouter suite des infos si necessaire plus tard.
        ];
        
    }

}