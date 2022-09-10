<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    use HasFactory;
    protected $fillable = [
        'forum_id',
        'user_id',
        'comment',
    ];
    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
