<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'user_id', 'title', 'content', 'vote', 'best_answer_id',
    ];

    protected $appends = ['user_vote'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function answers()
    {
        return $this->hasMany('App\Models\Answer');
    }

    public function votes()
    {
        return $this->hasMany('App\Models\QuestionVote');
    }

    public function bestAnswer()
    {
        return $this->belongsTo('App\Models\Answer');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\QuestionComment');
    }

    public function isOwnedByUser($userId)
    {
        return $this->user_id == $userId;
    }

    public function addComment($data)
    {
        $this->comments()->create($data);
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

    public function getTagButtonAttribute()
    {
        $result = '';

        if ($this->tag) {
            $tagArray = explode(',', $this->tag);
            foreach ($tagArray as $tag) {
                if ($tag) {
                    $result .= '<button class="btn btn-sm btn-outline-secondary">' . $tag . '</button> ';
                }
            }
        }

        return $result;
    }
}
