<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use Hash;
use \Redirect;
use Mail;
use Crypt;
use Exception;
use App\User;
use GlobalVars;
use App\PasswordReset;
use Session;

class LoginController extends Controller
{
    /**
        * Function Name :  index
        * Purpose       :  This function loads login form if user is not loged in otherwise loads dashboard page.
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  void
        * Return Value  :  login/dashboard page
    */
    public function index()
    {
  
        try{
            $data['title']      = 'Control Panel Login';
            if (\Auth::guard('admin')->check()) {
                return \Redirect::route('dashboard');
            } else {
                return view('admin/adminuser.login', $data);
            }
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }       
    }

    /**
        * Function Name :  dologin
        * Purpose       :  This function use for login a valid user.
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  \Illuminate\Http\Request $request
        * Return Value  :  if user is valid then redirect to dashboard otherwise return to login form
    */
    public function dologin(Request $request)
    {
        $Validator = Validator::make($request->all(), [
                'email'     => 'required',
                'password'  => 'required'
            ]);

        try{    
            if ($Validator->fails()) {
            //$request->session()->flash('error','Please Enter All Fields');
                return \Redirect::route('admin_login')->withErrors($Validator);
            } else {
                $checkUserstatus = User::where('email', $request->input('email'))->where('status', GlobalVars::ACTIVE_STATUS)->whereIn('user_type', ['A','SA'])->first();
				echo "<pre>"; print_r($checkUserstatus); exit; 
                if ($checkUserstatus) {
                    $auth = auth()->guard('admin')->attempt([
                        'email'     => $request->input('email'),
                        'password'  => $request->input('password')
                    ]);
					$sId=Session::put('key', $checkUserstatus->id);
					$utype=Session::put('utype', $checkUserstatus->user_type);
					$ulogo=Session::put('ulogo', $checkUserstatus->user_logo);
                    if ($auth) {
                        return \Redirect::Route('dashboard');
                    } else {
                        $request->session()->flash('error', 'Invalid Username or Password');
                        return \Redirect::Route('admin_login');
                    }
                } else {
                    $request->session()->flash('error', 'You are not an authorized user');
                    return \Redirect::Route('admin_login');
                }
            }
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }
    /**
        * Function Name :  logout
        * Purpose       :  This function use for login a valid user.
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  void
        * Return Value  :  return to login form
    */
    public function logout()
    {
        try{
            \Auth::guard('admin')->logout();
            return \Redirect::Route('admin_login');
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }        
    }

    /**
        * Function Name :  forgot_password
        * Purpose       :  This function use for generate forgort password.
        * Author        :
        * Created Date  :  
        * Modified date :          
        * Input Params  :  \Illuminate\Http\Request $request
        * Return Value  :  void
    */    

    public function forgot_password(Request $request) {        
        try{ 
            $data['title']      = 'Forget Password';
            if ($request->isMethod('post')) {
                $Validator = Validator::make($request->all(),[
                    'email'         => 'required|email',
                    ]
                );
                if($Validator->fails()) {
                    return \Redirect::back()->withErrors($Validator);
                }else {
                    $settings  = \Helpers::getSiteSettingsData();
                    $email = $request->email;
                    /*$emailAddressExists = User::Where('email', $email)->where('user_type', \GlobalVars::ADMIN_USER_TYPE)->first();*/
					$emailAddressExists = User::where('email', $request->input('email'))->where('status', GlobalVars::ACTIVE_STATUS)->whereIn('user_type', ['A','SA'])->first();
                    if($emailAddressExists){
                        //$websitePath = \GlobalVars::ADMIN_SITE_URL;
                        $token = uniqid(mt_rand(), true);
    
                        //save token for forgot password request
                        $resetPwd = new PasswordReset();
                        $resetPwd->email = $emailAddressExists->email;
                        $resetPwd->token = $token;
                        $resetPwd->save();
                        //$url = $websitePath.'reset-password/'.Crypt::encrypt($emailAddressExists->email.'_'.$token);
                        $url = Route('admin_reset_newpassword', \Helpers::encryptId($emailAddressExists->email.'_'.$token));
                        $mailData = array(
                            'organisationName'                  => $settings['from_email_name'],
                            'organisationEmail'                 => 'debnidhi.kuila@gmail.com',
                            'title'                             => 'Forgot Password',
                            'userName'                          => $emailAddressExists->first_name.' '.$emailAddressExists->last_name,
                            'userEmail'                         => $email,
                            'url'                               => $url,
                            'emailHeaderSubject'                => 'Reset Your Password'
                        );
                        
                        Mail::send('emails.resetPasswordAdmin', $mailData, function ($message) 
						use ($mailData) {
							$message->from($mailData['organisationEmail'], $mailData['organisationName']);
							$message->to($mailData['userEmail'], $mailData['organisationName'])->subject(config('global.SITE_ADDRESS_NAME').' '.$mailData['emailHeaderSubject']);
							});
    
                        $data['validate'] = 'Success';
                        $data['message'] = 'Y';
                        return \Redirect::back()->with('success', 'A link has been sent to your email id.');
                    }else{
                        return \Redirect::back()->with('error', 'This user doesn\'t exists');
                    }
                }                
            }
            return view('admin/adminuser.forget_password', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }

    public function resetPassword($encryptToken) {
        try{
            $decryptToken   = \Helpers::decryptId($encryptToken);//decrypt the token
            $tokenArr       = explode("_",$decryptToken);
            $email          = $tokenArr[0];
            $token          = $tokenArr[1];

            $data['token'] = $encryptToken;
            //check whether this token exist or not
            $resultToken = PasswordReset::where([
                ['email', $email],
                ['token', $token]
            ])->get();

            if(!$resultToken->count() > 0){
                return \Redirect::Route('admin_forgot_password')->with('error', 'The link has expired.');
            }
            return view('admin/adminuser.reset_password', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }

    public function updatePassword(Request $request) {
        try{

            $Validator = Validator::make($request->all(),[
                'password' => 'required|min:5',
                'confirm_password' => 'required|same:password'
                ]
            );

            if ($Validator->fails()) {
                return \Redirect::back()->withErrors($Validator);
            }else{ 
                $decryptToken   = \Helpers::decryptId($request->token);//decrypt the token
                $tokenArr       = explode("_",$decryptToken);
                $email          = $tokenArr[0];
                $token          = $tokenArr[1];

                $password       = $request->password;
                $confPassword   = $request->confirm_password;
                //$objUser = User::where('email',$email)->where('user_type', \GlobalVars::ADMIN_USER_TYPE)->first();
				
				$objUser = User::where('email', $email)->where('status', GlobalVars::ACTIVE_STATUS)->whereIn('user_type', ['A','SA'])->first();

                if (!empty($objUser)) {//if user exist

                    if (!(Hash::check($request->input('password'), $objUser->password))) {
                        $objUser->password = $password;
                        $objUser->save();
                        $objReset = PasswordReset::where('token',$token)->delete();
                        $request->session()->flash('success', 'Password reset successfully.');
                        return \Redirect::Route('admin_login');                        
                    }else{
                        return \Redirect::back()->with('error', 'New password should not be same with the old password');
                    }
                }else{
                    //session()->flash('alert-danger', 'Something went Wrong. Please try reset password again.');
                    return \Redirect::back()->with('error', 'Some error occurred. Please try again');
                }
            }                           
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }

}
