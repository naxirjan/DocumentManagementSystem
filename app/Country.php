<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //
    protected $table = 'com_country';
    protected $primaryKey = 'cont_id';
    
    
    public function assigned_project_instance()
    {
     	return $this->hasMany('App\AssignedProjectInstance','country_id');
    }
    
     public function role_user()
    {
        return $this->belongsTo('App\RoleUser');
    }
}
