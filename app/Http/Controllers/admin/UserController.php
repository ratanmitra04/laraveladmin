<?php
/***********************************************/
# Company Name    :                             
# Author          : KB                            
# Created Date    :                            
# Controller Name : UserController               
# Purpose         : User Management             
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
use App\City;
use App\Location;
use App\User;
use App\Category;
use App\Preference;
use \Validator;
use Auth;

class UserController extends Controller
{
    use GeneralMethods;
    public $controllerName  = 'User';
    public $management;
    public $modelName       = 'User';
    public $breadcrumb;
    public $routePrefix     = 'users';
    public $listUrl         = 'users.list';
    public $viewFolderPath  = 'admin/users';

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
        $this->management      = 'User';
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
           // $data['userTypes']                  = ['W'=>'Wholesale','R'=>'Retail'];
            $data['user_type']          = $request->user_type;
            $data['records'] = User::where('id','!=',1)->where('user_type', '!=', 'SA');

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
			$data['cityname'] = City::where('id', $data['record']['city_id'])->first();
			//$data['locationname'] = Location::where('id', $data['record']['location_id'])->first();
			$data['category'] = Category::where('id','!=','')->orderby('category_name', 'ASC')->get();
			$recommended = Preference::where('user_id', $data['record']['id'])->get();
			$selectedRecommended=[];
			foreach($recommended as $key=>$value)
			{
				$selectedRecommended[]=$value->preference_category;
			}
			$data['selectedRecommended'] = $selectedRecommended;
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
        $data['city'] = City::where('id','!=','')->orderby('city_name', 'ASC')->get();
		//echo "<pre>"; print_r($data);
		//$data['area'] = location::where('city_id', $data['city'])->get();
		$data['category'] = Category::where('id','!=','')->orderby('category_name', 'ASC')->get();
		return view($this->viewFolderPath.'.add', $data);
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
                'email'                 => 'required|email|unique:users,email',
				'password'              => 'required|min:6|max:10',
				/*'phone'                 =>'nullable|numeric|digits_between:10,15'*/
                /*'phone'                 => 'numeric|min:10|max:15',*/
                /*'user_type'             => 'required',*/
				'age'					=>'nullable|integer|between:16,100'
               
            ],
            [
                'first_name.required'     => 'First Name is Required',
                'last_name.required'      => 'Last Name is Required',
                'email.required'          => 'Email is Required',
                'password.required'       => 'Password is Required',
				/*'phone.required'          => 'Phone is Required',
                'user_type.required'      => 'User Type is Required',*/
                
            ]
        );
        try{
            $model                      = new User;
            $model->first_name          = $request->first_name;
            $model->last_name           = $request->last_name;
            $model->email               = $request->email;
			$model->password            = $request->password;
            $model->phone               = $request->phone;
            $model->gender              = $request->gender;
            $model->age               	= $request->age;
			
            $model->city_id             = $request->city;
            //$model->location_id         = $request->area;
            
            $model->user_type           = $request->user_type;
            $model->login_type          = 'NL';
            $model->status              = $request->status;
			
			if ($files = $request->file('user_logo')) {
			   $files->getClientOriginalExtension();
			   $destinationPath = 'public/uploads/user_images/'; // upload path
			   $profilefile = 'image_'.time() . "." . $files->getClientOriginalExtension();
			   $files->move($destinationPath, $profilefile);
			   $insert['file'] = "$profilefile";
			   
			   $model->user_logo      = 'image_'.time() . "." . $files->getClientOriginalExtension();
			}
            $model->save();
			
			/****28-01-2021****/
			if($request->recommended_cat)
			{
				$user_id=$model->id;
				//$last_insert_id=$user->id;
				for ($i = 0; $i < count($request->recommended_cat); $i++) 
				{
					$preference                   		= new Preference;
					$preference->user_id       	 		= $user_id;
					$preference->preference_category  	= $request->recommended_cat[$i];
					
					$preference->save();
				}
			}
			/****28-01-2021****/
			
			$actionBy= Auth::guard('admin')->user()->id;
			$last_insert_id=$model->id;
			$this->createLog($actionBy,1,1,$last_insert_id,$request->first_name);
			
			if($model)
			{
					$mailData = array(
                        'organisationName'             => 'Custom Black Index',
                        'organisationEmail'            => 'debnidhi.kuila@gmail.com',
                        'title'                        => 'Registration Success',
                        'userName'                     => $request->first_name.' '.$request->last_name,
                        'userEmail'                    => $request->email,
                        'password'                     => $request->password,
                        'emailHeaderSubject'           => 'Registration'   	
                    );
                    Mail::send('emails.user-registration-admin', $mailData, function ($message) 
                    use ($mailData) {
						$message->from($mailData['organisationEmail'], $mailData['organisationName']);
						$message->to($mailData['userEmail'], $mailData['organisationName'])->subject(config('global.SITE_ADDRESS_NAME').': '.$mailData['emailHeaderSubject']);
                    });	
			}
			
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
			$data['city'] = City::where('id','!=','')->orderby('city_name', 'ASC')->get();
			//$data['area'] = location::where('city_id', $data['record']['city_id'])->get();
			$data['category'] = Category::where('id','!=','')->orderby('category_name', 'ASC')->get();
			$recommended = Preference::where('user_id', $data['record']['id'])->get();
			$selectedRecommended=[];
			foreach($recommended as $key=>$value)
			{
				$selectedRecommended[]=$value->preference_category;
			}
			$data['selectedRecommended'] = $selectedRecommended;
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
                /*'email'         => 'required|email|unique:users,email,'.$id,*/
               'email'         => 'required|email',
               'city'         => 'required',
				/*'phone'         =>'nullable|numeric|digits_between:10,15'*/
                /*'phone'         => 'required|numeric',
                'user_type'     => 'required',*/
				'age'			=>'nullable|integer|between:16,100'
            ],
            [
                'first_name.required'     => 'First Name is Required',
                'last_name.required'      => 'Last Name is Required',
                'email.required'          => 'Email is Required',
                'city.required'          => 'City is Required',
                /*'phone.required'          => 'Phone is Required',
                'user_type.required'      => 'User Type is Required',*/
            ]
        );
        try{
            $model = User::find($id);
            $model->first_name          = $request->first_name;
            $model->last_name           = $request->last_name;
            $model->email               = $request->email;
            $model->phone               = $request->phone;
			$model->gender              = $request->gender;
            $model->age               	= $request->age;
            $model->user_type           = $request->user_type;
			$model->login_type			= 'NL';
            $model->status              = $request->status;
			
			$model->city_id             = $request->city;
            //$model->location_id         = $request->area;
			
            //$model->user_logo           = $request->user_logo;
			$files = $request->file('user_logo');
			//echo "<pre>"; print_r($files);exit;
			if ($files = $request->file('user_logo')) {
				
				$destinationPath = 'public/uploads/user_images/'; // upload path
				$oldImgName=$model->user_logo;
				if(file_exists($destinationPath.$model->user_logo)){
					@unlink($destinationPath.$oldImgName);
				}
				
			   $files->getClientOriginalExtension();
			   //$destinationPath = 'uploads/user_images/'; // upload path
			   $profilefile = 'image_'.time() . '.' . $files->getClientOriginalExtension();
			   $files->move($destinationPath, $profilefile);
			   //$insert['file'] = "$profilefile";
			   
			   $model->user_logo      = 'image_'.time() . "." . $files->getClientOriginalExtension();
			}
			
			/****28-01-2021****/
			if($request->recommended_cat)
			{
				$user_id=$model->id;
				DB::table('preferences')->where('user_id', $user_id)->delete();
				for ($i = 0; $i < count($request->recommended_cat); $i++) 
				{
					$preference                   		= new Preference;
					$preference->user_id       	 		= $id;
					$preference->preference_category  	= $request->recommended_cat[$i];
					
					$preference->save();
				}
			}
			/****28-01-2021****/
			
            $model->save();
			
			$actionBy= Auth::guard('admin')->user()->id;
			$this->createLog($actionBy,1,2,$id,$request->first_name);
			
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
			 
			 $actionBy= Auth::guard('admin')->user()->id;
			 $this->createLog($actionBy,1,3,$id,$model->first_name);
			 
            //return \Redirect::Route($this->listUrl)->with('success', trans('admin_common.record_deleted'));
			return \Redirect::Route($this->listUrl)->with('success', 'Record deleted Successfully');
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
	
	public function export()
	{
		$headers = [
				'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
			,   'Content-type'        => 'text/csv'
			,   'Content-Disposition' => 'attachment; filename=user.csv'
			,   'Expires'             => '0'
			,   'Pragma'              => 'public'
		];

		$list = User::all('id','first_name','last_name','gender','user_type','email','phone','age','login_type','city_id','user_logo','radious','platform','status','created_at')->sortByDesc('created_at')->toArray();
		//echo "<pre>"; print_r($list); exit;
		foreach($list as $key=>$value)
		{
			$catDetails = Preference::where('user_id', $value['id'])->pluck('preference_category')->toArray();
			$catName = Category::whereIn('id', $catDetails)->pluck('category_name')->toArray();
			
			if(empty($value['city_id']))
			{
				$list[$key]['city_name']= 'N/A';
			}
			else
			{
				$cityDetails = City::where('id', $value['city_id'])->first();
				$list[$key]['city_name']= $cityDetails->city_name;
			}
			if(count($catName)>0)
			{
				$list[$key]['preference_category']= implode(',',$catName);
			}
			if($value['login_type']=='NL')	
			{
				$list[$key]['login_type']= 'Normal';
			}
			if($value['login_type']=='FB')	
			{
				$list[$key]['login_type']= 'Facebook';
			}	
			if($value['login_type']=='GP')	
			{
				$list[$key]['login_type']= 'Google';
			}
			if($value['user_type']=='A')
			{
				$list[$key]['user_type']= 'Admin';
			}
			if($value['user_type']=='SA')
			{
				$list[$key]['user_type']= 'Moderator';
			}
			if($value['user_type']=='CU')
			{
				$list[$key]['user_type']= 'Customer';
			}
			if($value['user_type']=='VU')
			{
				$list[$key]['user_type']= 'Vendor';
			}
			if($value['user_type']=='AU')
			{
				$list[$key]['user_type']= 'Advertise';
			}	
		}
		# add headers for each column in the CSV download
	    array_unshift($list, ['Firstname','Lastname','Gender','UserType','Email','Phone','Age','LoginType','Logo','Radius','Platform','Status','Registered On','City','Recommended Category']);
	   $callback = function() use ($list)
		{
			$FH = fopen('php://output', 'w');
			foreach ($list as $key=>$row) {
				unset($row['id']);	
				unset($row['city_id']);
				fputcsv($FH, $row);
			}
			fclose($FH);
		};

		return response()->stream($callback, 200, $headers);
	}
	
	
	public function getAreas($id)
	{
		// Fetch Location by City id
        $dataArea['record'] = Location::orderby("location_name","asc")
        			->select('id','location_name')
        			->where('city_id',$id)
        			->get();
  
        return response()->json($dataArea);
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
