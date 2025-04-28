<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('review_list')]
class ReviewListComponent
{
    public array $reviews = [];
}
