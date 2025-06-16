<?php

namespace App\Domain\Books\Service;

class BookValidator
{
    /**
     * Valide les données reçu par l'API
     * @param array $data
     * @throws \Exception
     * @return void
     */
    public function validate(array $data): void
    {
        foreach ($data as $key => $value) {
            if ($value === null) {
                throw new \InvalidArgumentException(sprintf("Le champ {$key} est manquant ou vide."));
            }
            if ($key === 'pageCount' && ($value == 0)) {
                throw new \InvalidArgumentException("Le nombre de pages est invalide.");
            }
        }

    }
}
