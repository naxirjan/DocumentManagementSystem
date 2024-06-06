<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectInstanceDocumentTemplate extends Model
{
    //

    protected $table 		= 'pdms_project_instance_document_templates';
    protected $primaryKey 	= 'project_instance_document_template_id';

    protected $fillable 	= ["document_template_id","project_instance_assigned_id","project_instance_submission_start_date","project_instance_submission_stop_date","status"];



     public static function getAllProjectInstanceDocumentTemplatesByProjectInstanceID($project_instance_id)
    {
        $result = DB::table('pdms_assigned_project_instances')
            
            ->join('pdms_project_instance_document_templates', 'pdms_assigned_project_instances.project_instance_assigned_id', '=', 'pdms_project_instance_document_templates.project_instance_assigned_id')
            
            ->join('pdms_document_templates', 'pdms_project_instance_document_templates.document_template_id', '=', 'pdms_document_templates.document_template_id')
            
            ->where('pdms_assigned_project_instances.project_instance_id','=',$project_instance_id)
             ->select(
             	"pdms_document_templates.document_template_id",
                "pdms_document_templates.template_type_id",
             	"pdms_document_templates.project_id",
             	"pdms_document_templates.document_template_title",
             	"pdms_project_instance_document_templates.project_instance_document_template_id", 
             	"pdms_project_instance_document_templates.project_instance_assigned_id",
             	"pdms_project_instance_document_templates.project_instance_submission_start_date",
             	"pdms_project_instance_document_templates.project_instance_submission_stop_date",
             	"pdms_project_instance_document_templates.created_at As document_template_assigned_date",
             	"pdms_project_instance_document_templates.status As document_template_assigned_status")
            ->get()
            ->toArray();

            return $result;
    }


    public static function getAllProjectInstanceDocumentTemplatesForSubmissionByProjectInstanceId($project_instance_id)
    {
        $data = DB::table('pdms_document_templates AS dt')
        ->join('pdms_project_instance_document_templates As pidt', 'dt.document_template_id','=','pidt.document_template_id')
        ->join('pdms_assigned_project_instances AS api', 'pidt.project_instance_assigned_id','=','api.project_instance_assigned_id')
        ->select('dt.document_template_id','dt.document_template_title','pidt.project_instance_document_template_id','pidt.project_instance_assigned_id','api.project_instance_id')
        ->where('dt.status', 'Active')
        ->where('pidt.status', 'Active')
        ->where('api.status', 'Active')
        ->where("api.country_id",session('current_cont_id'));
        if(session('current_dop_id')){
            $data->where("api.district_operation_id",session('current_dop_id'));
        }
        
       return $data->where('api.project_instance_id', $project_instance_id)
        ->get()->toArray();   
    }



    public static function getAllProjectInstanceDocumentTemplateSectionQuestions($project_instance_document_template_id)
    {

        $data = DB::select("SELECT q.question_id,q.question_type_id,q.question,qs.priority AS 'question_priority',qs.question_section_id,s.section_id,s.section_title,dts.priority

            FROM pdms_document_template_sections dts

            INNER JOIN pdms_document_template_questions dtq ON dtq.document_template_id = dts.document_template_id

            INNER JOIN pdms_question_section AS qs ON dtq.question_section_id = qs.question_section_id

            INNER JOIN pdms_sections AS s ON qs.section_id = s.section_id

            INNER JOIN pdms_questions AS q ON qs.question_id = q.question_id

            INNER JOIN pdms_project_instance_document_templates AS pidt ON dtq.document_template_id = pidt.document_template_id

            WHERE qs.section_id = dts.section_id

            AND qs.status = 'Active' 

            AND pidt.project_instance_document_template_id =$project_instance_document_template_id

            ORDER BY dts.priority,qs.priority ASC");

        return $data;
    }


    public static function getProjectInstanceDocumentTemplateQuestionAnswer($project_instance_submission_id,$document_template_question_section_id)
    {
        $data = DB::table('pdms_project_instance_document_template_answers As pidta')

        ->leftjoin('pdms_project_instance_submission_feedbacks AS pisf', 'pidta.project_instance_submission_id','=','pisf.project_instance_submission_id')

        ->select('dt.document_template_id','dt.document_template_title','pidt.project_instance_document_template_id','pidt.project_instance_assigned_id','api.project_instance_id')

        ->where('pidta.document_template_question_section_id', $document_template_question_section_id)
        
        ->where('pidta.project_instance_submission_id', $project_instance_submission_id)

        ->get()->toArray();

        return $data;
    }

}
