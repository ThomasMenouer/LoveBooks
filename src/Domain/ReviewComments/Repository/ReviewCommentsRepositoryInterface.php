<?php

namespace App\Domain\ReviewComments\Repository;

use App\Domain\ReviewComments\Entity\ReviewComments;

interface ReviewCommentsRepositoryInterface
{

    public function deleteReviewComment(ReviewComments $comment): void;

    public function saveReviewComment(ReviewComments $comment): void;

    public function editReviewComment(ReviewComments $comment): void;

}
