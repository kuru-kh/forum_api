<?php

namespace App\Repository;

use App\Models\ForumComment;

class ForumCommentRepository
{
    public function create(array $data)
    {
        return ForumComment::create($data);
    }
}