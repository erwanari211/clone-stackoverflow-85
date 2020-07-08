<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'user_id', 'title', 'content', 'vote', 'best_answer_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
