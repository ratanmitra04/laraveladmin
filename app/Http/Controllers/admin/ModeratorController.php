<?php
/***********************************************/
# Company Name    :                             
# Author          : KB                            
# Created Date    :                            
# Controller Name : ModeratorController               
# Purpose         : Moderator Management             
/***********************************************/

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use DB;
use Mail;
use App\Helpers\CommonHelper;
use GlobalVars;
use App\Traits\GeneralMethods;
use Exception, Image;
use App\Moderator;
use App\User;
use App\Management;
use App\Activities;


class ModeratorController extends Controller
{
    use GeneralMethods;
    public $controllerName  = 'Moderator';
    public $management;
    public $modelName       = 'Moderator';
    public $breadcrumb;
    public $routePrefix     = 'moderators';
    public $listUrl         = 'moderators.list';
    public $viewFolderPath  = 'admin/moderators';

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
        $this->management      = 'Moderator';
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
            $data['pagenum']        = isset($request->page)?$request->page:1;
			$data['keyword']        = '';
           // $data['userTypes']                  = ['W'=>'Wholesale','R'=>'Retail'];
            $data['user_type']          = $request->user_type;
            $data['records'] = User::where('id','!=','')->where('user_type', '=', 'SA');

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
	/** 02-10-2020 Debnidhi **/
	public function view($id)
    {
        try{ 
            $data['id'] = $id;
			$data['record'] = User::find($id);
			$data['managementname'] = Moderator::where('user_id', $data['record']['id'])->get();
            if(!$data['record']){
                throw new Exception("No result was found for id: $id");
            }	
            return view($this->viewFolderPath.'.view', $data);
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
		//echo "<pre>"; print_r($request); exit;
	   $this->validate(
            $request,
            [
                'first_name'            => 'required',
                'last_name'             => 'required',
                'email'                 => 'required|email',
				'phone'                 => 'required|numeric',
				'password'              => 'required',
				'status'                => 'required',
				'mod_management'        => 'required',
               
            ],
            [
                'first_name.required'     => 'First Name is Required',
                'last_name.required'      => 'Last Name is Required',
                'email.required'          => 'Email is Required',
				'phone.required'          => 'Phone is Required',
                'password.required'       => 'Password is Required',
				 'status.required'         => 'Status is Required',
                'mod_management.required' => 'Permission is Required',
                
            ]
        );
        try{
			$existUser = User::where('email',$request->email)->where('user_type','SA')->count();
			if($existUser>0)
			{
				return \Redirect::back()->with('error', 'The email already taken.');
			}
			else
			{
				$model                      = new User;
				$model->first_name          = $request->first_name;
				$model->last_name           = $request->last_name;
				$model->email               = $request->email;
				$model->password            = $request->password;
				$model->phone               = $request->phone;
				$model->user_type           = 'SA';
				$model->status              = $request->status;
				
				if ($files = $request->file('user_logo')) {
				   $files->getClientOriginalExtension();
				   $destinationPath = 'public/uploads/adminuser_logos/'; // upload path
				   $profilefile = 'image_'.time() . "." . $files->getClientOriginalExtension();
				   $files->move($destinationPath, $profilefile);
				   $insert['file'] = "$profilefile";
				   
				   $model->user_logo      = 'image_'.time() . "." . $files->getClientOriginalExtension();
				}
				
				$model->save();
				
				$last_insert_id=$model->id; // get last inserted id
				//echo "<pre>";print_r($request->mod_management); exit;
				if($request->mod_management)
				{
					for ($i = 0; $i < count($request->mod_management); $i++) {
						
						$moderator                   	= new Moderator;
						$moderator->user_id       		= $last_insert_id;
						$moderator->management_name     = $request->mod_management[$i];
					
						$moderator->save();
					}
				}	

				return \Redirect::Route($this->listUrl)->with('success', 'Record added Successfully');
			}
			
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
			$selectedmanagementname=[];
			if(Moderator::where('user_id',$id)->count()>0)
			{
				 $selectedmanagementname= Moderator::where('user_id',$id)->pluck('management_name')->toArray();
			}
			$data['selectedmanagementname']=$selectedmanagementname;
			//echo "<pre>"; print_r($data['selectedmanagementname']);		exit;	
			$data['managementname'] = Management::pluck('name');
//echo "<pre>"; print_r($data['managementname']);		//exit;	
//echo "<pre>"; print_r($data);		exit;	
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
                'first_name'            => 'required',
                'last_name'             => 'required',
                'email'         => 'required|email',
				'phone'                 => 'required|numeric',
               
            ],
            [
                'first_name.required'     => 'First Name is Required',
                'last_name.required'      => 'Last Name is Required',
                'email.required'          => 'Email is Required',
				'phone.required'          => 'Phone is Required',
                
            ]
        );
        try{
            $model = User::find($id);
			
            $model->first_name          = $request->first_name;
            $model->last_name           = $request->last_name;
            $model->email               = $request->email;
			$model->password            = $request->password;
            $model->phone               = $request->phone;
            $model->user_type           = 'SA';
            $model->status              = $request->status;
			
			if ($files = $request->file('user_logo')) {
				
				$destinationPath = 'public/uploads/adminuser_logos/'; // upload path
				$oldImgName=$model->user_logo;
				if(file_exists($destinationPath.$model->user_logo)){
					@unlink($destinationPath.$oldImgName);
				}
				
			   $files->getClientOriginalExtension();
			   //$destinationPath = 'uploads/user_images/'; // upload path
			   $profilefile = 'image_'.time() . "." . $files->getClientOriginalExtension();
			   $files->move($destinationPath, $profilefile);
			   $insert['file'] = "$profilefile";
			   
			   $model->user_logo      = 'image_'.time() . "." . $files->getClientOriginalExtension();
			}
			
			if(count($request->mod_management)>0)
			{
				DB::table('moderators')->where('user_id', $id)->delete();
				
				for ($i = 0; $i < count($request->mod_management); $i++) 
				{
					$moderator                   = new Moderator;
					$moderator->user_id       	 = $id;
					$moderator->management_name  = $request->mod_management[$i];
					
					$moderator->save();
				}
			}
			
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
			 //DB::table('moderators')->where('user_id', $id)->delete();
            //return \Redirect::Route($this->listUrl)->with('success', trans('admin_common.record_deleted'));
			return \Redirect::Route($this->listUrl)->with('success', 'Record deleted Successfully');
		 }catch(Exception $e){
             throw new \App\Exceptions\AdminException($e->getMessage());
         }
    }
	
	public function export()
	{
		$headers = [
				'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
			,   'Content-type'        => 'text/csv'
			,   'Content-Disposition' => 'attachment; filename=moderator.csv'
			,   'Expires'             => '0'
			,   'Pragma'              => 'public'
		];

		$listCount = User::where('user_type', '=', 'SA')->count();
		if($listCount>0){
			//$list = User::where('user_type', '=', 'SA')->get()->toArray();
			$list =User::where('user_type', 'SA')->get(['id','first_name','last_name','email','phone','user_logo','status'])->toArray();
			//echo "<pre>"; print_r($list); exit;
			/*foreach($list as $key=>$value)
			{
				$permissionDetails = Moderator::where('user_id', $value['id'])->get();
				for($i=0; $i<count($permissionDetails); $i++)
					{
						$abc=$permissionDetails[$i]->management_name;
					}
				$list[$key]['management_name']= $abc;
			}*/
			# add headers for each column in the CSV download
			array_unshift($list, ['Firstname','Lastname','Email','Phone','Logo','Status']);

		   $callback = function() use ($list)
			{
				$FH = fopen('php://output', 'w');
				foreach ($list as $key=>$row) {
					unset($row['id']);
					fputcsv($FH, $row);
				}
				fclose($FH);
			};

			return response()->stream($callback, 200, $headers);
		}else{
			echo "No record found.";
			exit;
		}
	}
	
	
	public function activity($id, Request $request)
	{
		try{ 
            $data['pagenum']  = isset($request->page)?$request->page:1;
			$data['keyword']        = '';
			
			$data['formDate'] = (isset($request->formDate) && !empty($request->formDate))?$request->formDate:'' ;
			$data['toDate']   = (isset($request->toDate) && !empty($request->toDate))?$request->toDate:'' ;
			$data['management'] = (isset($request->management) && !empty($request->management))?$request->management:'' ;
			
            $data['id'] = $id;
			$data['records'] = Activities::with(['management'])->where('action_by',$id);
			
			$data['actionByDetail'] = User::where('id',$id)->first();
			$data['managementInfo'] = Management::get();
			
			//$searchQuery = Activities::query();

			if($data['formDate']!='')
			{
				$data['records'] = $data['records']->where('created_at', '>=', $data['formDate'].' 00:00:00');
			}
			if($data['toDate']!='')
			{
				$data['records'] = $data['records']->where('created_at', '<=', $data['toDate'].' 23:59:59');
			}
			if($data['management']!='')
			{
				$data['records'] = $data['records']->where('management_id', '=', $data['management']);
			}

            if(!$data['records']){
                throw new Exception("No result was found for id: $id");
            }	
			$data['records'] = $data['records']->orderBy('id','desc')->paginate(GlobalVars::ADMIN_RECORDS_PER_PAGE);
			//echo "<pre>"; print_r($data['records']->toArray());exit;
            return view($this->viewFolderPath.'.activity', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
	}
	
    /**
        * Function Name :  getUserDropDownByType
        * Purpose       :  This function use for get the options of user by user type.
        * Author        :
        * Created Date  : 24/07/2019
        * Modified date :          
        * Input Params  :  Request $request
        * Return Value  :  options[string]
    */

    public function getUserDropDownByType(Request $request){
        $users = User::where('user_type', $request->type)->where('status', 'Active')->get();
        if($request->type=='R'){
            $optionList = '<option value="">All Retail</option>';
        }else{
            $optionList = '<option value="">All Wholesale</option>';
        }
        
        foreach ($users as $user) {
            $optionList .= '<option value='.$user->id.'>'.$user->first_name.' '.$user->last_name.'</option>';
        }
        return $optionList;
    }

    /**
        * Function Name :  updateAdminProfile
        * Purpose       :  This function is use for update admin profile.
        * Author        :
        * Created Date  : 19-11-2019
        * Modified date :          
        * Input Params  :  \Illuminate\Http\Request $request
        * Return Value  :  void
    */

    public function updateAdminProfile(Request $request) {
        $user = User::find(auth()->guard('admin')->user()->id);
        $data['record']      = $user;
        try{
            $data['title']      = 'Update Profile';
            if ($request->isMethod('post')) {
                $Validator = Validator::make($request->all(), [
                    'first_name'         => 'required',
                    'last_name'          => 'required',
                    'email'              => 'required|email|unique:users,email,'.$user->id,
                ]);

                if ($Validator->fails()) {
                    return \Redirect::route('admin_update_profile')->withErrors($Validator);
                } else {
                    $file               = $request->file('user_logo');
                    if($file){
                        $fileName           = time() . mt_rand(). '.' .$file->getClientOriginalExtension();
                        $mainImagePath      = public_path().\GlobalVars::ADMIN_USER_LOGO_PATH;
                        if(Image::make($file->getRealPath())->resize(160, 160, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($mainImagePath.$fileName)){
                            \File::delete($mainImagePath.$user->user_logo);
                            $user->user_logo     = $fileName;                            
                        }                       
                    }

                    $user->email         = $request->email;
                    $user->first_name    = $request->first_name;
                    $user->last_name     = $request->last_name;
                    if($user->save()){
                        $request->session()->flash('success', 'Profile updated successfully!! Please logout and login again.');
                        return \Redirect::Route('admin_update_profile'); 
                    }else{
                        $request->session()->flash('error', 'Something went wrong! try again.');
                        return \Redirect::Route('admin_update_profile');
                    }
                }
            }
            return view('admin.adminuser.update_profile', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }        
    }


    // public function teachers(Request $request)
    // {
    //     try{
    //         $data['keyword']        = '';
            
    //         $data['user_type']      = $request->user_type;
    //         $data['records'] = User::where('id','!=',1)->where('user_type','=','T');

    //         if($request->keyword !=''){
    //             // search section
    //             $data['keyword'] = $request->keyword;
    //             $keywordArray = explode(' ', $data['keyword']);
                
    //             if(count($keywordArray) == 2){ // If keyword have two words
    //                  $data['records'] = $data['records']->where(function($q) use ($data, $keywordArray){
    //                     $q->where('first_name','like','%'.$keywordArray[0].'%')
    //                  ->where('last_name','like','%'.$keywordArray[1].'%');
    //                  });                     
    //             }else{

    //                  $data['records'] = $data['records']->where(function($q) use ($data){
    //                     $q->Where('first_name','like','%'.$data['keyword'].'%')
    //                      ->orWhere('last_name','like','%'.$data['keyword'].'%')
    //                      ->orWhere('email','like','%'.$data['keyword'].'%')
    //                      ->orWhere('phone','like','%'.$data['keyword'].'%');
    //                  });
    //             }
    //         }

    //         if($data['user_type']){
    //             $data['records'] = $data['records']->where('user_type', $data['user_type']);
    //         }            

    //         $data['records'] = $data['records']->orderBy('id','desc')->paginate(GlobalVars::ADMIN_RECORDS_PER_PAGE);

    //         return view($this->viewFolderPath.'.teachers', $data);
    //     }catch(Exception $e){
    //         throw new \App\Exceptions\AdminException($e->getMessage());
    //     }
    // }




}
