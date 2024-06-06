<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectInstance extends Model
{
    //
    protected $table 		= 'pdms_project_instances';
    protected $primaryKey 	= 'project_instance_id';

    protected $fillable 	= ["project_id","added_by","project_instance_title","project_instance_description","project_instance_start_date","project_instance_end_date","status"];


    public function project_instance_countries()
     {
        return $this->hasMany('App\AssignedProjectInstance','project_instance_id');
     }

     public function project()
    {
        return $this->hasOne('App\Project','proj_id');
    }

    public static function getAllProjectInstances()
    {
        $questions = DB::table('pdms_project_instances')
             ->join('pdms_assigned_project_instances as api_1', 'pdms_project_instances.project_instance_id', '=', 'api_1.project_instance_id')
            ->join('com_country', 'api_1.country_id', '=', 'com_country.cont_id')
            ->get()
            ->toArray();

            return $questions;
    }


    /*Return All Project Instance For Particular Country-Asad:start*/
    public static function getAllProjectInstancesCreatedByCountryManager($countryId = null){
        $projectInstancesByCountry = DB::table('pdms_project_instances AS pis')
             ->join('pdms_assigned_project_instances as api_1', 'pis.project_instance_id', '=', 'api_1.project_instance_id')
            ->join('com_country', 'api_1.country_id', '=', 'com_country.cont_id')
            ->leftJoin('pdms_district_operations as dop', 'dop.district_operation_id', '=', 'api_1.district_operation_id')
            ->where('api_1.country_id',$countryId)->where('api_1.district_operation_id','!=',null)
            ->select('pis.*' , 'com_country.*' ,'dop.district_operation_full_name','dop.district_operation_short_name')
            ->orderBy('pis.project_instance_id' ,'desc')->get()->toArray();

        return $projectInstancesByCountry;
    }
    //End-->

    /*Return Single Project Instance By project_instance_id-Asad:start*/
    public static function getSingleProjectInstanceCreatedByCountryManager($projectInstanceId = null)
    {
        $projectInstanceByCountry = DB::table('pdms_project_instances AS pis')
             ->join('pdms_assigned_project_instances as api_1', 'pis.project_instance_id', '=', 'api_1.project_instance_id')
            ->join('com_country', 'api_1.country_id', '=', 'com_country.cont_id')
            ->leftJoin('pdms_district_operations as dop', 'dop.district_operation_id', '=', 'api_1.district_operation_id')
            ->where('pis.project_instance_id' ,$projectInstanceId)
            ->select('pis.*' , 'com_country.*' ,'dop.district_operation_full_name' ,'dop.district_operation_short_name' ,'api_1.status as assigned_country_status')
            ->orderBy('pis.project_instance_id' ,'desc')->get()->toArray();

        return $projectInstanceByCountry;
    }
    //End-->

    /*Return Countries And Districts Which Is Assigned To Project Instance by project_instance_id-Asad:start*/
    public static function getProjectInstanceCountryDistrictById($projectInstanceId =null){
        $projectInstanceCountryDistrict = DB::table('pdms_project_instances AS pis')
             ->join('pdms_assigned_project_instances as api_1', 'pis.project_instance_id', '=', 'api_1.project_instance_id')
            ->join('com_country', 'api_1.country_id', '=', 'com_country.cont_id')
            ->join('pdms_district_operations as dop', 'dop.district_operation_id', '=', 'api_1.district_operation_id')
            ->where('pis.project_instance_id' ,$projectInstanceId)
            ->select('com_country.*' ,'dop.*' ,'api_1.status as assigned_country_status' ,'api_1.project_instance_assigned_id')
            ->orderBy('pis.project_instance_id' ,'desc')->get()->toArray();
        return $projectInstanceCountryDistrict;
    }
    //End-->

    /*Return All Assigned Project Instance For Country Manager-Asad:start*/
    public static function getAllAssignedProjectInstancesForCountryManager($countryId=null,$withTemplate=false,$projectInstanceId =null)
    {
        $projectInstancesByCountry = DB::table('pdms_project_instances AS pis')
        ->join('pdms_assigned_project_instances as api_1', 'pis.project_instance_id', '=', 'api_1.project_instance_id')
        ->join('com_country', 'api_1.country_id', '=', 'com_country.cont_id')
        ->leftJoin('pdms_district_operations as dop', 'dop.district_operation_id', '=', 'api_1.district_operation_id');
            
        /*For Get Recored With Templates*/
        if($withTemplate){
            $projectInstancesByCountry->join('pdms_project_instance_document_templates as pidt' ,'pidt.project_instance_assigned_id' ,'=' ,'api_1.project_instance_assigned_id')
            ->join('pdms_document_templates as dt' ,'dt.document_template_id','=','pidt.document_template_id');
                
            $projectInstancesByCountry->select('pis.*' , 'com_country.*' ,'dop.district_operation_full_name','dop.district_operation_short_name' ,'api_1.*' ,'dt.document_template_id' ,'dt.document_template_title' ,'pidt.project_instance_document_template_id' ,'pidt.project_instance_submission_start_date as start_date' ,'pidt.project_instance_submission_stop_date as end_date' ,'pidt.status as a_d_t_status');

            $projectInstancesByCountry->where('api_1.status','Active');
            $projectInstancesByCountry->where('pis.status' ,'Active');

            /*For Single Recored Only*/
            if($projectInstanceId){
                $projectInstancesByCountry->where('pis.project_instance_id',$projectInstanceId);
            }

        }else{
                $projectInstancesByCountry->select('pis.*' , 'com_country.*' ,'dop.district_operation_full_name','dop.district_operation_short_name' ,'api_1.*');
            
                $projectInstancesByCountry->where('pis.status' ,'Active');
        }

        return $projectInstancesByCountry->where('api_1.country_id',$countryId)
                ->where('api_1.district_operation_id',null)
                ->orderBy('pis.project_instance_id' ,'desc')->get()->toArray();

    }
    //End-->

    /*Return All Assigned Project Instance For Operation Manager-Asad:start*/
    public static function getAllAssignedProjectInstancesForOperationManager($districtId=null,$projectInstanceId =null)
    {
        $projectInstancesByDistrict = DB::table('pdms_project_instances AS pis')
        ->join('pdms_assigned_project_instances as api_1', 'pis.project_instance_id', '=', 'api_1.project_instance_id')
        ->join('com_country', 'api_1.country_id', '=', 'com_country.cont_id')
        ->join('pdms_district_operations as dop', 'dop.district_operation_id', '=', 'api_1.district_operation_id')
            
        ->join('pdms_project_instance_document_templates as pidt' ,'pidt.project_instance_assigned_id' ,'=' ,'api_1.project_instance_assigned_id')
        ->join('pdms_document_templates as dt' ,'dt.document_template_id','=','pidt.document_template_id')
            
        ->select('pis.*' , 'com_country.*' ,'dop.district_operation_full_name','dop.district_operation_short_name' ,'api_1.*' ,'dt.document_template_id' ,'dt.document_template_title','pidt.*')    

        ->where('api_1.district_operation_id',$districtId);
            
        /*For Single Recored Only*/
        if($projectInstanceId){
            $projectInstancesByDistrict->where('pis.project_instance_id',$projectInstanceId);
        }
            
        return $projectInstancesByDistrict->where('api_1.status','Active')->where('pis.status' ,'Active')->orderBy('pis.project_instance_id','desc')->get()->toArray();
    }
    //End-->

    /*Return All Assigned Project Instance For Partner/Project Manager-Asad:start*/
    public static function getAllAssignedProjectInstancesForProjectManager($districtId=null,$role_user_id=null,$projectInstanceId =null)
    {
        $projectInstancesByDistrict = DB::table('pdms_project_instances AS pis')
        ->join('pdms_assigned_project_instances as api_1', 'pis.project_instance_id', '=', 'api_1.project_instance_id')
            
        ->join('com_country', 'api_1.country_id', '=', 'com_country.cont_id')
            
        ->join('pdms_district_operations as dop', 'dop.district_operation_id', '=', 'api_1.district_operation_id')
            
        ->join('pdms_district_operation_projects as dopp_1',function($join){
            $join->on('dopp_1.project_id' ,'=' ,'pis.project_id');
            $join->on('dopp_1.district_operation_id','=','api_1.district_operation_id');
        })


        ->join('pdms_project_users as pu' ,'pu.district_operation_project_id' ,'=' ,'dopp_1.district_operation_project_id')

        ->join('pdms_project_instance_document_templates as pidt' ,'pidt.project_instance_assigned_id','=','api_1.project_instance_assigned_id')
            
        ->join('pdms_document_templates as dt' ,'dt.document_template_id','=','pidt.document_template_id');
            
        $projectInstancesByDistrict->select('pis.*' , 'com_country.*' ,'dop.district_operation_full_name','dop.district_operation_short_name' ,'api_1.*' ,'dt.document_template_id' ,'dt.document_template_title','pidt.*' ,'pu.project_user_id','pu.status as pu_status');    

        if($projectInstanceId){
            $projectInstancesByDistrict->where('pis.project_instance_id',$projectInstanceId);    
        }
        
        return $projectInstancesByDistrict->where('api_1.district_operation_id',$districtId)->where('pu.role_user_id',$role_user_id)
            ->where('pu.status' ,'Active')->where('dopp_1.status' ,'Active')
            ->where('pis.status' ,'Active')
            ->orderBy('pis.project_instance_id','desc')->get()->toArray();

    }
    //End-->

    

     

}
