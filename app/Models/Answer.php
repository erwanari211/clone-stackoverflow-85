<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'question_id', 'user_id', 'content', 'vote', 'is_best_answer',
    ];

    protected $appends = ['user_vote'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function question()
    {
        return $this->hasMany('App\Models\Question');
    }

    public function votes()
    {
        return $this->hasMany('App\Models\AnswerVote');
    }

    public function upvote()
    {
        $user = auth()->user();
        if ($user) {
            $vote = $this->votes()->where('user_id', $user->id)->first();
            if ($vote) {
                if ($vote->vote_type == 1) {
                    $this->unvote();
                    $this->user->updateUserReputation('cancel upvote');
                }
                if ($vote->vote_type == 0) {
                    $vote->upvote();
                    $this->user->updateUserReputation('cancel downvote');
                    $this->user->updateUserReputation('upvote');
                }
            } else {
                $this->votes()->create([
                    'user_id' => $user->id,
                    'vote_type' => 1,
                ]);
                $this->user->updateUserReputation('upvote');
            }
            $this->countVote();
        }
    }

    public function downvote()
    {
        $user = auth()->user();
        if ($user) {
            $vote = $this->votes()->where('user_id', $user->id)->first();
            if ($vote) {
                if ($vote->vote_type == 0) {
                    $this->unvote();
                    $this->user->updateUserReputation('cancel downvote');
                }
                if ($vote->vote_type == 1) {
                    $vote->downvote();
                    $this->user->updateUserReputation('cancel upvote');
                    $this->user->updateUserReputation('downvote');
                }
            } else {
                $this->votes()->create([
                    'user_id' => $user->id,
                    'vote_type' => 0,
                ]);
                $this->user->updateUserReputation('downvote');
            }
            $this->countVote();
        }
    }

    public function unvote()
    {
        $user = auth()->user();
        if ($user) {
            $vote = $this->votes()->where('user_id', $user->id)->first();
            $vote->delete();
        }
        $this->countVote();
    }

    public function countVote()
    {
        $upvote     = $this->votes()->where('vote_type', 1)->count();
        $downvote   = $this->votes()->where('vote_type', 0)->count();
        $totalVote  = $upvote - $downvote;
        $this->vote = $totalVote;
        $this->save();
    }

    public function getUserVoteAttribute()
    {
        $userVote = null;
        if (auth()->check()) {
            $user = auth()->user();
            $vote = $this->votes()->where('user_id', $user->id)->first();
            if ($vote) {
                $userVote = $vote->vote_type == 1 ? 'UPVOTE' : 'DOWNVOTE';
            }
        }
        return $userVote;
    }
}
