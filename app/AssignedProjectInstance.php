<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignedProjectInstance extends Model
{
    //
    protected $table 		= 'pdms_assigned_project_instances';
    protected $primaryKey 	= 'project_instance_assigned_id';

    protected $fillable 	= ["project_instance_id","country_id","district_operation_id","parent_id","status"];


    public function project_instance()
	{
	 	return $this->belongsTo('App\ProjectInstance','project_instance_id');
	}

	public function country()
    {
        return $this->belongsTo('App\Country','country_id');
    }       
}
