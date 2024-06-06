<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'pdms_users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'first_name','last_name', 'email', 'password','phone','image','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*Asad-Code:start*/
    public function roles()
    {
        return $this->belongsToMany('App\Role','pdms_role_user','user_id', 'role_id');
    }

    /*Get All Assigned Role By User ID Asad:start*/
    public static function getUserAssignedRoles($userID){
        $userAssignedRoles = DB::table('pdms_users')->join('pdms_role_user','pdms_users.user_id','=','pdms_role_user.user_id')
        ->join('pdms_roles','pdms_roles.role_id','=','pdms_role_user.role_id')
        ->leftJoin('com_country','com_country.cont_id','=','pdms_role_user.country_id')
        ->leftJoin('pdms_district_operations', 'pdms_district_operations.district_operation_id','=','pdms_role_user.district_operation_id')
        ->where('pdms_users.user_id', $userID)
        ->select('pdms_roles.role_id','pdms_roles.role','com_country.cont_id', 'com_country.cont_name' ,'pdms_role_user.role_user_id','pdms_role_user.status As role_status','pdms_district_operations.district_operation_id','pdms_district_operations.district_operation_short_name')
        ->orderBy('pdms_role_user.role_id' , 'asc')
        ->get()->toArray();
        return $userAssignedRoles;
    }
    /*Get All Assigned Role By User ID Asad:end*/

    /*Asad-Code:end*/
}
