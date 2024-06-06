<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionMeta extends Model
{	
    protected $table = 'pdms_question_metas';
    protected $primaryKey = 'question_meta_id';
    protected $fillable = ["question_id","key","value","status"];

    public function questions()
    {
        return $this->belongsTo('App\Question');
    }
}
