<?php 

namespace App\Application\Books\DTO;

use DateTimeInterface;

class BookDto
{
    private string $title;
    private string $authors;
    private string $publisher;
    private string $description;
    private int $pageCount;
    private DateTimeInterface $publishedDate;
    private string $thumbnail;


    public function __construct(
        string $title, 
        string $authors, 
        string $publisher, 
        string $description, 
        int $pageCount, 
        DateTimeInterface $publishedDate,
        string $thumbnail,
        )
    {
        $this->title = $title;
        $this->authors = $authors;
        $this->publisher = $publisher;
        $this->description = $description;
        $this->pageCount = $pageCount;
        $this->publishedDate = $publishedDate;
        $this->thumbnail = $thumbnail;

    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthors(): string
    {

        return $this->authors;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    public function getPublishedDate(): DateTimeInterface
    {
        return $this->publishedDate;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }
}