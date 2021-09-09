<?php
/***********************************************/
# Company Name    :                             
# Author          : SM                            
# Created Date    :                            
# Controller Name : TeacherController               
# Purpose         : Teacher Management             
/***********************************************/

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Country;
use DB;
use Mail;
use GlobalVars;
use App\Traits\GeneralMethods;
use Exception, Image;
use \Validator;

class TeacherController extends Controller
{
    use GeneralMethods;
    public $controllerName  = 'Teacher';
    public $management;
    public $modelName       = 'User';
    public $breadcrumb;
    public $routePrefix     = 'teachers';
    public $listUrl         = 'teachers.list';
    public $viewFolderPath  = 'admin/teachers';

    /*
        * Function Name :  __construct
        * Purpose       :  It sets some public variables for being accessed throughout this
        *                   controller and its related view pages
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  N/A
        * Return Value  :  Mixed
   */
    public function __construct()
    {
        parent::__construct();
        $this->management      = 'Teacher';
        // Begin: Assign breadcrumb for view pages
        $this->assignBreadcrumb();
        // End: Assign breadcrumb for view pages
        // Variables assign for view page
        $this->assignShareVariables();
    }

    /**
        * Function Name :  index
        * Purpose       :  This function is for the Tips Tricks Article Category listing and searching
        * Author        :
        * Created Date  : 
        * Modified date :        
        * Input Params  :  Request $request
        * Return Value  :  return to listing page
    */

    public function index(Request $request)
    {
        try{
            $data['keyword']        = '';
            $data['user_type']      = $request->user_type;
            $data['records'] = User::where('id','!=',1)->where('user_type','=',GlobalVars::TEACHER_USER_TYPE);

            if($request->keyword !=''){
                // search section
                $data['keyword'] = $request->keyword;
                $keywordArray = explode(' ', $data['keyword']);
                
                if(count($keywordArray) == 2){ // If keyword have two words
                     $data['records'] = $data['records']->where(function($q) use ($data, $keywordArray){
                        $q->where('first_name','like','%'.$keywordArray[0].'%')
                     ->where('last_name','like','%'.$keywordArray[1].'%');
                     });                     
                }else{

                     $data['records'] = $data['records']->where(function($q) use ($data){
                        $q->Where('first_name','like','%'.$data['keyword'].'%')
                         ->orWhere('last_name','like','%'.$data['keyword'].'%')
                         ->orWhere('email','like','%'.$data['keyword'].'%')
                         ->orWhere('phone','like','%'.$data['keyword'].'%');
                     });
                }
            }

            if($data['user_type']){
                $data['records'] = $data['records']->where('user_type', $data['user_type']);
            }            

            $data['records'] = $data['records']->orderBy('id','desc')->paginate(GlobalVars::ADMIN_RECORDS_PER_PAGE);

            return view($this->viewFolderPath.'.list', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }

    /**
        * Function Name :  add
        * Purpose       :  This function renders the User add form
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  N/A
        * Return Value  :  return to add page

    */

    public function add()
    {
        return view($this->viewFolderPath.'.add');
    }

    /**
        * Function Name :  store
        * Purpose       :  This function use for User addition.
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  Request $request
        * Return Value  :  loads listing page on success and load add page for any error during the operation

    */

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'first_name'            => 'required',
                'last_name'             => 'required',
                'phone'                 => 'required',
                'user_type'             => 'required',
                'email'                 => 'required|email|unique:users',
                'password'              => 'required|min:5',
               
            ],
            [
                'first_name.required'     => 'First Name is Required',
                'last_name.required'      => 'Last Name is Required',
                'email.required'          => 'Email is Required',
                'phone.required'          => 'Phone is Required',
                'password.required'       => 'Password is Required',
                'user_type.required'      => 'User Type is Required',
                
            ]
        );
        try{
            $model                      = new User;
            $model->first_name          = $request->first_name;
            $model->last_name           = $request->last_name;
            $model->email               = $request->email;
            $model->phone               = $request->phone;
            $model->password            = $request->password;
            $model->user_type           = $request->user_type;
            
            $model->status              = $request->status;
            $model->save();

            // getting default meta data
            $settings  = \Helpers::getSiteSettingsData();
            //Mail sending start
            $data                           = [];
            $fromEmail                      = $settings['from_email']; // From email id
            $from                           = $settings['from_email_name'];  // From name
            $toEmail                        = $request->email; //To email id
            $toName                         = $request->first_name.' '.$request->last_name;  // To email name    
            $data['userName']               = $request->first_name.' '.$request->last_name;  
            $data['emailHeaderSubject']     = 'Registration Successful'; 
            $data['loginEmail']             = $request->email;
            $data['password']               = $request->password; 
            // email message
            $data['content_message']    = "Thanks for showing interest to our care giving program."; 
            Mail::send('emails.user-registration-admin', $data, function($sent) use($data,$toEmail,$toName,$fromEmail,$from)
            {
                $sent->from($fromEmail, $from);                                
                $sent->to($toEmail, $toName)->subject(\GlobalVars::SITE_ADDRESS_NAME.': '.$data['emailHeaderSubject']);
            });
            // Mail sending end

            return \Redirect::Route($this->listUrl)->with('success', 'Record added Successfully');
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }       
    }

    /**
        * Function Name :  edit
        * Purpose       :  This function renders the User edit form
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  intiger $id
        * Return Value  :  loads user edit page
    */

    public function edit($id)
    {
        try{
            $data['id'] = $id;
            $data['record'] = User::find($id);
            if(!$data['record']){
                throw new Exception("No result was found for id: $id");
            }
            return view($this->viewFolderPath.'.edit', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }      
    }

    /**
        * Function Name :  update
        * Purpose       :  This function use for update records of User.
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  Request $request, intiger $id
        * Return Value  :  loads listing page on success and load edit page for any error during the operation
    */

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'first_name'    => 'required',
                'last_name'     => 'required',
                'email'         => 'required|email|unique:users,email,'.$id,
                'phone'         => 'required',
                'user_type'     => 'required',
                
            ],
            [
                'first_name.required'     => 'First Name is Required',
                'last_name.required'      => 'Last Name is Required',
                'email.required'          => 'Email is Required',
                'phone.required'          => 'Phone is Required',
                'user_type.required'      => 'User Type is Required',
            ]
        );
        try{
            $model = User::find($id);
            $model->first_name          = $request->first_name;
            $model->last_name           = $request->last_name;
            $model->email               = $request->email;
            $model->phone               = $request->phone;
            $model->user_type           = $request->user_type;
            $model->status              = $request->status;
            $model->save();
            return \Redirect::Route($this->listUrl)->with('success', 'Record updated Successfully');
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }        
    }


    /**
        * Function Name :  changePassword
        * Purpose       :  This function renders the User change password form
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  intiger $id
        * Return Value  :  loads user change password page
    */

    public function changePassword($id)
    {
        try{
            $data['id'] = $id;
            $data['record'] = User::find($id);
            if(!$data['record']){
                throw new Exception("No result was found for id: $id");
            }            
            return view($this->viewFolderPath.'.change-password', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }       
    }
     
    /**
        * Function Name :  updatePassword
        * Purpose       :  This function use for update password of User.
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  Request $request, intiger $id
        * Return Value  :  loads listing page on success and load change password page for any error during the operation
    */

    public function updatePassword(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'password'          => 'required',
                'confirmpassword'   => 'required',
            ],
            [
                'password.required'         => 'Password is Required',
                'confirmpassword.required'  => 'Confirm Password is Required',
            ]
        );
        try{
            $model = User::find($id);
            if(!$model){
                throw new Exception("No result was found for id: $id");
            }            
            $model->password          = $request->password;
            $model->save();
            return \Redirect::Route($this->listUrl)->with('success', 'Password updated Successfully');
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }       
    }    

    /**
        * Function Name :  delete
        * Purpose       :  This function use for delete records from User.
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  intiger $id
        * Return Value  :  loads listing page
    */

    public function delete($id)
    {
        try{
            $model = User::find($id);
            $model->delete();
            return \Redirect::Route($this->listUrl)->with('success', 'Record deleted Successfully');
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }
 

     public function view($id)
    {
        try{ 
            $data['id'] = $id;           
            return view($this->viewFolderPath.'.view', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }



}
