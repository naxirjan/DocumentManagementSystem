<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;



use DB;
use Auth;
use Hash;
use Session;
use Redirect;
use Validator;

use App\User;
use App\Role;
use App\Country;
use App\Section;
use App\Project;
use App\RoleUser;
use App\Question;
use App\ProjectUser;
use App\QuestionType;
use App\QuestionMeta;
use App\TemplateType;
use App\ProjectInstance;
use App\DocumentTemplate;
use App\DistrictOperation;
use App\AssignedProjectInstance;
use App\DocumentTemplateSection;
use App\DocumentTemplateQuestion;
use App\ProjectInstanceSubmission;
use App\ProjectInstanceDocumentTemplate;
use App\ProjectInstanceSubmissionAttachment;
use App\ProjectInstanceDocumentTemplateAnswer;



class ProjectManagerController extends Controller
{
    public function __construct(){
    	$this->middleware( ['ProjectManager','deactive'] );
    }

    /*Dashboard-Asad:start*/
    public function index(){
        
        $projectInstances = $this->getAssignedProjectInstancesForProjectManager();
        return view('projectManager/index',['project_instances'=>$projectInstances]);
    }
    //End-->
    
    /*View User Profile-Asad:start*/
    public function viewProfile($user_id)
    {
        try{
            $user_id = Crypt::decryptString($user_id);
        }catch (DecryptException $e){
            abort(404);
        }
        $userData = User::where('user_id',$user_id)->get()->toArray();
        $userAssignedRoles = User::getUserAssignedRoles($user_id);
        $user_role_id = array();
        foreach ($userAssignedRoles as $value) {
            $user_role_id[] = $value->role_user_id; 
        }

        $userAssignedProjects = ProjectUser::getUserProjects($user_role_id);

        if(!empty($userData)){
            
            $userRoles = array();
            foreach ($userAssignedRoles as $key => $role) {
                if($role->role_id == 1){
                    $userRoles[$role->role]['countries']['USA'][] = array('role_id'=>$role->role_id,
                                                        'role'=>$role->role,
                                                        'role_user_id'=>$role->role_user_id,
                                                        'cont_name'=>'USA',
                                                        'role_status'=>$role->role_status
                                                        );

                    $userRoles[$role->role]['dop'][] = array('role_id'=>0);
                    $userRoles[$role->role]['status'] = array('role_status'=>$role->role_status);
                }else{
                    $userRoles[$role->role]['countries'][$role->cont_name][] = array('role_id'=>$role->role_id,
                                                        'role'=>$role->role,
                                                        'cont_id'=>$role->cont_id,
                                                        'cont_name'=>$role->cont_name,
                                                        'role_user_id'=>$role->role_user_id,
                                                        'role_status'=>$role->role_status
                                                        );

                    $userRoles[$role->role]['dop'][] = array('role_id'=>$role->role_id,
                                                        'role'=>$role->role,
                                                        'dop_id'=>$role->district_operation_id,
                                                        'dop_name'=>$role->district_operation_short_name,
                                                        'role_user_id'=>$role->role_user_id,
                                                        'role_status'=>$role->role_status
                                                        );

                    $userRoles[$role->role]['status'] = array('role_status'=>$role->role_status);
                }
                
            }
            $userData = $userData[0];
            return view('projectManager/viewProfile' , ['userAssignedRoles'=> $userRoles,'userData'=>$userData,'user_id'=>$user_id,'userAssignedProjects'=>$userAssignedProjects]);            
        }
        else{
            abort(404);
        }
    }
    /*View User Profile-Asad:end*/    

    /*Code By Abdul Ghani Start --Change Password--*/
    public function verifyPassword(Request $request){
        try {
            $user_id = Crypt::decryptString($request->userId);
        } catch (DecryptException $e) {
            abort(404);
        }
         
        $userId = User::where('user_id','=',$user_id)->get(['password']);
        if(!empty($userId[0]->password)){
          echo Hash::check($request->oldPassword,$userId[0]->password);  
            
        }
    }

    public function changePassword(Request $request){

        $controls = $request->all();
        if(isset($controls['oldPassword'])){
            $rules = array(
            'oldPassword' => 'required',
            'newPassword' => 'required|alpha_dash|min:7',
            'confirmPassword' => 'required_with:newPassword|same:newPassword|min:7',
            );
        }
        else{
            $rules = array(
            'newPassword' => 'required|alpha_dash|min:7',
            'confirmPassword' => 'required_with:newPassword|same:newPassword|min:7',
            );
        }

        $validator = Validator::make($controls,$rules);
        if($validator->fails()){
            return 0;
        }
        else{
            $data = array(
                'password' => Hash::make($request->confirmPassword),
            );
            try {
            $user_id = Crypt::decryptString($request->userId);
            } catch (DecryptException $e) {
                abort(404);
            }
            $result = User::where('user_id','=',$user_id)->update($data);
            if(isset($result)){
                return 1;    
            }
            
        }
    }


/*Manage Project Instance*/
    
    /*View Project Instance Detail*/
    public function viewProjectInstanceDetail($projectInstanceId){
        
        $flagExist = ProjectInstance::find($projectInstanceId);    
        if(!($flagExist))
        {
            return Redirect::back();
        }
        $projectInstances = ProjectInstance::getSingleProjectInstancesByCountryDistrictAndRole($projectInstanceId);
        
        $data['project_intance'] = array();
        foreach ($projectInstances as $key => $instance) {
            $data['project_intance'][ $instance->project_instance_id ] = array('project_instance_title' =>$instance->project_instance_title,'project_instance_description'=>$instance->project_instance_description,'start_date'=>$instance->project_instance_start_date ,'end_date'=>$instance->project_instance_end_date ,'status' =>$instance->status ,'created_at'=>$instance->created_at ,'added_by'=>$instance->added_by ,'project_instance_id'=>$instance->project_instance_id ,'project_id'=>$instance->project_id);
            
            $data['country'][ $instance->project_instance_id ][] = array('country'=>$instance->cont_name ,'dop_full'=>$instance->district_operation_full_name ,'dop_short'=>$instance->district_operation_short_name ,'country_status'=>$instance->assigned_country_status);
        }
        return view('projectManager/viewProjectInstanceDetail' ,['project_instance_detail'=>$data ]);
    }
    //End-->


    /*View Assigned Project Instance*/
    public function viewAssignedProjectInstances(){
        
        $projectInstances = $this->getAssignedProjectInstancesForProjectManager();        
        return view('projectManager/viewAssignedProjectInstances',['project_instances'=>$projectInstances]);
    }
    //End-->

    public function getAssignedProjectInstancesForProjectManager($projectInstanceId=null){
        $roleUserId   = session()->get('current_role_user_id');
        $currentUser  = getUserAndRoleByRoleUserId($roleUserId);
        $countryId    = $currentUser[0]->country_id;
        $districtId   = $currentUser[0]->district_operation_id;
        
        if($projectInstanceId){
            $projectInstances = ProjectInstance::getAllAssignedProjectInstancesForProjectManager($districtId,$roleUserId,$projectInstanceId);
        }else{
            $projectInstances = ProjectInstance::getAllAssignedProjectInstancesForProjectManager($districtId,$roleUserId);    
        }
        
        $data['project_instance'] = array();
        foreach ($projectInstances as $key => $instance)
        {
            $data['project_instance'][ $instance->project_instance_id ] = array(
                'project_instance_title'                => $instance->project_instance_title,
                'start_date'                            => $instance->project_instance_start_date ,
                'end_date'                              => $instance->project_instance_end_date ,
                'status'                                => $instance->status ,
                'created_at'                            => $instance->created_at ,
                'added_by'                              => $instance->added_by ,
                'project_instance_id'                   => $instance->project_instance_id ,
                'project_instance_assigned_id'          => $instance->project_instance_assigned_id,
                'project_id'                            => $instance->project_id ,
                'dop_id'                                => $instance->district_operation_id ,
                'cont_id'                               => $instance->cont_id);
            
            $data['assigned_document_template_count'][$instance->project_instance_id][$instance->document_template_id] = array(
                'document_template_id'                  => $instance->document_template_id ,
                'document_template_title'               => $instance->document_template_title ,
                'start_date'                            => $instance->project_instance_submission_start_date ,
                'end_date'                              => $instance->project_instance_submission_stop_date,
                'project_instance_document_template_id' => $instance->project_instance_document_template_id);
        }
        
        return $data;
    }
    //End-->

    /*View Assigned Project Instance Detail-Asad:start*/
    public function viewAssignedProjectInstanceDetail($projectInstanceId=null){
        
        if(!(isset($projectInstanceId) && is_numeric($projectInstanceId))){
            
           abort('404');
        }
        
        $validData = ProjectInstance::find($projectInstanceId);
        if(!($validData)){
            abort('404');   
        }
        
        $projectInstances = $this->getAssignedProjectInstancesForProjectManager($projectInstanceId);
        
        return view('projectManager/viewAssignedProjectInstanceDetail',['project_instance'=>$projectInstances,'projectInstanceId'=>$projectInstanceId]);
    }
    //End-->

/*Manage Project Instance*/

/*Manage Document Template Submissions*/
    
    /*Submit Document Template (Get Project Instance Detail & Project Instance All Document Templates)*/
    public function submitDocumentTemplate($project_instance_id=0)
    {    
        if(empty($project_instance_id) || !is_numeric($project_instance_id))
        {
            abort(404);
        }
         
        $project_instance_detail = ProjectInstance::where('project_instance_id','=',$project_instance_id)
                                                   ->with(['project_instance_countries','project'])->get()->toArray();
        if(count($project_instance_detail)==0)
        {
            abort(404);
        }
            
        $project_instance_document_templates = ProjectInstanceDocumentTemplate::getAllProjectInstanceDocumentTemplatesForSubmissionByProjectInstanceId($project_instance_id);

        $data["project_instance_detail"]=$project_instance_detail;
        $data["project_instance_document_templates"] = $project_instance_document_templates;
        return view('projectManager/submitDocumentTemplate',$data);
    }
    /*Submit Document Template (Get Project Instance Detail & Project Instance All Document Templates)*/
    
    
    /* Get Document Template Section Questions)*/
    public function loadProjectInstanceDocumentTemplateSectionQuestions($project_instance_document_template_id)
    {
        
        $check_project_instance_submission = ProjectInstanceSubmission::where('project_instance_document_template_id',$project_instance_document_template_id)
           // ->where("submitted_by",session('current_role_user_id'))
            ->orderBy('project_instance_submission_id','DESC')
            ->limit(1)
            ->get()->toArray();
        
        if(count($check_project_instance_submission)>0)
        {
            extract($check_project_instance_submission[0]);
            $user_data = getUserAndRoleByRoleUserId($submitted_by);
            $full_name_submitted_by = $user_data[0]->first_name.' '.$user_data[0]->last_name.' ('.$user_data[0]->role.')';
        }
        else
        {
            $full_name_submitted_by=null;
            $status=null;
            $project_instance_submission_id=0;
        }
        
        $data = array(
                "submitted_by"                       => $submitted_by,
                "full_name_submitted_by"             => $full_name_submitted_by,
                "project_instance_submission_status" => $status,
                "project_instance_submission_id"     => $project_instance_submission_id);
        
            /*Check If Project Instance Submission Status Is (Saved Or UnApproved By US Manager)*/        
            if(($status == 0 && $submitted_by == session('current_role_user_id')) || $status == 5 || $status == 7)
            {
               $template_section_questions = ProjectInstanceDocumentTemplate::getAllProjectInstanceDocumentTemplateSectionQuestions($project_instance_document_template_id);

                if(count($template_section_questions)>0)
                {
                    foreach($template_section_questions as $template)
                    {
                        $question_meta = QuestionMeta::where("question_id",$template->question_id)->get()->toArray();

                        $question_answers = ProjectInstanceDocumentTemplateAnswer::where('project_instance_submission_id',$project_instance_submission_id)->where('document_template_question_section_id',$template->question_section_id)->get()->toArray();

                        $data['sections_with_questions'][$template->section_id][$template->priority."_".$template->section_title][$template->question_id]=array(
                            "question_id"                       => $template->question_id,
                            "question_type_id"                  => $template->question_type_id,
                            "question"                          => $template->question,
                            "question_section_id"               => $template->question_section_id,
                            "section_id"                        => $template->section_id,
                            "question_meta"                     => $question_meta,
                            "answers"                           => (count($question_answers)>0?$question_answers:[])
                        );
                    }
                } 
                
                $data['attachments'] = ProjectInstanceSubmissionAttachment::where('project_instance_submission_id',$project_instance_submission_id)->get(); 
                
                
            }
        
            return view('projectManager/includes/loadProjectInstanceDocumentTemplateForSubmissionContent',$data);
    }
    /* Get Document Template Section Questions)*/
    
    /*Get Media File Attachment Control Content*/
    public function loadFileAttachmentControlContent()
    {
        return view('projectManager/includes/loadFileAttachmentControlContent');
    }
    /*Get Media File Attachment Control Content*/
    
    
    /*Delete Project Instance Submission Attachment File*/
    public function deleteProjectInstanceSubmissionAttachment(Request $request)
    {
        $controls = $request->all();
        
         $delete = ProjectInstanceSubmissionAttachment::where("project_instance_submission_attachment_id",$controls['project_instance_submission_attachment_id'])->delete();
        
        if($delete)
        {
            $file_path = "storage/FileAttachments/".$controls['project_instance_submission_id']."/".$controls['file_path'];
            
            if(File::exists($file_path))
            { 
                File::delete($file_path);
                
                $directory = "public/FileAttachments/".$controls['project_instance_submission_id'];
                $total_remaining_files = Storage::files($directory);
                
                if(count($total_remaining_files)==0)
                {
                    Storage::deleteDirectory($directory);   
                }
            }
            
            $data['attachments'] = ProjectInstanceSubmissionAttachment::where('project_instance_submission_id',$controls['project_instance_submission_id'])->get(); 

            $data['project_instance_submission_status'] = $controls['project_instance_submission_status'];
            return view('projectManager/includes/loadFileAttachmentControlContent',$data);            
        }
    }
    /*Delete Project Instance Submission Attachment File*/
    
    
    /*Submit Document Template Process (Add All Document Template Section Question ANswers)*/
    public function submitDocumentTemplateProcess(Request $request)
    {
        $controls = $request->all();
        extract($controls);
       
        if(!isset($old_answers))
        {
            $old_answers=[];
        }
            
        $project_instance_submission_data = array(
            "project_instance_document_template_id"=>$project_instance_document_template_id,
            "submitted_by"      => session('current_role_user_id'),
            "submitted_on"      => date("Y-m-d h:i:s")
        );
        
        /*If SUBMIT Button Is Clicked*/
        if(isset($controls['submit']))
        {
            /*Check If Project Instance Submission Status Is Saved*/
            if(is_numeric($project_instance_submission_status) && $project_instance_submission_status==0)
            {
                $update_status = ProjectInstanceSubmission::where("project_instance_submission_id",$project_instance_submission_id)->where("status",0)->update(["status"=>1]);
                if($update_status)
                {
                    
                    /*Custom Function -> Manage Project Submission File Attachments*/
                    $this->manageFileAttachments($controls,"pending_saved_submission");
                    /*Custom Function -> Manage Project Submission File Attachments*/
                    
                    foreach($answers as $question_section_id => $new_answer)
                    {
                        if(!empty($new_answer))
                        {   
                            /* Checkbox Answers Start */
                            if(is_array($new_answer))
                            {
                                $count_new_answers=0;
                                $count_old_answers=( isset($old_answers[$question_section_id]) ? count($old_answers[$question_section_id]) :0 );
                
                                /*Count Total New Answers(Checkboxes)*/
                                foreach($new_answer as $new_answer_id => $ans)
                                {
                                    if(!empty($ans))
                                    {
                                         $count_new_answers++;
                                    }
                                }

                                /*Insert All New Checkbox Answers*/
                                if($count_old_answers == 0 && $count_new_answers > 0)
                                {
                                    foreach($new_answer as $new_answer_id => $ans)
                                    {
                                        if(!empty($ans))
                                        {
                                            $project_instance_document_template_answer_data = array(
                                            "document_template_question_section_id" => $question_section_id,
                                            "project_instance_submission_id"        => $project_instance_submission_id,
                                            "answer"                                => $ans,
                                            "status"                                => 'Active');
                                            $insert =  ProjectInstanceDocumentTemplateAnswer::create($project_instance_document_template_answer_data);  
                                        }
                                    } 
                                }
                               /*Delete All Old Checkbox Answers*/
                                else if($count_old_answers > 0 && $count_new_answers == 0)
                                {                            
                                    foreach($old_answers[$question_section_id] as $old_answer_id => $old_answer)
                                    {
                                        $delete =  ProjectInstanceDocumentTemplateAnswer::where("document_template_question_section_id",$question_section_id)
                                            ->where("project_instance_submission_id",$project_instance_submission_id)
                                            ->delete();
                                    }
                                }
                               /*Insert New Checkbox Answers  One By One*/
                                else if($count_old_answers < $count_new_answers)
                                {
                                    foreach($new_answer as $new_answer_id => $ans)
                                    {
                                        if(!empty($ans))
                                        {
                                            if(!in_array($ans, $old_answers[$question_section_id], true))
                                            {
                                                $project_instance_document_template_answer_data = array(
                                                "document_template_question_section_id" => $question_section_id,
                                                "project_instance_submission_id"        => $project_instance_submission_id,
                                                "answer"                                => $ans,
                                                "status"                                => 'Active');
                                                $insert =  ProjectInstanceDocumentTemplateAnswer::create($project_instance_document_template_answer_data);
                                            }
                                        }
                                    }
                                }
                               /*Delete Old Checkbox Answers One By One*/
                                else if($count_old_answers > $count_new_answers)
                                {
                                    foreach($old_answers[$question_section_id] as $old_answer_id => $old_answer)
                                    {
                                        if(!in_array($old_answer, $new_answer, true))
                                        {
                                            $delete =  ProjectInstanceDocumentTemplateAnswer::where("document_template_question_section_id",$question_section_id)
                                                ->where("project_instance_submission_id",$project_instance_submission_id)
                                                ->where("answer",$old_answer)
                                                ->delete();
                                        }
                                    }
                                }

                             /* Checkbox Answers End */
                            }
                            else
                            {
                                $project_instance_document_template_answer_data = array(
                                "document_template_question_section_id" => $question_section_id,
                                "project_instance_submission_id"        => $project_instance_submission_id,
                                "answer"                                => $new_answer,
                                "status"                                => 'Active' );
                            
                                $update =  ProjectInstanceDocumentTemplateAnswer::updateOrCreate(
                                        [
                                            "document_template_question_section_id" => $question_section_id,
                                            "project_instance_submission_id"        => $project_instance_submission_id
                                        ],
                                        $project_instance_document_template_answer_data
                                    );
                            }
                        }
                    }
                    return Redirect::back()->with('msg_success','Document Template Submitted Successfully !...');
                }
                else
                {
                  return Redirect::back()->with('msg_fail','Some Error Occured, Please Try Again Later !...');  
                }
            }
            /*Check If Project Instance Submission Status Is UnAprroved Or Not Submitted*/
            else if($project_instance_submission_status==7 || empty($project_instance_submission_status))
            { 
                $project_instance_submission_data['status'] = 1;
                $controls['old_project_instance_submission_id']=$controls['project_instance_submission_id'];

                $get_project_instance_submission_id =  ProjectInstanceSubmission::create($project_instance_submission_data)->project_instance_submission_id;
                
                if($get_project_instance_submission_id)
                {
                
                    $controls['project_instance_submission_id']=$get_project_instance_submission_id;
                    /*Custom Function -> Manage Project Submission File Attachments*/
                    $this->manageFileAttachments($controls,"insert_new_submission");
                    /*Custom Function -> Manage Project Submission File Attachments*/
                    
                    foreach($answers as $question_section_id => $answer)
                    {
                        if(!empty($answer))
                        {
                            /* Checkbox Answers Start */
                            if(is_array($answer))
                            {
                                foreach($answer as $key => $ans)
                                {
                                    $project_instance_document_template_answer_data = array(
                                        "document_template_question_section_id"=>$question_section_id,
                                        "project_instance_submission_id"=>$get_project_instance_submission_id,
                                        "answer" => $ans,
                                        "status" => 'Active' 
                                    );

                                   $update =  ProjectInstanceDocumentTemplateAnswer::create($project_instance_document_template_answer_data);
                                }
                            }
                            /* Checkbox Answers End */
                            else
                            {
                                $project_instance_document_template_answer_data = array(
                                "document_template_question_section_id"=>$question_section_id,
                                "project_instance_submission_id"=>$get_project_instance_submission_id,
                                "answer" => $answer,
                                "status" => 'Active' 
                            );

                            $update =  ProjectInstanceDocumentTemplateAnswer::create($project_instance_document_template_answer_data);
                            }
                        }
                    }
                    return Redirect::back()->with('msg_success','Document Template Submitted Successfully !...');
                }
                else
                {
                  return Redirect::back()->with('msg_fail','Some Error Occured, Please Try Again Later !...');  
                }   
            }
        }
        /*If SAVE Button Is Clicked*/
        else if(isset($controls['save']))
        {   
            $flag=false;
            /*Check If Project Instance Submission Status Is Saved*/
            if(is_numeric($project_instance_submission_status) && $project_instance_submission_status==0)
            {
                /*Custom Function -> Manage Project Submission File Attachments*/
                $this->manageFileAttachments($controls,"pending_saved_submission");
                /*Custom Function -> Manage Project Submission File Attachments*/
                
                foreach($answers as $question_section_id => $new_answer)
                {
                   if(!empty($new_answer))
                   {
                        /* Checkbox Answers Start */
                        if(is_array($new_answer))
                        {    
                            $count_new_answers=0;
                            $count_old_answers=( isset($old_answers[$question_section_id]) ? count($old_answers[$question_section_id]) :0 );

                            /*Count Total New Answers(Checkboxes)*/
                            foreach($new_answer as $new_answer_id => $ans)
                            {
                                if(!empty($ans))
                                {
                                     $count_new_answers++;
                                }
                            }

                            /*Insert All New Checkboxes Answers*/
                            if($count_old_answers == 0 && $count_new_answers > 0)
                            {
                                foreach($new_answer as $new_answer_id => $ans)
                                {
                                    if(!empty($ans))
                                    {
                                        $project_instance_document_template_answer_data = array(
                                        "document_template_question_section_id" => $question_section_id,
                                        "project_instance_submission_id"        => $project_instance_submission_id,
                                        "answer"                                => $ans,
                                        "status"                                => 'Active');
                                        $insert =  ProjectInstanceDocumentTemplateAnswer::create($project_instance_document_template_answer_data);  
                                        $flag=$insert->project_instance_document_template_answer_id;
                                    }
                                } 
                            }
                            /*Delete All Old Checkboxes Answers*/
                            else if($count_old_answers > 0 && $count_new_answers == 0)
                            {                            
                                foreach($old_answers[$question_section_id] as $old_answer_id => $old_answer)
                                {
                                    $delete =  ProjectInstanceDocumentTemplateAnswer::where("document_template_question_section_id",$question_section_id)
                                        ->where("project_instance_submission_id",$project_instance_submission_id)
                                        ->delete();
                                }
                            }
                            /*Insert New Checkboxes Answers One By One*/
                            else if($count_old_answers < $count_new_answers)
                            {
                                foreach($new_answer as $new_answer_id => $ans)
                                {
                                    if(!empty($ans))
                                    {
                                        if(!in_array($ans, $old_answers[$question_section_id], true))
                                        {
                                            $project_instance_document_template_answer_data = array(
                                            "document_template_question_section_id" => $question_section_id,
                                            "project_instance_submission_id"        => $project_instance_submission_id,
                                            "answer"                                => $ans,
                                            "status"                                => 'Active');
                                            $insert =  ProjectInstanceDocumentTemplateAnswer::create($project_instance_document_template_answer_data);  
                                            $flag = $insert->project_instance_document_template_answer_id;
                                        }
                                    }
                                }
                            }
                            /*Delete Old Checkboxes Answers One By One*/
                            else if($count_old_answers > $count_new_answers)
                            {
                                foreach($old_answers[$question_section_id] as $old_answer_id => $old_answer)
                                {
                                    if(!in_array($old_answer, $new_answer, true))
                                    {
                                        $delete =  ProjectInstanceDocumentTemplateAnswer::where("document_template_question_section_id",$question_section_id)
                                            ->where("project_instance_submission_id",$project_instance_submission_id)
                                            ->where("answer",$old_answer)
                                            ->delete();
                                    }
                                }
                            }
                        }
                        /* Checkbox Answers End */
                        else
                        {
                            $project_instance_document_template_answer_data = array(
                            "document_template_question_section_id" => $question_section_id,
                            "project_instance_submission_id"        => $project_instance_submission_id,
                            "answer"                                => $new_answer,
                            "status"                                => 'Active');

                            $update =  ProjectInstanceDocumentTemplateAnswer::where('document_template_question_section_id',$question_section_id)
                                        ->where("project_instance_submission_id",$project_instance_submission_id)
                                        ->update($project_instance_document_template_answer_data);
                            
                            $flag=$update;
                        }
                   }

                }
                if($flag)
                {
                    return Redirect::back()->with('msg_success','Document Template Saved Successfully !...');
                }
                else
                {
                    return Redirect::back()->with('msg_fail','Some Error Occured, Please Try Again Later !...');  
                }
                
            }
             /*Check If Project Instance Submission Status Is UnAprroved Or Not Submitted*/
            else if($project_instance_submission_status==7 || empty($project_instance_submission_status))
            { 
                $project_instance_submission_data['status'] = 0;
                $controls['old_project_instance_submission_id']=$controls['project_instance_submission_id'];
                $get_project_instance_submission_id =  ProjectInstanceSubmission::create($project_instance_submission_data)->project_instance_submission_id;
                if($get_project_instance_submission_id)
                {
                    $controls['project_instance_submission_id']=$get_project_instance_submission_id;
                    
                    /*Custom Function -> Manage Project Submission File Attachments*/
                    $this->manageFileAttachments($controls,"insert_new_submission");
                    /*Custom Function -> Manage Project Submission File Attachments*/
                    
                    foreach($answers as $question_section_id => $answer)
                    {
                        if(!empty($answer))
                        {
                            if(is_array($answer))
                            {
                                foreach($answer as $key => $ans)
                                {
                                    if(!empty($ans))
                                    {
                                        $project_instance_document_template_answer_data = array(
                                        "document_template_question_section_id"=>$question_section_id,
                                        "project_instance_submission_id"=>$get_project_instance_submission_id,
                                        "answer" => $ans,
                                        "status" => 'Active' 
                                    );

                                    $update =  ProjectInstanceDocumentTemplateAnswer::create($project_instance_document_template_answer_data);
                                    }
                                }
                            }
                            else
                            {
                                    $project_instance_document_template_answer_data = array(
                                            "document_template_question_section_id"=>$question_section_id,
                                            "project_instance_submission_id"=>$get_project_instance_submission_id,
                                            "answer" => $answer,
                                            "status" => 'Active' 
                                        );

                                    $update =  ProjectInstanceDocumentTemplateAnswer::create($project_instance_document_template_answer_data);
                            }
    
                        }
                    }
                    return Redirect::back()->with('msg_success','Document Template Saved Successfully !...');
                }
                else
                {
                  return Redirect::back()->with('msg_fail','Some Error Occured, Please Try Again Later !...');  
                }
            }
        }
        
    }
    /*Submit Document Template Process (Add All Document Template Section Question Answers)*/

    
    /*Manage Project Instance Submission All File Attachment On Behalf Of Submission Status*/
    function manageFileAttachments($controls,$action)
    {
        /*If Saved Button Or Submit Button Is Clicked And Project Instance Submission Status Is Zero( Means Saved)*/
        if($action=="pending_saved_submission")
        {
                /*Project Instance Submission File Attachments*/
                for($i=0; (isset($controls['attachments']['file_type_id'][$i]) && isset($controls['attachments']['file_desctiption'][$i])); $i++)
                {
                    /*Check If New File Attachment Is Uploaded*/
                    if(isset($controls['attachments']['file_path'][$i]))
                    {
                        if(!File::exists("public/FileAttachments/".$controls['project_instance_submission_id']))
                        {
                            Storage::makeDirectory("public/FileAttachments/".$controls['project_instance_submission_id']);
                        }

                        $file_name = $controls['attachments']['file_desctiption'][$i].".".$controls['attachments']['file_path'][$i]->getClientOriginalExtension();

                        $file_attachment_data = array(
                            "project_instance_submission_id"    =>  $controls['project_instance_submission_id'],
                            "file_type_id"                      =>  $controls['attachments']['file_type_id'][$i],
                            "file_path"                         =>  $file_name,
                            "file_description"                  =>  $controls['attachments']['file_desctiption'][$i]);

                        if(isset($controls['attachments']['attachment_id'][$i]))
                        {
                            $update = ProjectInstanceSubmissionAttachment::where("project_instance_submission_attachment_id",$controls['attachments']['attachment_id'][$i])->update($file_attachment_data);

                            if($update)
                            {
                                $old_file_path = "storage/FileAttachments/".$controls['project_instance_submission_id']."/".$controls['attachments']['old_file_path'][$i];

                                if(File::exists($old_file_path))
                                { 
                                    unlink($old_file_path);
                                }

                                Storage::putFileAs("public/FileAttachments/".$controls['project_instance_submission_id'], $controls['attachments']['file_path'][$i], $file_name);
                            }
                        }
                        else if(!isset($controls['attachments']['attachment_id'][$i]))
                        {
                            $insert = ProjectInstanceSubmissionAttachment::create($file_attachment_data);
                            if($insert->project_instance_submission_attachment_id)
                            {
                                Storage::putFileAs("public/FileAttachments/".$controls['project_instance_submission_id'], $controls['attachments']['file_path'][$i], $file_name);
                            }
                        }
                    }
                    /*Check If Old File Exists And New File Attachment Is Not Uploaded*/
                    else if(!isset($controls['attachments']['file_path'][$i]) && isset($controls['attachments']['old_file_path'][$i]))
                    {
                        $extract = explode(".",$controls['attachments']['old_file_path'][$i]);
                        if($extract[0] != $controls['attachments']['file_desctiption'][$i])
                        {
                            $old_file_name = $controls['attachments']['old_file_path'][$i];
                            $new_file_name = $controls['attachments']['file_desctiption'][$i].".".$extract[1];

                            $file_attachment_data = array(
                            "file_type_id"                      =>  $controls['attachments']['file_type_id'][$i],
                            "file_path"                         =>  $new_file_name,
                            "file_description"                  =>  $controls['attachments']['file_desctiption'][$i]
                            );

                            $update = ProjectInstanceSubmissionAttachment::where("project_instance_submission_attachment_id",$controls['attachments']['attachment_id'][$i])->update( $file_attachment_data );

                            if($update)
                            {                        rename("storage/FileAttachments/".$controls['project_instance_submission_id']."/".$old_file_name,"storage/FileAttachments/".$controls['project_instance_submission_id']."/".$new_file_name);

                            }
                        }
                    }            
                }
            /*Project Instance Submission File Attachments*/    
            
        }
        /*If Save Button Is Clicked And Project Instance Submission Status Is Three(Means Rejected) Or It Is New Fresh Submission*/
        else if($action=="insert_new_submission")
        {
            
            /*Project Instance Submission File Attachments*/
            for($i=0; (isset($controls['attachments']['file_type_id'][$i]) && isset($controls['attachments']['file_desctiption'][$i])); $i++)
            {     
                /*Check If New File Attachment Is Uploaded*/
                if(isset($controls['attachments']['file_path'][$i]))
                {
                    if(!File::exists("public/FileAttachments/".$controls['project_instance_submission_id']))
                    {
                        Storage::makeDirectory("public/FileAttachments/".$controls['project_instance_submission_id']);
                    }
                    
                    $file_name = $controls['attachments']['file_desctiption'][$i].".".$controls['attachments']['file_path'][$i]->getClientOriginalExtension();
                    
                    $file_attachment_data = array(
                    "project_instance_submission_id"    =>  $controls['project_instance_submission_id'],
                    "file_type_id"                      =>  $controls['attachments']['file_type_id'][$i],
                    "file_path"                         =>  $file_name,
                    "file_description"                  =>  $controls['attachments']['file_desctiption'][$i]);

                    $insert = ProjectInstanceSubmissionAttachment::create($file_attachment_data);
                    if($insert->project_instance_submission_attachment_id)
                    {
                        Storage::putFileAs("public/FileAttachments/".$controls['project_instance_submission_id'], $controls['attachments']['file_path'][$i], $file_name);
                    }
                    
                }
                /*Check If Old File Exists And New File Attachment Is Not Uploaded*/
                else if(!isset($controls['attachments']['file_path'][$i]) && isset($controls['attachments']['old_file_path'][$i]))
                {
                        $extract = explode(".",$controls['attachments']['old_file_path'][$i]);
                        $old_file_name = $controls['attachments']['old_file_path'][$i];
                        $new_file_name = $controls['attachments']['file_desctiption'][$i].".".$extract[1];
                        $file_attachment_data = array(
                            "project_instance_submission_id"    =>  $controls['project_instance_submission_id'],
                            "file_type_id"                      =>  $controls['attachments']['file_type_id'][$i],
                            "file_path"                         =>  $new_file_name,
                            "file_description"                  =>  $controls['attachments']['file_desctiption'][$i]
                            );
                    
                        $insert = ProjectInstanceSubmissionAttachment::create($file_attachment_data);
                        if($insert->project_instance_submission_attachment_id)
                        {
                            if(!File::exists("public/FileAttachments/".$controls['project_instance_submission_id']))
                            {
                                Storage::makeDirectory("public/FileAttachments/".$controls['project_instance_submission_id']);
                            }
                            Storage::copy("public/FileAttachments/".$controls['old_project_instance_submission_id']."/".$old_file_name,"public/FileAttachments/".$controls['project_instance_submission_id']."/".$new_file_name); 
                        }
                    
                }
            }
            /*Project Instance Submission File Attachments*/
        }    
    }
    /*Manage Project Instance Submission All File Attachment On Behalf Of Submission Status*/
    
/*Manage Document Template Submissions*/    

}
