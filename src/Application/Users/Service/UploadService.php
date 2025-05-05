<?php 

namespace App\Application\Users\Service;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class UploadService
{
    public function __construct(
        private readonly string $avatarDirectory,
        private readonly SluggerInterface $slugger,
    ) {}

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getAvatarDirectory(), $fileName);
        } catch (FileException $e) {
            throw new \RuntimeException('Erreur lors du téléchargement du fichier : ' . $e->getMessage());
        }

        return $fileName;
    }

    public function getAvatarDirectory(): string
    {
        return $this->avatarDirectory;
    }

    public function delete(string $filename): void
{
    $path = $this->avatarDirectory . '/' . $filename;
    if (file_exists($path)) {
        unlink($path);
    }
}
}