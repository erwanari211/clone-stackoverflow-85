<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerComment extends Model
{
    protected $fillable = ['answer_id', 'user_id', 'content'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function answer()
    {
        return $this->belongsTo('App\Models\Answer');
    }

}
?>