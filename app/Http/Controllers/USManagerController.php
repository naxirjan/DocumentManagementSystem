<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

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
use App\ProjectInstanceDocumentTemplate;

class USManagerController extends Controller
{
    public function __construct()
    {
    	
        $this->middleware( ['USManager' ,'deactive'] );
    }

    public function index(){
    	return view('usManager/index');
    }

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
                                                        'role_user_id'=>$role->role_user_id,
                                                        'role_status'=>$role->role_status
                                                        );

                    $userRoles[$role->role]['status'] = array('role_status'=>$role->role_status);
                }
                
            }
            
            $userData = $userData[0];
            return view('usManager/viewProfile' , ['userAssignedRoles'=>$userRoles,'userData'=>$userData,'user_id'=>$user_id]);            
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
    /*Code By Abdul Ghani End --Change Password--*/
/*Manage Profile*/



/*Manage Questions -> Start*/
    /*Add New Question*/
    public function addQuestion()
    {            
        $question_types = array(""=>"-- Select Question Type --");
        $data = QuestionType :: all()->toArray();
        foreach($data as $key => $type)
        {
            $question_types[$type['question_type_id']]=$type['question_type'];
        }    
        return view('usManager/addQuestion',array("question_types"=>$question_types));
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
        if($check['question_section_rows']>0)
        {
            abort(404);
        }
    
        $question_data['question_types'] = array(""=>"-- Select Question Type --");
        $data = QuestionType :: all()->toArray();
        foreach($data as $key => $type)
        {
            $question_data['question_types'][$type['question_type_id']]=$type['question_type'];
        }
        
        return view('usManager/editQuestion',$question_data);
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
            	return redirect::back()->withInput($request->all())->withErrors($validator);
            	//return redirect("/usManager/addQuestion")->withInput($request->all())->withErrors($validator);
            	//
        }
        else
        {    
            if($controls['action']=="add")
            {
                
                $controls['is_sum']     = ($request->has('is_sum') && $controls['is_sum'] == "on" ? 'Yes':'No');
                $controls['is_average'] = ($request->has('is_average') && $controls['is_average'] == "on" ? 'Yes':'No');
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
        return view('usManager/viewQuestions',array("questions"=>$questions));
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
        return view('usManager/viewQuestionDetail',$question_detail);
    }
    
    /*Load Question Meta Control Content For Add Question Meta Data*/
    public function loadQuestionMetaControlContent()
    {
        return view('usManager/includes/loadQuestionMetaControlContent');
    }
    
    /*Load Question Meta Data By Question ID*/
    public function loadQuestionMetaDataByQuestionID($question_id)
    {
        $question_mata['question_meta']=QuestionMeta::where('question_id','=',$question_id)->get()->toArray();
        return view('usManager/includes/loadQuestionMetaDataByQuestionID',$question_mata);   
    }
    
/*Manage Questions -> End */


/*Manage Sections*/

    /*Add New Section-Asad:start*/
    public function addSection(){
        $activeQuestions = Question::all()->where('status' , 'Active')->toArray();
        return view('usManager/addSection' ,['activeQuestions'=>$activeQuestions]);
    }
    /*Add New Section-Asad:end*/

    /*View Section-Asad:start*/
    public function viewSection(){
        $allSections = Section::getAllSections();              
        return view('usManager/viewSection' ,['allSections'=>$allSections]);
    }
    /*View Section-Asad:end*/

    /*View Section Detail-Asad:start*/
    public function viewSectionDetail($sectionID){
        $singleSection = Section::getSectionDetail($sectionID);
        return view('usManager/viewSectionDetail' ,['singleSection'=>$singleSection]);
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
    public function editSection($sectionID)
    {
        $sectionQuestions = Section::with(['questions'=>function($query){
            $query->select('pdms_questions.question_id','pdms_questions.question','pdms_question_section.question_section_id','pdms_question_section.priority' ,'pdms_question_section.status')->orderBy('pdms_question_section.priority' , 'ASC');
        }])->where('section_id',$sectionID)->get()->toArray();  
        
        $remainingActiveQuestions = Question::select('pdms_questions.question_id','pdms_questions.question','pdms_questions.status')->whereNotIn('pdms_questions.question_id', function($query) use($sectionID){
            $query->select('pdms_question_section.question_id')->from('pdms_question_section')->where('pdms_question_section.section_id',$sectionID);
        })->where('pdms_questions.status' ,'Active')->get()->toArray();
        
        return view('usManager/editSection' ,['remainingActiveQuestions'=>$remainingActiveQuestions,'sectionQuestions'=>$sectionQuestions]);
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
        $data['form_action'] = '/addUserByusManager';
        return view('usManager.addUser',$data);
    }

    public function getDistrictOperationByCountryId(Request $request){

        
        if($request->roleType == 3 || $request->roleType == 4 || $request->roleType == 5)
        {
            $data['districtOperations'] = DistrictOperation::all()->where('country_id','=',$request->country)->toArray();
            $data['action'] = 'getDistrictOperationByCountryId';
            $id = (explode("_",$request->parent));
            $id = $id[1];
            $data['id'] = $id;
            $data['roleType'] = $request->roleType;
            $data['country'] = $request->country;
            return view('usManager/includes/ajaxResponse',$data);       
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
            return view('usManager/includes/ajaxResponse',$data); 
        }
        else{
            return 0;
        }

    }

    public function addUserByusManager(Request $request){
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
            return redirect('/usManager/addUserForm')->withErrors($validator)->withInput();
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

                return redirect('/usManager/addUserForm')->with(Session::flash('success', 'User Added Successfully !..'));
        }
                return redirect('/usManager/addUserForm')->with(Session::flash('danger', 'User Not Added Successfully !..'));

    }

    public function viewUsersByUsManager(Request $request){

        $users['users'] = array();
        $users['users'] = User::all()->toArray();    

        return view('usManager.viewUsers',$users);
    }

    public function editUserByusManager($id){
       
       $data['user'] = User::with(['roles'=>function($query){
                      $query->select('pdms_roles.role_id','pdms_roles.role' ,'pdms_role_user.role_user_id','pdms_role_user.country_id','pdms_role_user.district_operation_id','pdms_role_user.status');
                    }])->find($id)->toArray();

       $data['districtOperations'] = DistrictOperation::all()->toArray();
       $data['countries'] = Country::all()->toArray();     
       $data['roles'] = Role::all()->toArray();
       $data['title'] = 'Edit User';
       $data['form_action'] = '/updateUserByusManager';
       return view('usManager.addUser')->with($data);
    }

    public function updateUserByusManager(Request $request){
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
            return redirect('/usManager/editUser/'.$controls['user_id'])->withErrors($validator)->withInput();
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
            return redirect('/usManager/editUser/'.$controls['user_id'])->withErrors($validator)->withInput();
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

                    echo "Old".$controls["country".$i];
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
                    echo "New".$controls["role_typenew".$i];
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
                    return redirect('/usManager/viewUsers')->with('success','User Updated Successfully !...');
                }
                else{
                    return redirect('/usManager/viewUsers')->with('danger','User Could Not Updated !...');
                }

            }
    }
    /*Code By Abdul Ghani End --Manage Users--*/


    public function userProjects(Request $request){
        $data['districtOperationProjects'] = array();
        $data['districtOperationProjects'] = RoleUser::getDistrictOperationProjects($request->roleUserId,$request->dopId);
        $data['userAssignedProjects'] = array();
        $data['userAssignedProjects'] = getUserProjectsByRoleUserID($request->roleUserId);
        $data['action'] = 'districtOperationProjects';
        return view('usManager/includes/ajaxResponse',$data);       
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
        return view('usManager/includes/ajaxResponse',$data);    
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


/*Manage Users*/

/*Manage Templates*/
    
    /*Add New Template-Asad:start*/
    public function addTemplate(){
        return view('usManager/addTemplate');
    }
    /*Add New Template-Asad:end*/

    /*Call From Ajax-Asad:start*/
    public function getSectionWithQuestionsBySectionId(Request $request){
        
        if(is_array($request->input('section_id'))){
            $sectionWithQuestions = array();
            foreach ($request->input('section_id') as $key => $sectionID) {
            $sectionWithQuestions[] = Section::getSectionActiveQuestions($sectionID);
            }    
        }else{
            $sectionWithQuestions[] = Section::getSectionActiveQuestions($request->input('section_id'));
        }
        
       return view('usManager/includes/ajaxDocumentTemplateStructure',['sectionWithQuestions'=>$sectionWithQuestions]);
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
        
        $allDocumentTemplates = DocumentTemplate::getAllDocumentTemplates();
        return view('usManager/viewDocumentTemplate' ,['allDocumentTemplates'=>$allDocumentTemplates]);
    }
    /*View Document Template-Asad:end*/

    /*View Document Template Detail-Asad:start*/
    public function viewTemplateDetail($templateID){

     $singleDocumentTemplates = DocumentTemplate::getDocumentTemplateDetail($templateID);
           
     $totalTemplateSectionQuestion = array();
     foreach ($singleDocumentTemplates['templateSectionQuestions'] as $key => $question) {
         $totalTemplateSectionQuestion[$question->section_id][] = $question->question_id;
     }
        
        
     
     return view('usManager/viewDocumentTemplateDetail' ,['singleDocumentTemplates'=>$singleDocumentTemplates ,'totalTemplateSectionQuestion'=>$totalTemplateSectionQuestion]);   
    }
    /*View Document Template Detail-Asad:end*/

    /*Call From Ajax-Asad:start*/
    public function getPredefinedTemplateByTemplateId(Request $request){
        $activeTemplates = TemplateType::all()->where('status','Active')->toArray();
        $allProjects     = Project::all()->toArray();
        $activeSections  = Section::all()->where('status','Active')->toArray();
        
        $singleDocumentTemplates = DocumentTemplate::getDocumentTemplateDetail($request->input('document_template_id'));
        foreach ($singleDocumentTemplates['templateSection'] as $key => $sectionID) {
            $overAllSectionQuestion[] = Section::getSectionActiveQuestions($sectionID->section_id);
        }

        return view('usManager/includes/ajaxPredefinedTemplate',['activeTemplates'=>$activeTemplates,'allProjects'=>$allProjects,'activeSections'=>$activeSections ,'overAllSectionQuestion'=>$overAllSectionQuestion,
            'singleDocumentTemplates'=>$singleDocumentTemplates]);
    }
    //end-->

    /*Call From Ajax-Asad:start*/
    public function getGeneralDiv(Request $request){
        
        $predefinedTemplates = DocumentTemplate::orderBy('document_template_id' ,'DESC')->where('status' ,'Active')->get()->toArray();
        $activeTemplates = TemplateType::all()->where('status','Active')->toArray();
        $allProjects     = Project::all()->toArray();
        $activeSections  = Section::all()->where('status','Active')->toArray();
        
        return view('usManager/includes/ajaxGeneralDiv',['predefinedTemplates'=>$predefinedTemplates,'activeTemplates'=>$activeTemplates,'allProjects'=>$allProjects,'activeSections'=>$activeSections ,'flag'=>$request->input('flag')]);
    }
    //end-->
    
/*Manage Templates*/


/*Manage Project Instances -> Start*/
    
        /*Add Project Instance*/
    public function addProjectInstance()
    {       
        $all_projects = Project::all();
        $countries = Country::all();
        $projects=array(""=>"-- Select Project --");
        
        foreach($all_projects as $project)
        {
            $projects[$project->proj_id] = $project->proj_name;
        }

        return view('usManager/addProjectInstance',array("projects"=>$projects,"countries"=>$countries));
    }
        
    /*Edit Project Instance */
    public function editProjectInstance($project_instance_id=0)
    {
        if(empty($project_instance_id) || !is_numeric($project_instance_id))
        {
            abort(404);
        }
        
        $project_instance_data = ProjectInstance::where('project_instance_id','=',$project_instance_id)
                                                    ->with(['project_instance_countries'])->get()->toArray(); 
        
        if(count($project_instance_data)==0)
        {
            abort(404);            
        }  
        
        $all_projects = Project::all();
        $countries = Country::all();
        $projects=array(""=>"-- Select Project --");
        
        foreach($all_projects as $project)
        {
            $projects[$project->proj_id] = $project->proj_name;
        }
        
        return view('usManager/editProjectInstance',array("projects"=>$projects,"countries"=>$countries,"project_instance_data"=>$project_instance_data));
        
    }
    
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
        }
        else
        {   
            if($controls['action']=="add")
            {
                $controls['status'] = ($request->has('status') && $controls['status'] == "on" ? 'Active':'InActive'); 
                
                $project_instance_data = array(
                    "project_id"                       => $controls['project_id'],
                    "added_by"                         => session()->get('current_role_user_id'),
                    "project_instance_title"           => $controls['project_instance_title'],
                    "project_instance_description"     => $controls['project_instance_description'],
                    "project_instance_start_date"      => date("Y-m-d",strtotime($controls['project_instance_start_date'])),
                    "project_instance_end_date"        => date("Y-m-d",strtotime($controls['project_instance_end_date'])),
                    "status"                           => $controls['status']);
                
                    $project_instance_id = ProjectInstance::create($project_instance_data)->project_instance_id;
                    if($project_instance_id)
                    {       
                        for($i=0; isset($controls['country_id'][$i]); $i++)
                        {
                            $controls['country_status'][$i] = (isset($controls['country_status'][$i]) && $controls['country_status'][$i] == "on" ? 'Active':'InActive');

                            $country_id = $controls['country_id'][$i]; 
                            
                            $assigned_project_instance_data = array(
                                "project_instance_id"               =>  $project_instance_id,
                                "country_id"                        =>  $country_id,
                                "status"                            =>  $controls['country_status'][$i]);

                            if(isset($controls['district_operation_id'][$country_id][0]))
                            {
                                $assigned_project_instance_data['district_operation_id'] = $controls['district_operation_id'][$country_id][0];
                            }


                            AssignedProjectInstance::create($assigned_project_instance_data);        
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
                    "added_by"                         => session()->get('current_role_user_id'),
                    "project_instance_title"           => $controls['project_instance_title'],
                    "project_instance_description"     => $controls['project_instance_description'],
                    "project_instance_start_date"      => date("Y-m-d",strtotime($controls['project_instance_start_date'])),
                    "project_instance_end_date"        => date("Y-m-d",strtotime($controls['project_instance_end_date'])),
                    "status"                           => $controls['status']);
                
                $update = ProjectInstance::where('project_instance_id','=',$controls['project_instance_id'])->update($project_instance_data);
                
                if($update)
                {
                    for($i=0; isset($controls['country_id'][$i]); $i++)
                    {
                        $project_instance_assigned_id = $controls['project_instance_assigned_id'][$i];
                        $country_id                   = $controls['country_id'][$i];
                        $district_operation_id        = null;
                        
                        if(isset($controls['district_operation_id'][$country_id]))
                        {
                            $district_operation_id = $controls['district_operation_id'][$country_id][0]; 
                        }
                        
                        $conditions = array(
                        'project_instance_assigned_id' => $project_instance_assigned_id,    
                        'project_instance_id'          => $controls['project_instance_id']);

                        $assigned_project_instance_data =array(
                        'project_instance_id'          => $controls['project_instance_id'],
                        'country_id'                   => $country_id,
                        'district_operation_id'        => $district_operation_id,
                        'status'                       => $controls['project_instance_country_status'][$i]);
                        
                        AssignedProjectInstance::updateOrCreate($conditions,$assigned_project_instance_data);
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
       
    /*View All Project Instances*/
    public function viewProjectInstances()
    {
        $project_instances = ProjectInstance::where('added_by',session('current_role_user_id'))->with(['project_instance_countries'])->get()->toArray();
        return view('usManager/viewProjectInstances',array('project_instances'=>$project_instances));
    }
    
    /*View All Project Instances Details*/
    public function viewProjectInstanceDetail($project_instance_id=0)
    {
        if(empty($project_instance_id) || !is_numeric($project_instance_id))
        {
            abort(404);
        }
        
        $project_instance_detail = ProjectInstance::where('project_instance_id','=',$project_instance_id)
                                                    ->with(['project_instance_countries'])->get()->toArray();
        
        if(count($project_instance_detail)==0)
        {
            abort(404);            
        }
        
        return view('usManager/viewProjectInstanceDetail',array('project_instance_detail'=>$project_instance_detail));
    }
    
    /*Load Project Instance Assigned To Countries Content*/
    public function loadProjectInstanceAssignedToCountriesContent()
    {
        $countries = Country::all();
        return view('usManager/includes/loadProjectInstanceAssignedToCountriesContent',array('countries'=>$countries));
    }
    
    /*Load Project Instance Assigned-To Countries By Project Instance Assigned ID*/
    public function loadProjectInstanceCountriesByProjectInstanceID($project_instance_id)
    {
        $countries = Country::all();
        
        $project_instance_countries = AssignedProjectInstance::where('project_instance_id','=',$project_instance_id)->get()->toArray();

        return view('usManager/includes/loadProjectInstanceCountriesByProjectInstanceID',array('countries'=>$countries,"project_instance_countries"=>$project_instance_countries));

    }
    /*Load DIstrict Operations By Country ID*/
    public function loadCountryDistrictOperationsContent($country_id)
    {
        $district_operations = getAllDistrictOperationsByCountryId($country_id);
        
        $data['district_operations']=$district_operations;
        $data['country_id']=$country_id;    
        
        return view('usManager/includes/loadCountryDistrictOperationsContent',$data);
    }
 
/*Manage Project Instances*/    

/*Manage Project Instances Document Templates*/
    
    /*View All Document Templates Of Project Instance By Project Instance ID*/
    public function viewProjectInstanceDocumentTemplates($project_instance_id)
    {
        
        if(empty($project_instance_id) || !is_numeric($project_instance_id))
        {
            abort(404);
        }
        
        $project_instance_detail = ProjectInstance::where('project_instance_id','=',$project_instance_id)
                                                    ->with(['project_instance_countries'])->get()->toArray();
                
        if(count($project_instance_detail)==0)
        {
            abort(404);            
        }

        $common_document_templates = DocumentTemplate::where('project_id','=',$project_instance_detail[0]['project_id'])->where('status','=','Active')->get();
        
        
       $project_instance_document_templates= ProjectInstanceDocumentTemplate::getAllProjectInstanceDocumentTemplatesByProjectInstanceID($project_instance_id);
        
       
        return view('usManager/viewProjectInstanceDocumentTemplates',array("project_instance_detail"=>$project_instance_detail,"common_document_templates"=>$common_document_templates,"project_instance_document_templates"=>$project_instance_document_templates));
        
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
        
        return view('usManager/includes/loadProjectInstanceDocumentTemplateContent',array("document_templates"=>$document_templates,"project_instance_assigned_id"=>$project_instance_assigned_id,"document_template_id"=>$document_template_id));
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

    
}
