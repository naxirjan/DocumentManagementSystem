<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    //
    protected $table = 'pdms_question_types';
    protected $primaryKey = 'question_type_id';
    
    public function questions()
    {
        return $this->belongsTo('App\Question');
    }
}
