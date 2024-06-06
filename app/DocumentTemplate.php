<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class DocumentTemplate extends Model
{
    //
    protected $table = 'pdms_document_templates';
    protected $primaryKey = 'document_template_id';
    protected $fillable = ['template_type_id','project_id','added_by','document_template_title','status'];

    /*Get All Document Templates With Total Section-Asad:start*/
	public static function getAllDocumentTemplates($roleUserID = null){
		
		$allSection = DB::table('pdms_document_templates AS DT')
		
		->join('pdms_template_types AS T','T.template_type_id','=','DT.template_type_id')
		->join('com_project AS P' , 'P.proj_id' , 'DT.project_id')
		->join('pdms_role_user AS RU' , 'RU.role_user_id' , '=' , 'DT.added_by')
		->join('pdms_users AS U' , 'U.user_id' , '=' , 'RU.user_id')
		->join('pdms_roles AS R' , 'R.role_id' , '=' , 'RU.role_id')
		->join('pdms_document_template_sections AS DTS' , 'DTS.document_template_id','=','DT.document_template_id')

		->select(DB::raw('DT.document_template_id, count(DTS.section_id) as total_section, DT.document_template_title , P.proj_name, T.template_type, DT.added_by, concat(U.first_name," ",U.last_name) as fullname , R.role, DT.status,DT.created_at'))
		
		->groupBy('DTS.document_template_id','DT.document_template_id', 'DT.document_template_title' ,'P.proj_name','T.template_type','U.first_name','U.last_name','R.role', 'DT.status','DT.created_at' , 'DT.added_by')->orderBy('DT.document_template_id','desc');

		if($roleUserID){
			$allSection->having('DT.added_by',$roleUserID);
		}

		return $allSection->get()->toArray();
			
	} 
	/*Get All Document Templates With Total Section-Asad:end*/


	/*Get Single Section With Total Question Detail-Asad:start*/
	public static function getDocumentTemplateDetail($templateID){
		
		$singleTemplate = DB::table('pdms_document_templates AS DT')
			->join('pdms_template_types AS T','T.template_type_id','=','DT.template_type_id')
			->join('com_project AS P' , 'P.proj_id' , 'DT.project_id')
			->join('pdms_role_user AS RU' , 'RU.role_user_id' ,'=' , 'DT.added_by')
			->join('pdms_users AS U' , 'U.user_id' , '=' , 'RU.user_id')
			->join('pdms_roles AS R' , 'R.role_id' , '=' , 'RU.role_id')
			->select(DB::raw('DT.*, concat(U.first_name," ",U.last_name) as fullname, R.role ,P.proj_name, T.template_type'))->where('DT.document_template_id' , $templateID)->get()->toArray();

		$data['singleTemplate'] = $singleTemplate;	
		
		$templateSection = DB::table('pdms_document_templates AS DT')
		->join('pdms_document_template_sections AS DTS' , 'DTS.document_template_id','=','DT.document_template_id')
		->join('pdms_sections As S' , 'S.section_id' , 'DTS.section_id')
		->select('S.*','DT.document_template_id' ,'DTS.priority As section_priority')
		->where('DT.document_template_id' , $templateID)->orderBy('DTS.priority','asc')
		->get()->toArray();		

		$data['templateSection'] = $templateSection;
		

		$templateSectionQuestions = DB::table('pdms_document_templates AS DT')
		->join('pdms_document_template_questions AS DTQ', 'DTQ.document_template_id','=','DT.document_template_id')
		->join('pdms_question_section As QS', 'QS.question_section_id' , 'DTQ.question_section_id')
		->join('pdms_sections As S', 'S.section_id','QS.section_id')
		->join('pdms_questions As Q', 'Q.question_id','QS.question_id')
		->select('S.section_title','DT.document_template_title','Q.question','QS.question_section_id','S.section_id',
			'Q.question_id' ,'QS.priority')
		->where('DT.document_template_id', $templateID)//->orderBy('DTS.priority','asc')
		->get()->toArray();		

		$data['templateSectionQuestions'] = $templateSectionQuestions;

		return $data;
	}
	/*Get Single Section With Total Question Detail-Asad:end*/


//     public static function getAllDocumentTemplateQuestionsByProjectInstanceSubmissionId($project_instance_submission_id)
//     {

//         $data = DB::select("SELECT q.question_id,q.question_type_id,q.question,qs.priority AS 'question_priority',pidta.answer,pidta.project_instance_submission_id,s.section_id,s.section_title,dtq.question_section_id,dts.priority,dts.document_template_id,pisf.feedback

//         FROM pdms_document_template_sections dts

//         INNER JOIN pdms_document_template_questions dtq ON dtq.document_template_id = dts.document_template_id

//         INNER JOIN pdms_question_section AS qs ON dtq.question_section_id = qs.question_section_id

//         INNER JOIN pdms_sections AS s ON qs.section_id = s.section_id

//         INNER JOIN pdms_questions AS q ON qs.question_id = q.question_id

//         INNER JOIN pdms_project_instance_document_templates AS pidt ON dtq.document_template_id = pidt.document_template_id

//         INNER JOIN pdms_project_instance_submissions AS pis ON pidt.project_instance_document_template_id = pis.project_instance_document_template_id

//         LEFT JOIN pdms_project_instance_document_template_answers AS pidta ON dtq.question_section_id = pidta.document_template_question_section_id

//         LEFT JOIN pdms_project_instance_submission_feedbacks AS pisf ON pis.project_instance_submission_id = pisf.project_instance_submission_id

//         WHERE qs.section_id = dts.section_id

//         AND pis.project_instance_submission_id = pidta.project_instance_submission_id 

//         AND pidta.project_instance_submission_id = $project_instance_submission_id

//         ORDER BY dts.priority,qs.priority ASC");

// 	   return $data;
// }	

    
    
}
