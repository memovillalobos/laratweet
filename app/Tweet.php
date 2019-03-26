<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = [
        'tweet_id',
        'message',
        'author'
    ];

    protected $with = [
        'replies'
    ];

    public function replies()
    {
        return $this->hasMany('\App\Tweet');
    }
}
