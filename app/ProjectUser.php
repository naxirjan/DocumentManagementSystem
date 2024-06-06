<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class ProjectUser extends Model
{
    protected $table = 'pdms_project_users';
    protected $primaryKey = 'project_user_id';
    protected $fillable = ['district_operation_project_id','role_user_id','status'];

    static public function getUserProjects($user_role_id){

        $userProjects= DB::table('com_project')
              ->join("pdms_district_operation_projects As dop",'com_project.proj_id','=','dop.project_id')
              ->join('pdms_project_users As pu','dop.district_operation_project_id','=','pu.district_operation_project_id')
              ->where('pu.role_user_id',$user_role_id)->get()->toArray();

        return ($userProjects);
    }
}
