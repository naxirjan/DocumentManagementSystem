<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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


class CountryManagerController extends Controller
{
    public function __construct(){
    	$this->middleware( ['CountryManager','deactive'] );
    }

    /*Dashboard-Asad:start*/
    public function index(){
        $projectInstances = $this->getAssignedProjectInstancesForCountryManager();
        return view('countryManager/index',['project_instances'=>$projectInstances]);
    }
    //End-->
   
/*Manage Profile*/
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
                                                        'cont_id'=>$role->cont_id,
                                                        'role_user_id'=>$role->role_user_id,
                                                        'role_status'=>$role->role_status
                                                        );

                    $userRoles[$role->role]['status'] = array('role_status'=>$role->role_status);
                }
                
            }
            $userData = $userData[0];
            return view('countryManager/viewProfile' , ['userAssignedRoles'=>$userRoles,'userData'=>$userData,'user_id'=>$user_id]);            
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

/*Manage Profile*/

/*Manage Questions*/

    /*Add New Question*/
    public function addQuestion()
    {                
        $question_types = array(""=>"-- Select Question Type --");
        $data = QuestionType :: all()->toArray();
        foreach($data as $key => $type)
        {
            $question_types[$type['question_type_id']]=$type['question_type'];
        }    
        return view('countryManager/addQuestion',array("question_types"=>$question_types));
    }
    
    /*Edit Question*/
    public function editQuestion($question_id=0)
    {   
        if(empty($question_id) || !is_numeric($question_id))
        {
            abort(404);
        }
        
        $question_data=Question::getQuestionDetail($question_id);
        if(!count($question_data['question'])>0)
        {
            abort(404);            
        }
        
        $check=checkIfQuestionAlreadyAssigned($question_id);
        if($check['question_section_rows']>0 || $check['document_template_questions_rows']>0)
        {
            abort(404);
        }

        
        $question_data['question_types'] = array(""=>"-- Select Question Type --");
        $data = QuestionType :: all()->toArray();
        foreach($data as $key => $type)
        {
            $question_data['question_types'][$type['question_type_id']]=$type['question_type'];
        }

        return view('countryManager/editQuestion',$question_data);
    }
    
    /*Add Or Update Question Process According To Action Type*/
    public function addOrUpdateQuestionProcess(Request $request)
    {
        
        $controls  = $request->all();
        
         $rules = array(
            "question"           => "required",
            "question_type_id"   => "required",
        );

        $messages = [
            'question.required'         => 'Question Description Is Required',
            'question_type_id.required' => 'Question Type Is Required',
        ];

        $validator  = Validator::make($controls, $rules,$messages);
        if($validator->fails())
        {
            return redirect("/countryManager/addQuestion")->withInput($request->all())->withErrors($validator);
        }
        else
        {    
            if($controls['action']=="add")
            {
                
                $controls['is_sum']     = ($request->has('is_sum') && $controls['is_sum'] == "on" ? 'Yes':'No');
                $controls['is_average'] = ($request->has('is_average') && $controls['is_average'] == "on" ? 'No':'No');
                $controls['status']     = ($request->has('status') && $controls['status'] == "on" ? 'Active':'InActive');
                $role_user_id           = session('current_role_user_id');

               
                $question_data = array(
                    "question"          => $controls['question'],
                    "question_type_id"  => $controls['question_type_id'],
                    "added_by"          => $role_user_id,
                    "is_sum"            => $controls['is_sum'],
                    "is_average"        => $controls['is_average'],
                    "status"            => $controls['status'],
                );
                
                $question_id = Question::create($question_data)->question_id;
                            
                if($question_id)
                {
                    if(isset($controls['question_meta_value']))
                    {
                        for($i=0; $i<=isset($controls['question_meta_value'][$i]); $i++)
                        {
                            $question_meta_data = array(
                            "question_id"   => $question_id,
                            "key"           => $controls['question_meta_key'][$i],
                            "value"         => $controls['question_meta_value'][$i],
                            "status"        => 'Active',    
                            );
                            
                            QuestionMeta::create($question_meta_data);
                        }
                    } 
                    return Redirect::back()->with('msg_success','Question Added Successfully !...');
                }
                else
                {
                    return Redirect::back()->with('msg_fail','Some Error Occured, Please Try Again Later !...');
                } 
            }
            else if($controls['action']=="update")
            {
                $controls['is_sum']     = ($request->has('is_sum') && $controls['is_sum'] == 'on' ? 'Yes':'No');
                $controls['is_average'] = ($request->has('is_average') && $controls['is_average'] == 'on' ? 'Yes':'No');
                $controls['status']     = ($request->has('status') && $controls['status'] == 'on' ? 'Active':'InActive');
                
                /*If Question Type Is Textfiled And Old Type == New Type*/
                if($controls['old_question_type_id'] == $controls['question_type_id'] && $controls['question_type_id']==1)
                {
                    $data = array(
                        "question"      => $controls['question'],
                        "is_sum"        => $controls['is_sum'],
                        "is_average"    => $controls['is_average'],
                        "status"        => $controls['status'],
                    );
                    
                    $update = Question::where('question_id',$controls['question_id'])->update($data);
                    
                    if($update)
                    {
                        return Redirect::back()->with('msg_success','Question Updated Successfully !...');
                    }
                    else
                    {
                        return Redirect::back()->with('msg_fail','Some Error Occured, Please Try Again Later !...');
                    }
                }
                /*If Question Type Is Not Textfiled And Old Type == New Type*/
                else if($controls['old_question_type_id'] == $controls['question_type_id'] && $controls['question_type_id']!=1)
                {
                    $question_data = array(
                    "question"      => $controls['question'],
                    "status"        => $controls['status'],    
                    );
                        //dd('If Question Type Is Not Textfiled And Old & New Is Equal');
                    $update_question = Question::where('question_id',$controls['question_id'])->update($question_data);
                    
                    /*If Question Data Is Updated*/
                    if($update_question)
                    {
                        /*If Old Question Meta Data Exists*/
                        if( isset($controls['question_meta_old_value']) && count($controls['question_meta_old_value']) > 0)
                        {
                            for($i=0; $i<=isset($controls['question_meta_old_value'][$i]); $i++)
                            {
                                if(isset($controls['question_meta_old_key'][$i]) && isset($controls['question_meta_old_value'][$i]))
                                {
                                    $question_meta_data = array(
                                    "key"       => $controls['question_meta_old_key'][$i],
                                    "value"     => $controls['question_meta_old_value'][$i],
                                    );

                                    QuestionMeta::where('question_meta_id',$controls['question_meta_old_id'][$i])->update($question_meta_data);
                                }
                            }
                        }

                        /*If New Question Meta Data Exists*/
                        if(count($controls['question_meta_new_value']) > 0)
                        {
                            for($i=0; $i<=isset($controls['question_meta_new_value'][$i]); $i++)
                            {
                                if(isset($controls['question_meta_new_key'][$i]) && isset($controls['question_meta_new_value'][$i]))
                                {
                                    $question_meta_data = array(
                                    "key"           => $controls['question_meta_new_key'][$i],
                                    "value"         => $controls['question_meta_new_value'][$i],
                                    "question_id"   => $controls['question_id'],
                                    "status"        => "Active"
                                    );

                                    QuestionMeta::create($question_meta_data);
                                }
                                
                            }
                        }
                        return Redirect::back()->with('msg_success','Question Updated Successfully !...');
                    }
                    else
                    {
                        return Redirect::back()->with('msg_fail','Some Error Occured, Please Try Again Later !...');
                    }
                }
                /*If Question Type Is Textfield And It Is Changesd To Other Type*/
                else if($controls['old_question_type_id'] != $controls['question_type_id'] && $controls['old_question_type_id']==1)
                {
                    $data = array(
                        "question_type_id"  =>$controls['question_type_id'],
                        "question"          => $controls['question'],
                        "is_sum"            => 'No',
                        "is_average"        => 'No',
                        "status"            => $controls['status'],
                    );
                    
                       /*If New Question Meta Data Exists && At Least One Question Item(Meta Data) Is Added*/
                        if(count($controls['question_meta_new_value']) > 0 && isset($controls['question_meta_new_value'][0]))
                        {
                            /*Update Question Data*/
                            $update = Question::where('question_id',$controls['question_id'])->update($data);
                                
                            /*If Question Is Updated*/
                            if($update)
                            {
                                for($i=0; $i<=isset($controls['question_meta_new_value'][$i]); $i++)
                                {
                                    /*If Old Question Meta Key & Old Question Meta Value Are Added/Set*/
                                    if(isset($controls['question_meta_new_key'][$i]) && isset($controls['question_meta_new_value'][$i]))
                                    {
                                        $question_meta_data = array(
                                        "key"       => $controls['question_meta_new_key'][$i],
                                        "value"     => $controls['question_meta_new_value'][$i],
                                        "question_id" =>$controls['question_id'],
                                        "status" =>'Active'
                                        );

                                        QuestionMeta::create($question_meta_data);
                                    }
                                }
                                
                                return Redirect::back()->with('msg_success','Question Updated Successfully !...');
                            }
                            /*If Question Is Not Updated*/
                            else
                            {
                                return Redirect::back()->with('msg_fail','Please Add At Least One Question Item !...');
                            }
                        }
                        /*If First Question Item(Meta Data) Is Not Added*/
                        else
                        {
                            return Redirect::back()->with('msg_fail','Please Add At Least One Question Item (Value/Label) !...');
                        }
                   
                }
                /*If Question Type Is Not Textfield And It Is Changesd To Textfield*/
                else if($controls['old_question_type_id'] != $controls['question_type_id'] && $controls['old_question_type_id']!=1)
                {
                    //dd('If Question Type Is Not Textfield And It Is Changesd To Textfield');
                    $data = array(
                        "question_type_id"  =>$controls['question_type_id'],
                        "question"          => $controls['question'],
                        "is_sum"            => $controls['is_sum'],
                        "is_average"        => $controls['is_average'],
                        "status"            => $controls['status'],
                    );
                    
                     /*Update Question Data*/
                    $update = Question::where('question_id',$controls['question_id'])->update($data);

                    /*If Question Is Updated*/
                    if($update)
                    {
                        /*Delete Question Meta Data*/
                        QuestionMeta::where('question_id',$controls['question_id'])->delete();

                        return Redirect::back()->with('msg_success','Question Deleted Successfully !...');
                    }
                    /*If Question Is Not Updated*/
                    else
                    {
                        return Redirect::back()->with('msg_fail','Please Add At Least One Question Item !...');
                    }

                }
                
            }        
        }
    }
 
    /*Delete Question Meta*/
    public function deleteQuestionMeta(Request $request)
    {
        $controls = $request->all();
        $delete=QuestionMeta::where("question_meta_id","=",$controls['question_meta_id'])->delete();
        if($delete)
        {
            echo "success";
        }
        else
        {
            echo "fail";
        }
    }
    /*View All Questions*/
    public function viewQuestions()
    {
        $questions=Question::getAllQuestions();
        return view('countryManager/viewQuestions',array("questions"=>$questions));
    }
       
    /*View Question Detail*/
    public function viewQuestionDetail($question_id=0)
    {
        if(empty($question_id) || !is_numeric($question_id))
        {
            abort(404);
        }
        
        $question_detail=Question::getQuestionDetail($question_id);
        if(!count($question_detail['question'])>0)
        {
            abort(404);            
        }
        return view('countryManager/viewQuestionDetail',$question_detail);
    }
    
    /*Load Question Meta Control Content For Add Question Meta Data*/
    public function loadQuestionMetaControlContent()
    {
        return view('countryManager/includes/loadQuestionMetaControlContent');
    }
    
    /*Load Question Meta Data By Question ID*/
    public function loadQuestionMetaDataByQuestionID($id)
    {
        $question_mata['question_meta']=QuestionMeta::where('question_id','=',$id)->get()->toArray();
        return view('countryManager/includes/loadQuestionMetaDataByQuestionID',$question_mata);   
    }
    
/*Manage Questions*/

/*Manage Sections*/

    /*Add Section-Asad:start*/
    public function addSection(){
        $roleUserId = session()->get('current_role_user_id');
        $activeQuestions = Question::all()->where('status' , 'Active')->where('added_by',$roleUserId)->toArray();
        return view('countryManager/addSection' ,['activeQuestions'=>$activeQuestions]);
    }
    /*Add Section-Asad:end*/


    /*View Section-Asad:start*/
    public function viewSection(){
        $roleUserId = session()->get('current_role_user_id');
        $allSections = Section::getAllSections($roleUserId);              
        return view('countryManager/viewSection' ,['allSections'=>$allSections]);
    }
    /*View Section-Asad:end*/


    /*View Section Detail-Asad:start*/
    public function viewSectionDetail($sectionID){

        $validUser = Section::where('section_id', $sectionID)->where('added_by' , session()
                    ->get('current_role_user_id'))->count();
        
        if($validUser){
            $singleSection = Section::getSectionDetail($sectionID);
            return view('countryManager/viewSectionDetail' ,['singleSection'=>$singleSection]);
        }
        return Redirect::back();
    }
    /*View Section Detail-Asad:end*/


    /*Save Section-Asad:start*/
    public function saveSection(Request $request){
        
        $section = new Section;
        $section->added_by  = session()->get('current_role_user_id');
        $section->section_title = $request->input('section_title');
        $section->section_description = $request->input('section_description');
        $section->status = $request->input('section_status');
        $section->save();
        
        /*For Insert Bridg Table:pdms_question_section*/
        $section_question_ids = $request->input('section_question_ids'); 
        $question_priority    = $request->input('question_priority');
        $question_status      = $request->input('section_question_status');
        foreach($section_question_ids as $key => $question_id){
          $section->questions()->attach($question_id, array('priority'=> $question_priority[$key] ,'status'=>$question_status[$key]));    
        }
        return response()->json(['message'=>'Yes']);
    }
    /*Save Section-Asad:end*/

    /*Edit Section-Asad:start*/
    public function editSection($sectionID){
        
        $roleUserId = session()->get('current_role_user_id');
        $validUser = Section::where('section_id', $sectionID)->where('added_by',$roleUserId)->count();  
        
        if($validUser){
            $sectionQuestions = Section::with(['questions'=>function($query){
            $query->select('pdms_questions.question_id','pdms_questions.question','pdms_question_section.question_section_id','pdms_question_section.priority' ,'pdms_question_section.status')->orderBy('pdms_question_section.priority' , 'ASC');
            }])->where('section_id',$sectionID)->get()->toArray();  
        
            $remainingActiveQuestions = Question::select('pdms_questions.question_id','pdms_questions.question','pdms_questions.status')->whereNotIn('pdms_questions.question_id', function($query) use($sectionID){
            $query->select('pdms_question_section.question_id')->from('pdms_question_section')->where('pdms_question_section.section_id',$sectionID);
            })->where('pdms_questions.status' ,'Active')->where('pdms_questions.added_by',$roleUserId)->get()->toArray();
        
            return view('countryManager/editSection' ,['remainingActiveQuestions'=>$remainingActiveQuestions,'sectionQuestions'=>$sectionQuestions]);    
        }
        return Redirect::back();
    }
    /*Edit Section-Asad:end*/


    /*Update Section-Asad:start*/
    public function updateSection(Request $request){
        
        $section = Section::findorFail($request->input('section_id'));
        $section->section_title = $request->input('section_title');
        $section->section_description = $request->input('section_description');
        $section->status = $request->input('section_status');
        $section->save();

        /*For Update Bridg Table:pdms_question_section*/
        $section_question_ids = $request->input('section_question_ids'); 
        $question_priority    = $request->input('question_priority');
        $question_status      = $request->input('section_question_status');
          
        foreach($section_question_ids as $key => $question_id){
            $flag = DB::table('pdms_question_section')
                ->updateOrInsert(
                    ['section_id' => $request->input('section_id'), 'question_id' => $question_id],
                    ['priority' => $question_priority[$key],'status'=>$question_status[$key]]
                );
        }
        
        return response()->json(['message'=>'Yes']);    
    }
    /*Update Section-Asad:end*/


/*Manage Sections*/

/*Manage Users*/    
    /*Code By Abdul Ghani Start --Manage Users--*/
    public function addUserForm(){

        $data['countries'] = Country::all()->toArray();
        $data['roles'] = Role::all()->toArray();
        $data['title'] = 'Add User';
        $data['form_action'] = '/addUserBycountryManager';
        return view('countryManager.addUser',$data);
    }

    public function getDistrictOperationByCountryId(Request $request){
        
        if($request->roleType == 4 || $request->roleType == 5)
        {
            $data['districtOperations'] = DistrictOperation::all()->where('country_id','=',$request->country)->toArray();
            $data['action'] = 'getDistrictOperationByCountryId';
            $id = (explode("_",$request->parent));
            $id = $id[1];
            $data['id'] = $id;
            return view('countryManager/includes/ajaxResponse',$data);       
        }
        else{
            return 0;
        }

        
    }

    public function getCountryByRoleType(Request $request){
        if($request->roleType != 1){
            $data['countries'] = Country::all()->toArray();
            $data['action'] = 'getCountryByRoleType';
            $id = (explode("_",$request->parent));
            $id = $id[1];
            $data['id'] = $id;
            return view('countryManager/includes/ajaxResponse',$data); 
        }
        else{
            return 0;
        }

    }

    public function addUserBycountryManager(Request $request){
        $controls = $request->all();

            $rules = array(
            'first_name' => 'required|alpha|min:3',
            'last_name'  => 'alpha|min:3',
            'email'      => 'required|email|unique:pdms_users',
            'password'   => 'required|alpha_dash|min:7',
            'number'     => 'required|numeric',
            'image'      => 'required|mimes:jpg,jpeg,png',
            'role_type1' => 'required',


        );

        $validator = Validator::make($controls,$rules);
        if($validator->fails()){
            return redirect('/countryManager/addUserForm')->withErrors($validator)->withInput();
        }
        else{
            $uploadedFile = $request->file('image'); 
            $uploadedFile->move('storage/UserImage/', $controls['image']->getClientOriginalName());
            $user_data = array(
                'first_name' => $controls['first_name'],
                'last_name'  => $controls['last_name'],
                'email'      => $controls['email'],
                'password'   => Hash::make($controls['password']),
                'phone'      => $controls['number'],
                'image'      => $controls['image']->getClientOriginalName(),
                'status'     => (isset($controls['user_status']) && $controls['user_status'] == 1)?'Active':'InActive',
            );
            
            $user = User::create($user_data);

                for($i = 1;isset($controls["role_type".$i]);$i++){
                        
                         $role_user_data = array(
                            'user_id'    => $user->user_id,
                            'role_id'    => $controls["role_type".$i],
                            'country_id' => (isset($controls["country".$i]))?$controls["country".$i]:null,
                            'district_operation_id' => (isset($controls["district_operation".$i]))?$controls["district_operation".$i]:null,
                            'status'     => (isset($controls["status".$i]) && $controls["status".$i] == 'on')?'Active':'InActive',            
                        );
                        RoleUser::create($role_user_data);

                }

                return redirect('/countryManager/addUserForm')->with(Session::flash('success', 'User Added Successfully !..'));
        }
                return redirect('/countryManager/addUserForm')->with(Session::flash('danger', 'User Not Added Successfully !..'));

    }

    public function viewUsersByCountryManager(Request $request){
            
      $users['users'] = array();
      $users['users'] = DB::table('pdms_users as u')->join('pdms_role_user as ru','u.user_id','=' ,'ru.user_id')->select(DB::raw('DISTINCT(u.user_id),u.user_id,u.first_name,u.last_name,u.email,u.image,u.status'))
        ->where('ru.country_id',session('current_cont_id'))->whereIn('ru.role_id',[4,5])->get()->toArray();       
        return view('countryManager.viewUsers',$users);
    }

    public function editUserByCountryManager($id){
       
       $data['user'] = User::with(['roles'=>function($query){
                      $query->select('pdms_roles.role_id','pdms_roles.role' ,'pdms_role_user.role_user_id','pdms_role_user.country_id','pdms_role_user.district_operation_id','pdms_role_user.status');
                    }])->find($id)->toArray();

       $data['districtOperations'] = DistrictOperation::all()->toArray();
       $data['countries'] = Country::all()->toArray();     
       $data['roles'] = Role::all()->toArray();
       $data['title'] = 'Edit User';
       $data['form_action'] = '/updateUserByCountryManager';
       return view('countryManager.addUser')->with($data);
    }

    public function updateUserByCountryManager(Request $request){
        if(!empty($request->file('image'))){
            $controls = $request->all();

            $rules = array(
            'first_name' => 'required|alpha|min:3',
            'last_name'  => 'alpha|min:3',
            'email'      => 'required|email|',
            'password'   => 'required|alpha_dash|min:7',
            'number'     => 'required|numeric',
            'image'      => 'required|mimes:jpg,jpeg,png',
            'role_type1' => 'required',
            'country1'   =>  (isset($request->country1))?'required':'',
        );

        $validator = Validator::make($controls,$rules);
        if($validator->fails()){
            return redirect('/countryManager/editUser/'.$controls['user_id'])->withErrors($validator)->withInput();
        }
        else{
            $uploadedFile = $request->file('image'); 
            $uploadedFile->move('storage/UserImage/', $controls['image']->getClientOriginalName());
            $user_data = array(
                'first_name' => $controls['first_name'],
                'last_name'  => $controls['last_name'],
                'email'      => $controls['email'],
                'password'   => Hash::make($controls['password']),
                'phone'      => $controls['number'],
                'image'      => $controls['image']->getClientOriginalName(),
                'status'     => (isset($controls['user_status']) && $controls['user_status'] == 1)?'Active':'InActive',
            );
            
 
        }
        }
        else{
            $controls = $request->all();
              
            $rules = array(
            'first_name' => 'required|alpha|min:3',
            'last_name'  => 'alpha|min:3',
            'email'      => 'required|email|',
            'password'   => 'required|alpha_dash|min:7',
            'number'     => 'required|numeric',
            'role_type1' => 'required',
            'country1'   =>  (isset($request->country1))?'required':'',
        );

        $validator = Validator::make($controls,$rules);
        if($validator->fails()){
            
            return redirect('/countryManager/editUser/'.$controls['user_id'])->withErrors($validator)->withInput();
        }
        else{
            $user_data = array(
                'first_name' => $controls['first_name'],
                'last_name'  => $controls['last_name'],
                'email'      => $controls['email'],
                'password'   => Hash::make($controls['password']),
                'phone'      => $controls['number'],
                'status'     => (isset($controls['user_status']) && $controls['user_status'] == 1)?'Active':'InActive',
            );
            
        }
        }
            $user = User::where('user_id',$request->user_id)->update($user_data);
            if(isset($user)){
                for($i = 1;isset($controls["role_type".$i]);$i++){

                        $role_user_data = array(
                            'user_id'    => $controls['user_id'],
                            'role_id'    => $controls["role_type".$i],
                            'country_id' => (isset($controls["country".$i]))?$controls["country".$i]:null,
                            'district_operation_id' => (isset($controls["district_operation".$i]))?$controls["district_operation".$i]:null,
                            'status'     => (isset($controls["status".$i]) && $controls["status".$i] == 'on')?'Active':'InActive',            
                        );
                        $result = RoleUser::where('role_user_id',$controls['role_user_id'.$i])->update($role_user_data);               
                }
                for($i = 1;isset($controls["role_typenew".$i]);$i++){
                      $role_user_data = array(
                            'user_id'    => $controls['user_id'],
                            'role_id'    => $controls["role_typenew".$i],
                            'country_id' => (isset($controls["country".$i]))?$controls["countrynew".$i]:null,
                            'district_operation_id' => (isset($controls["district_operationnew".$i]))?$controls["district_operationnew".$i]:null,
                            'status'     => (isset($controls["statusnew".$i]) && $controls["statusnew".$i] == 'on')?'Active':'InActive',            
                        );
                      $result = RoleUser::create($role_user_data);
                }

                if(!empty($result)){
                    return redirect('/countryManager/viewUsers')->with('success','User Updated Successfully !...');
                }
                else{
                    return redirect('/countryManager/viewUsers')->with('danger','User Could Not Updated !...');
                }

            }
    }
    
    public function userProjects(Request $request){
        
        $data['districtOperationProjects'] = array();
        $data['userAssignedProjects']      = array();
        $data['districtOperationProjects'] = RoleUser::getDistrictOperationProjects($request->roleUserId,$request->dopId);
        
        $data['userAssignedProjects'] = getUserProjectsByRoleUserID($request->roleUserId);

        $data['action'] = 'districtOperationProjects';
        return view('countryManager/includes/ajaxResponse',$data);       
    }    

    public function assignProjects(Request $request){
        
        foreach ($request->districtOperationProjectId as $key => $dopId) {
            $result = ProjectUser::updateOrCreate([
                'district_operation_project_id' => $dopId,
                'role_user_id'                  => $request->roleUserId,
                    
            ],[
                'district_operation_project_id' => $dopId,
                'role_user_id'                  => $request->roleUserId,
                'status'                        => 'Active',

            ]);
        }
        if(isset($result)){
            $data['message'] = 'Project Assigned';
        }
        else{
            $data['message'] = 'Project Not Assigned';
        }
        
        $data['userAssignedProjects'] = ProjectUser::getUserProjects($request->roleUserId);
        $data['action'] = 'getuserProjects';
        return view('countryManager/includes/ajaxResponse',$data);    
    }

    public function deleteUserProject(Request $request){
        
        $result = ProjectUser::where('project_user_id','=',$request->projectUserId)->delete();
        if($result)
        {
            echo "deleted";
        }   
        else
        {
            echo "not_deleted";
        }
    }
    
    /*Code By Abdul Ghani End --Manage Users--*/
/*Manage Users*/
    
/*Manage Templates*/
    
    /*Add New Template-Asad:start*/
    public function addTemplate(){
        return view('countryManager/addTemplate');
    }
    /*Add New Template-Asad:end*/

    /*Call From Ajax-Asad:start*/
    public function getSectionWithQuestionsBySectionId(Request $request){
        
        foreach ($request->input('section_id') as $key => $sectionID) {
            $sectionWithQuestions[] = Section::getSectionActiveQuestions($sectionID);
        }
        
        return view('countryManager/includes/ajaxDocumentTemplateStructure',['sectionWithQuestions'=>$sectionWithQuestions]);
    }
    //end-->

    /*Save Document Template-Asad:start*/
    public function saveDocumentTemplate(Request $request){
        
        $document_template_status = ($request->has('document_template_status') && $request->input('document_template_status') == "Active" ?'Active':'InActive');

        $inputArray = array(
                        'template_type_id'          => $request->input('template_type_id'),
                        'project_id'                => $request->input('project_id'),  
                        'added_by'                  => session()->get('current_role_user_id'),     
                        'document_template_title'   => $request->input('document_template_title'),
                        'status'                    => $document_template_status
                    );


        $documentTemplateId = DocumentTemplate::create($inputArray)->document_template_id;
        
        if($documentTemplateId){
            
            $assignedSectionId = $request->input('assigned_sections_id');
            $assignedSectionPriority = $request->input('assigned_section_priority');
            $assignedQuestionSectionId = $request->input('assigned_question_section_id');
            
            foreach ($assignedSectionId as $key => $sectionID) {
               $flagCheck_1 = DB::table('pdms_document_template_sections')->insert(
                    ['document_template_id' => $documentTemplateId, 'section_id' =>$sectionID ,'priority'=> $assignedSectionPriority[$key]]
                );       
            }

            foreach ($assignedQuestionSectionId as $key => $questionSectionID) {
               $flagCheck_2 = DB::table('pdms_document_template_questions')->insert(
                    ['question_section_id' => $questionSectionID, 'document_template_id' => $documentTemplateId]
                );   
            }

            if($flagCheck_1 && $flagCheck_2){
                return Redirect::back()->with('Yes','Document Template Created Successfully ...!');
            }else{
                return Redirect::back()->with('No','Some Error Occured, Please Try Again Later !...');
            }
        }else{
            return Redirect::back()->with('No','Some Error Occured, Please Try Again Later !...');
        }
    }
    /*Save Document Template-Asad:end*/
    

    /*View Document Template-Asad:start*/
    public function viewTemplate(){
        
        $roleUserId = session()->get('current_role_user_id');
        $allDocumentTemplates = DocumentTemplate::getAllDocumentTemplates($roleUserId);
        return view('countryManager/viewDocumentTemplate' ,['allDocumentTemplates'=>$allDocumentTemplates]);
    }
    /*View Document Template-Asad:end*/

    /*View Document Template Detail-Asad:start*/
    public function viewTemplateDetail($templateID){

        $validUser = DocumentTemplate::where('document_template_id', $templateID)->where('added_by' , session()->get('current_role_user_id'))->count();
        
        //dd($validUser);
        
        if($validUser){
            $singleDocumentTemplates = DocumentTemplate::getDocumentTemplateDetail($templateID);
            
            $totalTemplateSectionQuestion = array();
            foreach ($singleDocumentTemplates['templateSectionQuestions'] as $key => $question) {
                 $totalTemplateSectionQuestion[$question->section_id][] = $question->question_id;
             }
            
            
            
            return view('countryManager/viewDocumentTemplateDetail' ,['singleDocumentTemplates'=>$singleDocumentTemplates ,'totalTemplateSectionQuestion'=>$totalTemplateSectionQuestion]);   
         }else{
            return Redirect::back();
         }
        
    }
    /*View Document Template Detail-Asad:end*/

    /*Call From Ajax-Asad:start*/
    public function getPredefinedTemplateByTemplateId(Request $request){
        
        $roleUserId = session()->get('current_role_user_id');
        $activeTemplates = TemplateType::all()->where('status','Active')->toArray();
        $allProjects     = Project::all()->toArray();
        $activeSections  = Section::all()->where('status','Active')->where('added_by',$roleUserId)->toArray();
        
        $singleDocumentTemplates = DocumentTemplate::getDocumentTemplateDetail($request->input('document_template_id'));
        $overAllSectionQuestion = array();
        foreach ($singleDocumentTemplates['templateSection'] as $key => $sectionID) {
            $overAllSectionQuestion[] = Section::getSectionActiveQuestions($sectionID->section_id);
        }

        return view('countryManager/includes/ajaxPredefinedTemplate',['activeTemplates'=>$activeTemplates,'allProjects'=>$allProjects,'activeSections'=>$activeSections ,'overAllSectionQuestion'=>$overAllSectionQuestion,
            'singleDocumentTemplates'=>$singleDocumentTemplates]);
    }
    //end-->

    /*Call From Ajax-Asad:start*/
    public function getGeneralDiv(Request $request){
        
        $roleUserId = session()->get('current_role_user_id');
        $predefinedTemplates = DocumentTemplate::orderBy('document_template_id' ,'DESC')->where('status' ,'Active')->where('added_by',$roleUserId)->get()->toArray();
        $activeTemplates = TemplateType::all()->where('status','Active')->toArray();
        $allProjects     = Project::all()->toArray();
        $activeSections  = Section::all()->where('status','Active')->where('added_by',$roleUserId)->toArray();
        
        return view('countryManager/includes/ajaxGeneralDiv',['predefinedTemplates'=>$predefinedTemplates,'activeTemplates'=>$activeTemplates,'allProjects'=>$allProjects,'activeSections'=>$activeSections ,'flag'=>$request->input('flag')]);
        
    }
    //end-->

/*Manage Templates*/

/*Manage Project Instances*/    

    /*Add Project Instance-Asad:start*/
    public function addProjectInstance(){

        $roleUserId  = session()->get('current_role_user_id');
        $currentUser = getUserAndRoleByRoleUserId($roleUserId);
        $countryId   = $currentUser[0]->country_id;
        $districtOperations = DistrictOperation::where('country_id' ,$countryId)->get()->toArray();
        return view('countryManager/addProjectInstance' ,['dop'=>$districtOperations]);
    }
    //End-->

    /*Call From Ajax*/
    public function loadProjectInstanceAssignedToCountriesContent(){
        $countries = Country::all();
        $roleUserId  = session()->get('current_role_user_id');
        $currentUser = getUserAndRoleByRoleUserId($roleUserId);
        $countryId   = $currentUser[0]->country_id;
        $districtOperations = DistrictOperation::where('country_id' ,$countryId)->get()->toArray();
        return view('countryManager/includes/loadProjectInstanceAssignedToCountriesContent' ,['countries'=>$countries ,'dop'=>$districtOperations]);
    }
    //End-->

    /*Call From Ajax-Asad:start*/
    public function getGeneralDivForProjectInstance(Request $request){
        
        $projects    = Project::select('proj_name' ,'proj_id')->get()->toArray();
        $countries   = Country::all()->toArray();
        $roleUserId  = session()->get('current_role_user_id');
        $currentUser = getUserAndRoleByRoleUserId($roleUserId);
        $countryId   = $currentUser[0]->country_id;
        
        $districtOperations = DistrictOperation::where('country_id' ,$countryId)->get()->toArray();
        $projectInstances   = ProjectInstance::getAllAssignedProjectInstancesForCountryManager($countryId);
        
        $predefinedProjectInstances = array();
        foreach ($projectInstances as $key => $instance) {
            $predefinedProjectInstances['project_intance'][$instance->project_instance_id] = array('project_instance_title' =>$instance->project_instance_title,'project_instance_assigned_id'=>$instance->project_instance_assigned_id);
        }
         
        return view('countryManager/includes/ajaxGeneralDivForProjectInstance',['projects'=> $projects, 'predefinedProjectInstances'=>$predefinedProjectInstances,'countries'=>$countries ,'dop'=>$districtOperations ,'flag'=>$request->input('flag')]);
    }
    //End-->
    
    
    /*Call From Ajax-Asad:start*/
    public function getPredefinedProjectInstance(Request $request){
        $assignedProjectInstanceId = $request->input('project_instance_id');
        
        $assignedProjectInstance = AssignedProjectInstance::find($assignedProjectInstanceId)->toArray();
        
        $projectInstanceId       = $assignedProjectInstance['project_instance_id'];
        $projectInstance         = ProjectInstance::find($projectInstanceId)->toArray();
        $countryDistrict         = ProjectInstance::getProjectInstanceCountryDistrictById($projectInstanceId);
        
        $projects                = Project::select('proj_name' ,'proj_id')->get()->toArray();
        $countries               = Country::all()->toArray();
        $roleUserId              = session()->get('current_role_user_id');
        $currentUser             = getUserAndRoleByRoleUserId($roleUserId);
        $countryId               = $currentUser[0]->country_id;
        $districtOperations      = DistrictOperation::where('country_id' ,$countryId)->get()->toArray();
        
        return view('countryManager/includes/ajaxPredefinedProjectInstanceStructure',['projects'=> $projects,'countries'=>$countries ,'dop'=>$districtOperations ,'oldProjectInstance'=>$projectInstance ,'countryDistrict'=>$countryDistrict]);

    }
    //End-->

    /*Add Or Update Project Instance Process*/
    public function addOrUpdateProjectInstanceProcess(Request $request)
    {
        $controls = $request->all();

        $rules = [
            "project_id"                              => "required",
            "project_instance_description"            => "required",
            "project_instance_start_date"             => "required",
            "project_instance_end_date"               => "required",    
        ];

        $messages = [
            "project_id.required"                     => "Project Is Required",
            "project_instance_description.required"   => "Project Instance Description Is Required",
            "project_instance_start_date.required"    => "Project Instance Start Date Is Required",
            "project_instance_end_date.required"      => "Project Instance End Date Is Required",
        ];

        $validator  = Validator::make($controls, $rules,$messages);
        if($validator->fails())
        {
            return redirect::back()->withInput($request->all())->withErrors($validator);
                
        }else
        {
            $roleUserId  = session()->get('current_role_user_id');
            $currentUser = getUserAndRoleByRoleUserId($roleUserId);
            $countryId   = $currentUser[0]->country_id;
           
            if($controls['action']=="add")
            {
                    $controls['status'] = ($request->has('status') && $controls['status'] == "on" ? 'Active':'InActive'); 
                    
                    $project_instance_data = array(
                        "project_id"                       => $controls['project_id'],
                        "added_by"                         => $roleUserId,
                        "project_instance_title"           => $controls['project_instance_title'],
                        "project_instance_description"     => $controls['project_instance_description'],
                        "project_instance_start_date"      => date("Y-m-d",strtotime($controls['project_instance_start_date'])),
                        "project_instance_end_date"        => date("Y-m-d",strtotime($controls['project_instance_end_date'])),
                        "status"                           => $controls['status']                   
                   );
                    
                    $project_instance_id = ProjectInstance::create($project_instance_data)->project_instance_id;
                    if($project_instance_id)
                    {
                        if(count($controls['district_operation_id'])>0)
                        {
                            for($i=0; $i<=isset($controls['district_operation_id'][$i]); $i++)
                            {
                                if(is_numeric($controls['district_operation_id'][$i]))
                                {
                                    $controls['district_status'][$i] = (isset($controls['district_status'][$i]) && $controls['district_status'][$i] == "on" ? 'Active':'InActive');
                                    $assigned_project_instance_data = array(
                                    "project_instance_id"   =>  $project_instance_id,
                                    "country_id"            =>  $countryId,
                                    "district_operation_id" =>  $controls['district_operation_id'][$i], 
                                    "status"                =>  $controls['district_status'][$i]    
                                    );
                                    
                                    if($request->has('predefined_project_intance')){
                                        $assigned_project_instance_data['parent_id'] = $controls['predefined_project_intance'];    
                                    }
                                     
                                    AssignedProjectInstance::create($assigned_project_instance_data); 
                                }
                            }
                        }
                        
                        return Redirect::back()->with('msg_success','Project Instance Added Successfully !...');
                    }
                    else
                    {
                        return Redirect::back()->with('msg_fail','Some Error Occured, Please Try Again Later !...');
                    }
            }
            else if($controls['action']=="edit")
            {
                    $controls['status'] = ($request->has('status') && $controls['status'] == "on" ? 'Active':'InActive'); 
                    
                    $project_instance_data = array(
                        "project_id"                       => $controls['project_id'],
                        "project_instance_title"           => $controls['project_instance_title'],
                        "project_instance_description"     => $controls['project_instance_description'],
                        "project_instance_start_date"      => date("Y-m-d",strtotime($controls['project_instance_start_date'])),
                        "project_instance_end_date"        => date("Y-m-d",strtotime($controls['project_instance_end_date'])),
                        "status"                           => $controls['status']                   
                   );
                    
                    $update = ProjectInstance::where('project_instance_id','=',$controls['project_instance_id'])->update($project_instance_data);
                    
                    if($update)
                    {
                        for($i=0; $i<=isset($controls['district_operation_id'][$i]); $i++)
                        {
                           if(is_numeric($controls['district_operation_id'][$i]))
                            {
                                 
                                AssignedProjectInstance::updateOrCreate(
                                /*Where Conditions To Update*/
                                [   
                                    'project_instance_id'   => $controls['project_instance_id'],
                                    'district_operation_id' => $controls['district_operation_id'][$i],
                                ],
                                /*Data To Insert/Update*/
                                [
                                    'project_instance_id'           => $controls['project_instance_id'],
                                    'country_id'                    => $countryId,
                                    'district_operation_id'         => $controls['district_operation_id'][$i],
                                    'status'                        => $controls['project_instance_district_status'][$i]
                                ]);
                               
                            }
                        }
                         return Redirect::back()->with('msg_success','Project Instance Updated Successfully !...');
                    }
                    else
                    {
                        return Redirect::back()->with('msg_fail','Some Error Occured, Please Try Again Later !...');
                    }
            }
        
        }
    }
    //End-->

    /*Edit Project Instance */
    public function editProjectInstance($project_instance_id=0)
    {
        $roleUserId  = session()->get('current_role_user_id');
        
        if(empty($project_instance_id) || !is_numeric($project_instance_id))
        {
            abort(404);
        }
        
        $validUser   = ProjectInstance::where('project_instance_id', $project_instance_id)
                       ->where('added_by',$roleUserId)->count();
        
        if($validUser){
            
            $projectInstanceId = $project_instance_id;
            $projectInstance   = ProjectInstance::find($projectInstanceId)->toArray();
            $countryDistrict   = ProjectInstance::getProjectInstanceCountryDistrictById($projectInstanceId);
            
            $projects    = Project::select('proj_name' ,'proj_id')->get()->toArray();
            $countries   = Country::all()->toArray();
            $currentUser = getUserAndRoleByRoleUserId($roleUserId);
            $countryId   = $currentUser[0]->country_id;
            $districtOperations = DistrictOperation::where('country_id' ,$countryId)->get()->toArray();
            
            return view('countryManager/editProjectInstance',array('projects'=>$projects,'countries'=>$countries,'dop'=>$districtOperations ,'oldProjectInstance'=>$projectInstance ,'countryDistrict'=>$countryDistrict));    
        }
        return Redirect::back();
    }
    //End-->

    /*View Project Instance-Asad:start*/
    public function viewProjectInstances(){
        
        $roleUserId  = session()->get('current_role_user_id');
        $currentUser = getUserAndRoleByRoleUserId($roleUserId);
        $countryId   = $currentUser[0]->country_id;
        $projectInstances = ProjectInstance::getAllProjectInstancesCreatedByCountryManager($countryId);
        
        /*Make Unique Array For Project Instance*/
        $data['project_instance'] = array();
        foreach ($projectInstances as $key => $instance) {
            $data['project_instance'][ $instance->project_instance_id ] = array('project_instance_title' =>$instance->project_instance_title,'start_date'=>$instance->project_instance_start_date ,'end_date'=>$instance->project_instance_end_date ,'status' =>$instance->status ,'created_at'=>$instance->created_at ,'added_by'=>$instance->added_by ,'project_instance_id'=>$instance->project_instance_id);
            
            /*Make Assigned Country And District Array Of A Project Instance*/
            $data['country'][ $instance->project_instance_id ][] = array('country'=>$instance->cont_name ,'dop_full'=>$instance->district_operation_full_name ,'dop_short'=>$instance->district_operation_short_name);
        }
        
        return view('countryManager/viewProjectInstances' ,['project_instances'=>$data]);
    }
    //End-->

    /*View Project Instance Detail-Asad:start*/
    public function viewProjectInstanceDetail($projectInstanceId){
        
        if(!(isset($projectInstanceId) && is_numeric($projectInstanceId))){
            
           abort('404');
        }

        $flagExist = ProjectInstance::find($projectInstanceId);    
        if(!($flagExist))
        {
            return Redirect::back();
        }
        $projectInstances = ProjectInstance::getSingleProjectInstanceCreatedByCountryManager($projectInstanceId);
        
        /*Make Unique Array For Project Instance*/
        $data['project_instance'] = array();
        foreach ($projectInstances as $key => $instance) {
            $data['project_instance'][ $instance->project_instance_id ] = array('project_instance_title' =>$instance->project_instance_title,'project_instance_description'=>$instance->project_instance_description,'start_date'=>$instance->project_instance_start_date ,'end_date'=>$instance->project_instance_end_date ,'status' =>$instance->status ,'created_at'=>$instance->created_at ,'added_by'=>$instance->added_by ,'project_instance_id'=>$instance->project_instance_id ,'project_id'=>$instance->project_id);
            
            /*Make Assigned Country And District Array Of A Project Instance*/
            $data['country'][ $instance->project_instance_id ][] = array('country'=>$instance->cont_name ,'dop_full'=>$instance->district_operation_full_name ,'dop_short'=>$instance->district_operation_short_name ,'country_status'=>$instance->assigned_country_status);
        }
        
        return view('countryManager/viewProjectInstanceDetail' ,['project_instance_detail'=>$data ]);
    }
    //End-->

    /*View Assigned Project Instance-Asad:start*/
    public function viewAssignedProjectInstances()
    {
        
        $projectInstances = $this->getAssignedProjectInstancesForCountryManager();
        return view('countryManager/viewAssignedProjectInstances',['project_instances'=>$projectInstances]);
    }
    //End-->

    /*General Function To Get Assigned Instances For Country-Asad:start*/
    public function getAssignedProjectInstancesForCountryManager($projectInstanceId=null){
        $roleUserId   = session()->get('current_role_user_id');
        $currentUser  = getUserAndRoleByRoleUserId($roleUserId);
        $countryId    = $currentUser[0]->country_id;
        $withTemplate = true;
        
        if($projectInstanceId){
            $projectInstances = ProjectInstance::getAllAssignedProjectInstancesForCountryManager($countryId,$withTemplate,$projectInstanceId);
        }else{
            $projectInstances = ProjectInstance::getAllAssignedProjectInstancesForCountryManager($countryId,$withTemplate);    
        }
        
        /*Make Unique Array For Project Instance*/
        $data['project_instance'] = array();
        foreach ($projectInstances as $key => $instance) {
            $data['project_instance'][ $instance->project_instance_id ] = array('project_instance_title' =>$instance->project_instance_title,'start_date'=>$instance->project_instance_start_date ,'end_date'=>$instance->project_instance_end_date ,'status' =>$instance->status ,'created_at'=>$instance->created_at ,'added_by'=>$instance->added_by ,'project_instance_id'=>$instance->project_instance_id ,'project_instance_assigned_id'=>$instance->project_instance_assigned_id,'project_id'=>$instance->project_id);
            
            /*Make Assigned Document Template Array Of A Project Instance*/
            $data['assigned_document_template_count'][$instance->project_instance_id][] = array('document_template_id'=>$instance->document_template_id ,'document_template_title'=>$instance->document_template_title ,'start_date'=>$instance->start_date ,'end_date'=>$instance->end_date,'project_instance_document_template_id'=>$instance->project_instance_document_template_id,'a_d_t_status'=>$instance->a_d_t_status); 
            
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
        
        $projectInstances = $this->getAssignedProjectInstancesForCountryManager($projectInstanceId);
        
        return view('countryManager/viewAssignedProjectInstanceDetail',['project_instance'=>$projectInstances,'projectInstanceId'=>$projectInstanceId]);
    }
    //End-->

//--------------------------------------------------------------------------------//
    /*Manage Project Instances Document Templates*/
    
    /*View All Document Templates Of Project Instance By Project Instance ID*/
    public function viewProjectInstanceDocumentTemplates($project_instance_id)
    {
        
        if(empty($project_instance_id) || !is_numeric($project_instance_id))
        {
            abort(404);
        }
        
        $project_instance_detail = ProjectInstance::where('project_instance_id','=',$project_instance_id)->with(['project_instance_countries'])->get()->toArray();
                
        if(count($project_instance_detail)==0)
        {
            abort(404);            
        }

        $common_document_templates = DocumentTemplate::where('project_id','=',$project_instance_detail[0]['project_id'])->where('status','=','Active')->get();
        

       $project_instance_document_templates= ProjectInstanceDocumentTemplate::getAllProjectInstanceDocumentTemplatesByProjectInstanceID($project_instance_id);       
        return view('countryManager/viewProjectInstanceDocumentTemplates',array("project_instance_detail"=>$project_instance_detail,"common_document_templates"=>$common_document_templates,"project_instance_document_templates"=>$project_instance_document_templates));
        
    }
    
    /*Get/Load Project Instance Document Template Controls Content via jQuery*/
    public function loadProjectInstanceDocumentTemplateContent($document_template_id_and_project_instance_assigned_id)
    {
        /* On Zero(0) Index It Is: document_template_id*/
        /* On One(1) Index It Is: project_instance_assigned_id*/
        $extract = explode("_",$document_template_id_and_project_instance_assigned_id);
        
        $document_template_id = $extract[0];
        $project_instance_assigned_id =$extract[1];
    
        $document_templates = DocumentTemplate::where('document_template_id', $document_template_id)->get()->toArray();
        return view('countryManager/includes/loadProjectInstanceDocumentTemplateContent',array("document_templates"=>$document_templates,"project_instance_assigned_id"=>$project_instance_assigned_id,"document_template_id"=>$document_template_id));
    }
    
    /*Assign Document Templates To Project Instance*/
    public function assignDocumentTemplatesToProjectInstance(Request $request)
    {
        $controls = $request->all();
        $lastInsertID=false;
       
        if(isset($controls['templates']))
        {
            foreach($controls['templates'] as $project_instance_assigned_id => $document_template)
            {
                if(is_array($document_template))
                {
                    foreach($document_template as $key => $value)
                    {                    
                        $value['project_instance_submission_start_date'] = date("Y-m-d",strtotime($value['project_instance_submission_start_date']));
                        $value['project_instance_submission_stop_date'] = date("Y-m-d",strtotime($value['project_instance_submission_stop_date']));

                        $lastInsertID = ProjectInstanceDocumentTemplate::create($value)->project_instance_document_template_id;
                    }
                }
            } 
            
            if($lastInsertID)
            {
                return Redirect::back()->with('msg_success','Document Template(s) Assigned Successfully !...');        
            }
            else
            {
                 return Redirect::back()->with('msg_fail','Some Error Occured, Please Try Again Later !...');   
            }
        }
    }
    /*Manage Project Instances Document Templates*/

/*Manage Project Instances*/      
    
    
    

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
        return view('countryManager/submitDocumentTemplate',$data);
    }
    /*Submit Document Template (Get Project Instance Detail & Project Instance All Document Templates)*/
    
    
    /* Get Document Template Section Questions)*/
    public function loadProjectInstanceDocumentTemplateSectionQuestions($project_instance_document_template_id)
    {
        
        $check_project_instance_submission = ProjectInstanceSubmission::where('project_instance_document_template_id',$project_instance_document_template_id)
            ->where("submitted_by",session('current_role_user_id'))
            ->orderBy('project_instance_submission_id','DESC')
            ->limit(1)
            ->get()->toArray();
        
        if(count($check_project_instance_submission)>0)
        {
             extract($check_project_instance_submission[0]);
        }
        else
        {
            $status=null;
            $project_instance_submission_id=0;
        }
        
        $data = array(
                "project_instance_submission_status" => $status,
                "project_instance_submission_id"     => $project_instance_submission_id);
        
            /*Check If Project Instance Submission Status Is (Saved Or UnApproved By US Manager)*/        
            if($status == 0 || $status == 3)
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
        
            return view('countryManager/includes/loadProjectInstanceDocumentTemplateForSubmissionContent',$data);
    }
    /* Get Document Template Section Questions)*/
    
    /*Get Media File Attachment Control Content*/
    public function loadFileAttachmentControlContent()
    {
        return view('countryManager/includes/loadFileAttachmentControlContent');
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
            return view('countryManager/includes/loadFileAttachmentControlContent',$data);            
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
            else if($project_instance_submission_status==3 || empty($project_instance_submission_status))
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
            else if($project_instance_submission_status==3 || empty($project_instance_submission_status))
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
