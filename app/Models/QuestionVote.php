<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionVote extends Model
{
    protected $fillable = ['question_id', 'user_id', 'vote_type'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }

    public function upvote()
    {
        $this->vote_type = 1;
        $this->save();
    }

    public function downvote()
    {
        $this->vote_type = 0;
        $this->save();
    }
}
