<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerVote extends Model
{
    protected $fillable = ['answer_id', 'user_id', 'vote_type'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function answer()
    {
        return $this->belongsTo('App\Models\Answer');
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
?>