<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Question extends Model
{
    protected $table = 'pdms_questions';
    protected $primaryKey = 'question_id';
    protected $fillable = ["question","question_type_id","added_by","is_sum","is_average","status"];


    public function question_type()
    {
        return $this->hasMany('App\QuestionType','question_type_id','question_type_id');
    }

    public function question_meta()
    {
        return $this->hasMany('App\QuestionMeta','question_id','question_id');
    }

    /*Asad-Code:start*/
    public function sections() {
        return $this->belongsToMany('App\Section','pdms_question_section','section_id', 'question_id');
    }
    /*Asad-Code:end*/

    public static function getAllQuestions()
    {
        $questions = DB::table('pdms_questions')
            ->join('pdms_question_types', 'pdms_questions.question_type_id', '=', 'pdms_question_types.question_type_id')
            ->join('pdms_role_user', 'pdms_questions.added_by', '=', 'pdms_role_user.role_user_id')
            ->join('pdms_users', 'pdms_role_user.user_id', '=', 'pdms_users.user_id')
            ->join('pdms_roles', 'pdms_role_user.role_id', '=', 'pdms_roles.role_id')
            ->select('pdms_questions.*','pdms_question_types.question_type','pdms_users.first_name','pdms_users.last_name','pdms_roles.role')
            ->orderBy('pdms_questions.created_at', 'DESC')
            ->get()
            ->toArray();

            return $questions;
    }

    public static function getQuestionDetail($question_id)
    {
        $question = DB::table('pdms_questions')
            ->join('pdms_question_types', 'pdms_questions.question_type_id', '=', 'pdms_question_types.question_type_id')
            ->join('pdms_role_user', 'pdms_questions.added_by', '=', 'pdms_role_user.role_user_id')
            ->join('pdms_users', 'pdms_role_user.user_id', '=', 'pdms_users.user_id')
            ->join('pdms_roles', 'pdms_role_user.role_id', '=', 'pdms_roles.role_id')
            ->select('pdms_questions.*','pdms_question_types.question_type','pdms_users.first_name','pdms_users.last_name','pdms_roles.role')
            ->where('pdms_questions.question_id','=',$question_id)
            ->orderBy('pdms_questions.question_id', 'ASC')
            ->get()
            ->toArray();
        
        $question_meta = DB::table('pdms_questions')
            ->join('pdms_question_metas', 'pdms_questions.question_id', '=', 'pdms_question_metas.question_id')
            ->select('pdms_question_metas.*')
            ->where('pdms_questions.question_id','=',$question_id)
            ->orderBy('pdms_question_metas.question_meta_id', 'ASC')
            ->get()
            ->toArray();

            $data['question']=$question;
            $data['question_meta']=$question_meta;
        
            return $data;
    }
    


}
