<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'question_id', 'user_id', 'content', 'vote', 'is_best_answer',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function question()
    {
        return $this->hasMany('App\Models\Question');
    }
}
