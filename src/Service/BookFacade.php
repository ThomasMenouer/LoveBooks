<?php

namespace App\Service;

use App\Entity\Books;
use App\DataTransformer\BookTransformer;
use App\Dto\BookDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class BookFacade{

    private EntityManagerInterface $em;
    private BookTransformer $bookTransformer;
    private Security $security;


    public function __construct(EntityManagerInterface $em, BookTransformer $bookTransformer, Security $security)
    {
        $this->em = $em;
        $this->bookTransformer = $bookTransformer;
        $this->security = $security;
    }


    public function getData(array $data): BookDto
    {
        // book DTO à l'aide du DataTransformer
        $bookDto = $this->bookTransformer->transform($data);

        return $bookDto;
    }

    public function saveBook(BookDto $bookDto): void
    {
        // On créé l'entité Book grâce à DTO
        $book = new Books(
            $bookDto->getTitle(),
            $bookDto->getAuthors(),
            $bookDto->getPublisher(),
            $bookDto->getDescription(),
            $bookDto->getPageCount(),
            $bookDto->getPublishedDate(),
            $this->security->getUser()
        );

        // Sauvegarder en BDD
        $this->em->persist($book);
        $this->em->flush();
    }

}