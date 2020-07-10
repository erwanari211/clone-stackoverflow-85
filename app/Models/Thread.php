<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $appends = ['user_vote'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function replies()
    {
        return $this->hasMany('App\Models\Reply');
    }

    public function votes()
    {
        return $this->hasMany('App\Models\ThreadVote');
    }

    public function voteUp()
    {
        $user = auth()->user();
        if ($user) {
            $vote = $this->votes()->where('user_id', $user->id)->first();
            if ($vote) {
                if ($vote->vote_type == 1) {
                    $this->unvote();
                }
                if ($vote->vote_type == 0) {
                    $vote->vote_type = 1;
                    $vote->save();
                }
            } else {
                $this->votes()->create([
                    'user_id' => $user->id,
                    'vote_type' => 1,
                ]);
            }
            $this->countVote();
        }
    }

    public function voteDown()
    {
        $user = auth()->user();
        if ($user) {
            $vote = $this->votes()->where('user_id', $user->id)->first();
            if ($vote) {
                if ($vote->vote_type == 0) {
                    $this->unvote();
                }
                if ($vote->vote_type == 1) {
                    $vote->vote_type = 0;
                    $vote->save();
                }
            } else {
                $this->votes()->create([
                    'user_id' => $user->id,
                    'vote_type' => 0,
                ]);
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
        $voteUp = $this->votes()->where('vote_type', 1)->count();
        $voteDown = $this->votes()->where('vote_type', 0)->count();
        $totalVote = $voteUp - $voteDown;
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
                $userVote = $vote->vote_type == 1 ? 'VOTE UP' : 'VOTE DOWN';
            }
        }
        return $userVote;
    }
}
