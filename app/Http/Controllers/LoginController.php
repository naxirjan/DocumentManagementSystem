<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use URL;
use Validator;
use App\Role;
use App\User;
class LoginController extends Controller
{
    public function __construct(){

    	if(Auth::check()){
            return redirect(URL::previous());
        }	
    }

    public function index(){
        
        return view('login');
    }

    /*Login Process Code Asad:start*/ 
    public function loginProcess(Request $request){
        
        $controls = $request->all();

        $rules = array('email'=>'required|email','password'=>'required','role_type'=>'required');
        
        $validator = Validator::make($controls,$rules);

        if($validator->fails()){
            return redirect('/')->withErrors($validator)->withInput();
        }else{

            $array = array('email'=>$request->input('email'),'password'=>$request->input('password'));
            $user  = Auth::attempt($array);

            if(Auth::check() && $user)
            {
                $user_roles = userActiveRoles(Auth::user()->user_id);
                
                foreach($user_roles as $key => $role)
                {
                    if($role['role_id'] == $request->input('role_type'))
                    {
                        $request->session()->put('current_active_role_id', $role['role_id']);
                        $request->session()->put('current_active_role_type', $role['role']);
                        $request->session()->put('current_cont_id', $role['cont_id']);
                        $request->session()->put('current_cont_name', $role['cont_name']);
                        $request->session()->put('current_dop_id', $role['dop_id']);
                        $request->session()->put('current_dop_name', $role['dop_name']);
                        $request->session()->put('current_role_user_id', $role['role_user_id']);
                        $request->session()->put('current_role_status', $role['role_status']);
                    }

                }
                
                //if($request->session()->has('current_active_role_id')){
                    $current_role_id = $request->session()->get('current_active_role_id');
                      
                    if($current_role_id == 1){
                        return redirect('/usManager');  
                    }elseif($current_role_id == 2){
                        return redirect('/countryManager');
                    }elseif($current_role_id == 3){
                        return redirect('/partnerManager');
                    }elseif($current_role_id == 4){
                        return redirect('/operationManager');
                    }elseif($current_role_id == 5){
                        return redirect('/projectManager');
                    }else{
                        Auth::logout();
                        return redirect('/')->with("Msg" , "The Particular Role Is Not Assigned To You ...!");
                    }

                //}else{
                    //Auth::logout();
                    //return redirect('/')->with("Msg" , "Session Not Created Successfuly ...!");
                //}
                
            }
            else{
                return redirect('/')->with("Msg" ,"Email or Password Is Not Correct ...!");
            }
        }
    }
    /*Login Process Code Asad:end*/

    
    /*Logout Code Asad:start*/
    public function logout(){
    	Auth::logout();
    	session()->flush();
    	return redirect('/')->with("Msg" ,"Logout Successfuly ...!");;
    }
    /*Logout Code Asad:end*/

    /*Switch Role Code Asad:start*/
    public function switchRole($role_user_id){
            
            $current_user_roles = userActiveRoles(Auth::user()->user_id);
            //dd($current_user_roles);
            if(array_key_exists($role_user_id,$current_user_roles)){
                
                session()->put('current_active_role_id', $current_user_roles[$role_user_id]['role_id']);
                session()->put('current_active_role_type', $current_user_roles[$role_user_id]['role']);
                session()->put('current_cont_id', $current_user_roles[$role_user_id]['cont_id']);
                session()->put('current_cont_name', $current_user_roles[$role_user_id]['cont_name']);
                session()->put('current_dop_id', $current_user_roles[$role_user_id]['dop_id']);
                session()->put('current_dop_name', $current_user_roles[$role_user_id]['dop_name']);
                session()->put('current_role_user_id', $role_user_id);
                session()->put('current_role_status', $current_user_roles[$role_user_id]['role_status']);

                if(session()->has('current_active_role_id')){
                   
                   $role_id = session()->get('current_active_role_id');
                   if($role_id == 1){
                        return redirect('/usManager');  
                    }elseif($role_id == 2){
                            return redirect('/countryManager');
                    }elseif($role_id == 3){
                            return redirect('/partnerManager');
                    }elseif($role_id == 4){
                            return redirect('/operationManager');
                    }elseif($role_id == 5){
                            return redirect('/projectManager');
                    } 

               }
                 
            }else{
                return redirect(URL::previous());
            }
    }
    /*Switch Role Code Asad:end*/
}
