<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionComment extends Model
{
    protected $fillable = ['question_id', 'user_id', 'content'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }

}
?>
