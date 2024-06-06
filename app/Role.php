<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table = 'pdms_roles';
    protected $primaryKey = 'role_id';
    /*Asad-Code:start*/
    public function users()
    {
        return $this->belongsToMany('App\User','pdms_role_user','user_id', 'role_id');
    }
    /*Asad-Code:end*/
}
