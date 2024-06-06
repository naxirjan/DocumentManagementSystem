<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectInstanceSubmission extends Model
{
    //
    protected $table = 'pdms_project_instance_submissions';
    protected $primaryKey = 'project_instance_submission_id';
    protected $fillable = ["project_instance_document_template_id","submitted_by","submitted_on","status"];
}
