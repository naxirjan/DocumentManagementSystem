<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $table = 'com_project';
    protected $primaryKey = 'proj_id';
    protected $fillable = ['proj_name','is_sub_project','prog_id'];
    
    public function project_instance()
    {
        return $this->belongsTo('App\ProjectInstance','project_id');
    }
    
}
