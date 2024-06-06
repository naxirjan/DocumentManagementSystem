<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class RoleUser extends Model
{
	protected $table = 'pdms_role_user';
    protected $primaryKey = 'role_user_id';
    protected $fillable = ['user_id','role_id','country_id','district_operation_id','status'];  


    public static function getDistrictOperationProjects($roleUserId,$dopId){
        return $userProjects = DB::table('com_project as p')
                
                ->join('pdms_district_operation_projects as dopp','p.proj_id','=','dopp.project_id')

                ->join('pdms_district_operations as dop','dop.district_operation_id','=','dopp.district_operation_id')
                
                ->join('pdms_role_user as ru','ru.district_operation_id','=','dop.district_operation_id')

                ->select('dopp.district_operation_project_id','dopp.district_operation_id','dopp.project_id','ru.role_user_id','dopp.status','p.proj_name','dop.district_operation_full_name','dop.district_operation_short_name')
                ->where('ru.role_user_id',$roleUserId)->where('dop.district_operation_id',$dopId)

                ->get()->toArray();
    } 
}
