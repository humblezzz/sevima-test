<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Like extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = [
        'user_id', 'post_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
