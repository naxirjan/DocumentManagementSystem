<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectInstanceDocumentTemplateAnswer extends Model
{
    //
    protected $table = 'pdms_project_instance_document_template_answers';
    protected $primaryKey = 'project_instance_document_template_answer_id';
    protected $fillable = ["document_template_question_section_id","project_instance_submission_id","answer","status"];
}