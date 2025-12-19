<?php

namespace App\Application\Books\Service;


use App\Domain\Books\Entity\Books;
use App\Application\Books\DTO\BookDto;
use App\Domain\Books\Repository\BooksRepositoryInterface;
use App\Domain\Books\Service\BookValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\UserBooks\Entity\UserBooks;
use App\Domain\UserBooks\Enum\Status;
use Symfony\Bundle\SecurityBundle\Security;
use App\Presentation\Web\Transformer\BookTransformer;

class BookFacade{

    public function __construct(
        private EntityManagerInterface $em, 
        private BookTransformer $bookTransformer, 
        private Security $security,
        private BookValidator $bookValidator,
        private readonly BooksRepositoryInterface $booksRepositoryInterface)
    {
    }

    public function getData(array $data): BookDto
    {
        // Controler les datas
        $this->bookValidator->validate($data);
        // book DTO à l'aide du DataTransformer
        $bookDto = $this->bookTransformer->transform($data);

        return $bookDto;
    }

    public function saveBook(BookDto $bookDto): Books
    {   
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
            $this->booksRepositoryInterface->saveBook($existingBook);

        }

        return $existingBook;
    }

    public function saveUserBook(Books $book): void
    {

        $user = $this->security->getUser(); 
        
        $userBook = new UserBooks();
        $userBook->setUser($user);
        $userBook->setBook($book);
        $userBook->setStatus(Status::NOT_READ);
        $userBook->setPagesRead(0);
        $userBook->setUserRating(null);

        //$book->addUserBook($userBook);

        // todo : On pourrait vérifié si l'utilisateur à déjà le livre
        $this->em->persist($userBook);
        $this->em->flush();

    }

    public function controlBookData(array $data)
    {

    }

}