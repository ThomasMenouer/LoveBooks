<?php

namespace App\Domain\UserBooks\Enum;

enum Status: string
{
    case READ = 'Lu';
    case READING = 'En cours de lecture';
    case ABANDONED = 'Abandonné';
    case NOT_READ = 'Non lu';

    public function label(): string
    {
        return match ($this) {
            self::NOT_READ => 'Non lu',
            self::READING => 'En cours de lecture',
            self::READ => 'Lu',
            self::ABANDONED => 'Abandonné',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NOT_READ => 'color-yellow',
            self::READING => 'color-blue',
            self::READ => 'color-green',
            self::ABANDONED => 'color-red',
        };
    }
}