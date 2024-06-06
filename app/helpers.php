<?php
	/*Get Current User Active Roles-Asad:start*/
	if (!function_exists('userActiveRoles')){

		function userActiveRoles($userID){
	        $userAssignedRoles = DB::table('pdms_users as u')->join('pdms_role_user as ru','u.user_id','=','ru.user_id')
	        ->join('pdms_roles as r','r.role_id','=','ru.role_id')
	        ->leftJoin('com_country as c','c.cont_id','=','ru.country_id')
	        ->leftJoin('pdms_district_operations as dop', 'dop.district_operation_id','=','ru.district_operation_id')
	        ->where('u.user_id', $userID)->where('ru.status' ,'Active')
	        
	        ->select('r.role_id','r.role','c.cont_id', 'c.cont_name' ,'ru.role_user_id','ru.status As role_status','dop.district_operation_id','dop.district_operation_short_name')
	        
	        ->orderBy('ru.role_id' , 'asc')
	        ->get()->toArray();
	        
	        
	    	$userRoles = array();
	        foreach ($userAssignedRoles as $key => $role) {
	        	$userRoles[$role->role_user_id] = array(
	        		'role_id'=>$role->role_id,
	        		'role'=>$role->role,
	        		'cont_id'=>$role->cont_id,
	        		'cont_name'=>$role->cont_name,
	        		'dop_id'=>$role->district_operation_id,
	        		'dop_name'=>$role->district_operation_short_name,
	        		'role_user_id'=>$role->role_user_id,
	        		'role_status'=>$role->role_status
	        		);
	        }

	        return $userRoles;
	    }
	}
	/*Get Current User Active Roles-Asad:end*/


	if (!function_exists('checkIfQuestionAlreadyAssigned'))
	{
		function checkIfQuestionAlreadyAssigned($question_id=null)
	    {
	    	$data['question_section_rows'] = DB::table('pdms_questions')
	            ->join('pdms_question_section', 'pdms_questions.question_id', '=', 'pdms_question_section.question_id')
	            ->where('pdms_questions.question_id','=',$question_id)
	            ->where('pdms_question_section.status','=',1)
	            ->count();
	            
	        $data['document_template_questions_rows'] = DB::table('pdms_questions')
	            ->join('pdms_document_template_questions', 'pdms_questions.question_id', '=', 'pdms_document_template_questions.question_section_id')
	            //->where('pdms_document_template_questions.is_section_question','=',0)
	            ->where('pdms_questions.question_id','=',$question_id)
	            //->where('pdms_document_template_questions.status','=',1)
	            ->count();
	                

	            return $data;   
	    }
	}

	/*Get User And Role Data By Given Role User ID-Asad:start*/
	if (!function_exists('getUserAndRoleByRoleUserId')){
		function getUserAndRoleByRoleUserId($roleUserId){

			$user = DB::table('pdms_users AS U')
			->join('pdms_role_user AS RU' , 'U.user_id' ,'=' , 'RU.user_id')
			->join('pdms_roles AS R' , 'R.role_id' ,'=' , 'RU.role_id')
			->select('*')
			->where('RU.role_user_id' ,$roleUserId)->get()->toArray();
		
			return $user;
		}
	}	
	/*Get User And Role Data By Given Role User ID-Asad:end*/


/*Get Country Name By Country ID*/
if (!function_exists('getCountryNameByCountryId'))
{
		function getCountryNameByCountryId($country_id=null)
	    {
	    	$data = DB::table('com_country')
	            ->where('com_country.cont_id','=',$country_id)
	            ->get();

	            return $data;   
	    }
}

/*Get Distrcit Operation Name By District Operation ID*/
if (!function_exists('getDistrcitOperationNameByDistrcitOperationId'))
{
		function getDistrcitOperationNameByDistrcitOperationId($district_operation_id)
	    {
	    	$data = DB::table('pdms_district_operations')
	            ->where('pdms_district_operations.district_operation_id','=',$district_operation_id)
	            ->get();

	            return $data;   
	    }
}


	if (!function_exists('getProjectNameByProjectId'))
	{
		function getProjectNameByProjectId($project_id=null)
	    {
	    	$data = DB::table('com_project')
	            ->where('com_project.proj_id','=',$project_id)
	            ->get();
	            
	            return $data;   
	    }
	}


	if (!function_exists('countTotalProjectInstanceDocumentTemplateSubmissions'))
	{
		function countTotalProjectInstanceDocumentTemplateSubmissions($project_instance_document_template_id,$project_instance_assigned_id)
		{
			/*$data = DB::table('pdms_project_instance_submissions')
	            ->where('project_instance_document_template_id','=',$project_instance_document_template_id)
	            ->count();*/
	            

	        $data = DB::table('pdms_project_instance_submissions As pis')
	        	->join("pdms_project_instance_document_templates AS pidt","pis.project_instance_document_template_id", "=", "pidt.project_instance_document_template_id")
	        	->join("pdms_assigned_project_instances As api","pidt.project_instance_assigned_id","=","api.project_instance_assigned_id")
	            ->where('pis.project_instance_document_template_id','=',$project_instance_document_template_id)
	            ->where('api.project_instance_assigned_id','=',$project_instance_assigned_id)
	            ->count();

	            return $data; 
		}
	}

	if (!function_exists('getUserProjectsByRoleUserID')){
		function getUserProjectsByRoleUserID($role_user_id)
		{
			$userProjects= DB::table('com_project')
	              ->join("pdms_district_operation_projects As dop",'com_project.proj_id','=','dop.project_id')
	              ->join('pdms_project_users As pu','dop.district_operation_project_id','=','pu.district_operation_project_id')
	              ->where('pu.role_user_id',$role_user_id)->get()->toArray();

			return ($userProjects);
		}	
	}
	
	/*Check OverAll Project Instance Document Template Submission Status By Given project_instance_id-Asad:start*/
	if (!function_exists('overallProjectInstanceDocumentTemplatesSubmissionStatus'))
	{
		function overallProjectInstanceDocumentTemplatesSubmissionStatus($project_instance_id)
		{
			$result = DB::table('pdms_project_instances as pis')
					->join('pdms_assigned_project_instances as api' ,'pis.project_instance_id' ,'=' ,'api.project_instance_id')
					->join('pdms_project_instance_document_templates as pidt','pidt.project_instance_assigned_id' ,'=' ,'api.project_instance_assigned_id')
					->join('pdms_document_templates as dt' ,'dt.document_template_id','=','pidt.document_template_id')
					->join('pdms_project_instance_submissions as piss','piss.project_instance_document_template_id','=','pidt.project_instance_document_template_id')
					->join('pdms_submission_status as ss','ss.submission_status_id','=','piss.status')

					->select('pidt.project_instance_document_template_id','pis.project_instance_id','dt.document_template_id','dt.document_template_title','ss.*' ,'piss.project_instance_submission_id')

	            	->where('pis.project_instance_id','=',$project_instance_id)
	            	->get()->toArray();
	            
	        if(empty($result)){
	        	return "Assigned";
	        }else{

	        	$projectInstanceSubmissionStatus = array();
	        	foreach ($result as $key => $data) {
	         		$projectInstanceSubmissionStatus['status'][$data->project_instance_id][$data->project_instance_document_template_id] = array('project_instance_document_template_id'=>$data->project_instance_document_template_id,'status'=>$data->status_type);
	         	}
	         	
	         	$CheckFlag = false;
	         	foreach ($projectInstanceSubmissionStatus['status'][$project_instance_id] as $key => $status) {
	         			
	         		if($status['status'] == 'Submitted' || $status['status'] == 'UnApproved By US Manager' || $status['status'] == 'UnApproved By Country Manager' || $status['status'] == 'UnApproved By Operation Manager'){
	         				
	         				$CheckFlag = true;
	         				break;
	         		}

	         	}

	         	if($CheckFlag){
	         		return 'InProcess';
	         	}else{
	         		return 'Completed';
	         	}
	        }    
	        
		}
	}
	//End-->

	/*Get Single Document Temaplate Submission Status By Given project_instance_document_template_id-Asad:start*/
	if (!function_exists('getSingleDocumentTemplatesSubmissionStatus'))
	{
		function getSingleDocumentTemplatesSubmissionStatus($project_instance_document_template_id =null){

			$document_template_sub_status = DB::table('pdms_project_instance_submissions as piss')
			->join('pdms_submission_status as ss','ss.submission_status_id','=','piss.status')
			
			->join('pdms_role_user as ru' ,'piss.submitted_by','=','ru.role_user_id')
			->join('pdms_users as u' ,'u.user_id' ,'=','ru.user_id')
			->join('pdms_roles as r' ,'r.role_id','=','ru.role_id')
			
			->orderBy('piss.project_instance_submission_id' ,'desc')->limit(1)
			->where('piss.project_instance_document_template_id',$project_instance_document_template_id)
			//->where('piss.submitted_by',session('current_role_user_id'))
			->select('u.first_name','u.last_name','r.role' ,'ss.status_type','ss.submission_status_id as status_id','piss.project_instance_submission_id','piss.project_instance_document_template_id' ,'piss.reviewed_by')
			->get()->toArray();

			return $document_template_sub_status;
		}
		
	}
	//End-->

	function generateQuestionMetaControl($question_data)
	{		
		$answers = $question_data['answers'];

		if($question_data['question_type_id']==1)
		{
			?>
				<input  type="text" name="answers[<?php echo $question_data['question_section_id']; ?>]" class="form-control" placeholder="Enter Answer !..." value="<?php echo (isset($answers[0]['answer'])?$answers[0]['answer']:'');?>">
			<?php		
		}
		else
		{
			if(!empty($question_data['question_meta']))
			{
				if($question_data['question_type_id']==2)
				{
					?>
					<select  class="form-control" name="answers[<?php echo $question_data['question_section_id']; ?>]">
						<option value="">-- Select Answer --</option>
						<?php
							foreach ($question_data['question_meta'] as $key => $item)
							{
								if($item['status']=='Active')
								{
									?>
										<option <?php if(isset($answers[0]['answer']) && $answers[0]['answer']==$item['value']){echo "selected";}?> value="<?php echo $item['value']?>"><?php echo $item['value'];?></option>
									<?php
								}
							}
						?>
					</select>	
					<?php	
				}
				else if($question_data['question_type_id']==3)
				{
					foreach ($question_data['question_meta'] as $key => $item)
					{
						if($item['status']=='Active')
						{
							?>
							<div class="col-sm-3">
								<div class="radio">
									<label>
										<input <?php if(isset($answers[0]['answer']) && $answers[0]['answer']==$item['value']){echo "checked";}?> name="answers[<?php echo $question_data['question_section_id']; ?>]" value="<?php echo $item['value'];?>" type="radio" class="ace">
										<span class="lbl">
											<?php echo $item['value'];?>
										</span>
									</label>
								</div>
							</div>
							<?php
						}
					}
				}
				else if($question_data['question_type_id']==4)
				{	
					$count_total_checked_checkboxes=0;
					foreach ($question_data['question_meta'] as $key => $item)
					{						
						if($item['status']=='Active')
						{
							?>
							<div class="col-sm-3">
								<div class="checkbox">
									<label>
										<input <?php foreach($answers as $answer){ if($answer['answer']==$item['value']){echo "checked";}}?> value="<?php echo $item['value'];?>" type="checkbox" class="ace checkbox_answer">
										
										<span class="lbl">
											<?php 
												echo $item['value'];
											?>
										</span>
										
										<input type="hidden"  value="<?php foreach($answers as $answer){ if($answer['answer']==$item['value']){echo $item['value'];}}?>" name="answers[<?php echo $question_data['question_section_id']; ?>][]">
										<?php 
										foreach($answers as $answer)
										{ 
											if($answer['answer']==$item['value']){
											?>
												<input type="hidden" name="old_answers[<?php echo $question_data['question_section_id']; ?>][<?php echo $answer['project_instance_document_template_answer_id'];?>]" value="<?php echo $item['value'];?>">
											<?php
											}
										}
										?>
									</label>
								</div>
							</div>
							<?php
						}		
					}
				}
			}
		}
	}
	function getAllFileTypes()
	{
		$file_types= DB::table('pdms_file_types')->get()->toArray();

		return ($file_types);
	}

	
	function getAllDistrictOperationsByCountryId($country_id)
	{
		$district_operations= DB::table('pdms_district_operations')->where("country_id",$country_id)->where("status",'Active')->get();

		return $district_operations;
	}
?>