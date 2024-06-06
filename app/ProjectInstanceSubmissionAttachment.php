<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectInstanceSubmissionAttachment extends Model
{
    //
    protected $table = 'pdms_project_instance_submission_attachments';
    protected $primaryKey = 'project_instance_submission_attachment_id';
    protected $fillable = ["project_instance_submission_id","file_type_id","file_path","file_description"];
}

