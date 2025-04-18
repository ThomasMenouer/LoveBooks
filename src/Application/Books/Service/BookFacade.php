<?php

namespace App\Application\Books\Service;


use App\Domain\Books\Entity\Books;
use App\Application\Books\DTO\BookDto;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\UserBooks\Entity\UserBooks;
use Symfony\Bundle\SecurityBundle\Security;
use App\Presentation\Web\Transformer\BookTransformer;

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
        $user = $this->security->getUser();  // L'utilisateur connecté
        
        // Vérification si le livre existe déjà dans la base de données
        $existingBook = $this->em->getRepository(Books::class)->findOneBy([
            'title' => $bookDto->getTitle(),
            'authors' => $bookDto->getAuthors(),
            'publisher' => $bookDto->getPublisher(),
            'publishedDate' => $bookDto->getPublishedDate(),
        ]);

        // Si le livre n'existe pas, on le crée
        if (!$existingBook) {
            $existingBook = new Books(
                $bookDto->getTitle(),
                $bookDto->getAuthors(),
                $bookDto->getPublisher(),
                $bookDto->getDescription(),
                $bookDto->getPageCount(),
                $bookDto->getPublishedDate(),
                $bookDto->getThumbnail(),
            );

            // Sauvegarder le livre en BDD
            $this->em->persist($existingBook);
            $this->em->flush();
        }

        // Ajouter l'association entre l'utilisateur et le livre
        $userBook = new UserBooks();
        $userBook->setUser($user);
        $userBook->setBook($existingBook);
        $userBook->setStatus("Non lu");
        $userBook->setPagesRead(0);
        $userBook->setUserRating(null);

        $this->em->persist($userBook);
        $this->em->flush();
    }

}