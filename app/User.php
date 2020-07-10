<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function updateUserReputation($type = null)
    {
        if (strtolower($type) == 'upvote') {
            $point = 10;
            $this->increment('reputation_point', $point);
        }

        if (strtolower($type) == 'downvote') {
            $point = -1;
            $this->increment('reputation_point', $point);
        }

        if (strtolower($type) == 'cancel upvote') {
            $point = -10;
            $this->increment('reputation_point', $point);
        }

        if (strtolower($type) == 'cancel downvote') {
            $point = 1;
            $this->increment('reputation_point', $point);
        }
    }

    public function isAllowedToDownvote()
    {
        return $this->reputation_point > 15;
    }

    public function getReputationLabelAttribute()
    {
        $reputation = $this->reputation_point;
        $class = $reputation >= 0 ? 'badge badge-success' : 'badge badge-danger';
        return '<span title="Reputation Score" class="reputation ' . $class . '">' . $reputation . '</span>';
    }
}
