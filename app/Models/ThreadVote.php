<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThreadVote extends Model
{
    protected $fillable = ['thread_id', 'user_id', 'vote_type'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function thread()
    {
        return $this->belongsTo('App\Models\Thread');
    }
}
