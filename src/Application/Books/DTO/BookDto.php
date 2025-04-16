<?php 

namespace App\Application\Books\DTO;

class BookDto
{

    private string $title;
    private string $authors;
    private string $publisher;
    private string $description;
    private int $pageCount;
    private string $status;
    private \DateTimeInterface $publishedDate;
    private string $thumbnail;


    public function __construct(
        string $title, 
        string $authors, 
        string $publisher, 
        string $description, 
        int $pageCount, 
        \DateTimeInterface $publishedDate,
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


    public function getTitle()
    {
        return $this->title;
    }

    public function getAuthors()
    {

        return $this->authors;
    }

    public function getPublisher()
    {
        return $this->publisher;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPageCount()
    {
        return $this->pageCount;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }






}