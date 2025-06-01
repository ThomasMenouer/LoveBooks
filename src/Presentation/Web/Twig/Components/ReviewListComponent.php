<?php

namespace App\Presentation\Web\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('review_list')]
class ReviewListComponent
{
    public array $reviews = [];
}
