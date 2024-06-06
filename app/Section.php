<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Section extends Model
{
    protected $table = 'pdms_sections';
    protected $primaryKey = 'section_id';
    protected $fillable = ['added_by','section_title' , 'section_description' , 'status'];

    /*Asad-Code:start*/
    public function questions() {
        return $this->belongsToMany('App\Question','pdms_question_section','section_id', 'question_id');
    }

	/*Get All Section With Total Question-Asad:start*/
	public static function getAllSections($roleUserID = null){
		
		$allSection = DB::table('pdms_sections')->leftJoin('pdms_question_section' , 'pdms_question_section.section_id','=','pdms_sections.section_id')
			->leftJoin('pdms_questions' , 'pdms_question_section.question_id' , 'pdms_questions.question_id')
			->join('pdms_role_user' , 'pdms_role_user.role_user_id' ,'=' , 'pdms_sections.added_by')
			->join('pdms_users' , 'pdms_users.user_id' , '=' , 'pdms_role_user.user_id')
			->join('pdms_roles' , 'pdms_roles.role_id' , '=' , 'pdms_role_user.role_id')
			->select(DB::raw('pdms_sections.section_id, count(pdms_questions.question_id) as total_question, pdms_sections.section_title , pdms_sections.section_description , pdms_sections.status ,pdms_sections.added_by, concat(pdms_users.first_name," ",pdms_users.last_name) as fullname , pdms_roles.role ,pdms_sections.created_at'))
			->groupBy('pdms_sections.section_id' , 'pdms_sections.section_title' ,'pdms_sections.section_description','pdms_sections.status','pdms_users.first_name','pdms_users.last_name','pdms_roles.role' ,'pdms_sections.created_at' , 'pdms_sections.added_by')->orderBy('pdms_sections.section_id','desc');

		if($roleUserID){
			$allSection->having('pdms_sections.added_by',$roleUserID);
		}

		return $allSection->get()->toArray();
			
	} 
	/*Get All Section With Total Question-Asad:end*/

	/*Get Single Section With Total Question Detail-Asad:start*/
	public static function getSectionDetail($sectionID){
		$singleSection = DB::table('pdms_sections')
			->join('pdms_role_user' , 'pdms_role_user.role_user_id' ,'=' , 'pdms_sections.added_by')
			->join('pdms_users' , 'pdms_users.user_id' , '=' , 'pdms_role_user.user_id')
			->join('pdms_roles' , 'pdms_roles.role_id' , '=' , 'pdms_role_user.role_id')
			->select(DB::raw('pdms_sections.*, concat(pdms_users.first_name," ",pdms_users.last_name) as fullname , pdms_roles.role'))->where('pdms_sections.section_id' , $sectionID)->get()->toArray();

		$data['singleSection'] = $singleSection;	
		
		$sectionQuestions = DB::table('pdms_sections')
		->leftJoin('pdms_question_section' , 'pdms_question_section.section_id','=','pdms_sections.section_id')
		->leftJoin('pdms_questions' , 'pdms_question_section.question_id' , 'pdms_questions.question_id')
		->select('pdms_questions.*','pdms_question_section.priority' ,'pdms_question_section.status as question_status')
		->where('pdms_sections.section_id' , $sectionID)->orderBy('pdms_question_section.priority' ,'asc')
		->get()->toArray();		

		$data['sectionQuestions'] = $sectionQuestions;
		return $data;
	}
	/*Get Single Section With Total Question Detail-Asad:end*/

	
	/*Get Single Section With Total Active Questions-Asad:start*/
	public static function getSectionActiveQuestions($sectionID){
		$singleSection = DB::table('pdms_sections')
			->join('pdms_role_user' , 'pdms_role_user.role_user_id' ,'=' , 'pdms_sections.added_by')
			->join('pdms_users' , 'pdms_users.user_id' , '=' , 'pdms_role_user.user_id')
			->join('pdms_roles' , 'pdms_roles.role_id' , '=' , 'pdms_role_user.role_id')
			->select(DB::raw('pdms_sections.*, concat(pdms_users.first_name," ",pdms_users.last_name) as fullname , pdms_roles.role'))->where('pdms_sections.section_id' , $sectionID)->get()->toArray();

		$data['singleSection'] = $singleSection;	
		
		$sectionQuestions = DB::table('pdms_sections')
		->leftJoin('pdms_question_section' , 'pdms_question_section.section_id','=','pdms_sections.section_id')
		->leftJoin('pdms_questions' , 'pdms_question_section.question_id' , 'pdms_questions.question_id')
		->select('pdms_questions.*' ,'pdms_question_section.question_section_id','pdms_question_section.priority' ,'pdms_question_section.status as question_status')
		->where('pdms_sections.section_id' , $sectionID)->where('pdms_question_section.status','Active')->orderBy('pdms_question_section.priority' ,'asc')
		->get()->toArray();		

		$data['sectionQuestions'] = $sectionQuestions;
		return $data;
	}
	/*Get Single Section With Total Active Questions-Asad:end*/

	/*Asad-Code:end*/

}
