<?php
/***********************************************/
# Company Name    :                             
                        
# Created Date    :                                
# Controller Name : NotificationController                 
# Purpose         : Notification Management             
/***********************************************/

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use DB;
use Mail;
use App\Helpers\CommonHelper;
use GlobalVars;
use Exception;
use App\Traits\GeneralMethods;
use App\Notification;
use App\NotificationDetail;
use App\User;

class NotificationController extends Controller
{
    
    use GeneralMethods;
    public $controllerName  = 'Notification';
    public $management;
    public $modelName       = '';
    public $breadcrumb;
    public $routePrefix     = 'notification';
    public $listUrl         = 'notification.list';
    public $viewFolderPath  = 'admin/notification';
    
    public function __construct()
    {
        parent::__construct();
        $this->management      = 'Notification';
        // Begin: Assign breadcrumb for view pages
        $this->assignBreadcrumb();
        // End: Assign breadcrumb for view pages
        // Variables assign for view page
        $this->assignShareVariables();
        
    }
    
    public function index(Request $request)
    {
        try{
			$data['keyword']        = '';
            $data['records'] 		= Notification::where('id','!=','');

            if($request->keyword !=''){
                // search section
                $data['keyword'] = $request->keyword;
                
				 $data['records'] = $data['records']->where(function($q) use ($data){
					$q->Where('title','like','%'.$data['keyword'].'%')
					 ->orWhere('type','like','%'.$data['keyword'].'%');
				 });
            }           

            $data['records'] = $data['records']->orderBy('id','desc')->paginate(GlobalVars::ADMIN_RECORDS_PER_PAGE);
            return view($this->viewFolderPath.'.list', $data);
        
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }

    public function view($id)
    {
        try{ 
            $data['id'] = $id;    
			$data['record'] = Notification::find($id);
            if(!$data['record']){
                throw new Exception("No result was found for id: $id");
            }	
            return view($this->viewFolderPath.'.view', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    } 

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
                'title'            => 'required',
                'description'      => 'required',
                /*'email'            => 'required|email|unique:users,email',*/
                'sendto'           => 'required',
            ],
            [
                'title.required'     		=> 'Title is Required',
                'description.required'      => 'Description is Required',
                /*'email.required'          	=> 'Email is Required',*/    
                'sendto.required'          	=> 'Send to is Required',    
            ]
        );
        try{
            //echo "=>"; exit;
			$email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
			if($request->sendto=='other' && $email=='')
			{
				return \Redirect::back()->with('error', 'Email is Required');
			}
			
			else if ($request->sendto=='other' && !filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
				return \Redirect::back()->with('error', 'Email is not valid');
			} 
			
			else
			{
				$model                      = new Notification;
				$model->title          		= $request->title;
				$model->description         = $request->description;
				$model->type             	= $request->type;
				$model->sendto             	= $request->sendto;
				$model->email               = $request->email;
				
				$model->save();
				$last_insert_id=$model->id;
				if($model)
				{
					if($request->type=='PushNotification' && $request->sendto=='OnlyCustomer')
					{
						/*Notification for IOS Start*/
						$firebaseTokenIos = User::whereNotNull('fcm_token')->where('user_type','CU')->where('notification_status','1')->where('platform','IOS')->pluck('fcm_token')->all();

						$SERVER_API_KEY = 'AAAAjmgEoyU:APA91bHMbmg4Ket-GIrlIMqo35zjP9-kecYSpfTUhIwTCKwoLAZiARHarpGpXBgIgGBSO9JgL9HkcJhMJvMLn-2ryYNvtnfpdcLN7iE-2vzPyQuCddZXteAsaOBKwm-dz4DV5lcji5sr';
						$dataIos = [
							"registration_ids" => $firebaseTokenIos,
							"sound" => true,
							"notification" => [
								"title" => $request->title,
								"body" => $request->description,  
							]
						];
						$dataStringIos = json_encode($dataIos);
					
						$headers = [
							'Authorization: key=' . $SERVER_API_KEY,
							'Content-Type: application/json',
						];
					
						$ch = curl_init();
					  
						curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $dataStringIos);
							   
						$response = curl_exec($ch);
						$pjs = json_decode($response, TRUE);
						if($pjs['success']>0)
						{
							$users = User::whereNotNull('fcm_token')->where('user_type','CU')->where('notification_status','1')->where('platform','IOS')->get();
							for($i=0;$i<COUNT($users);$i++)
							{
								$detail                     = new NotificationDetail;
								$detail->notification_id    = $last_insert_id;
								$detail->user_id         	= $users[$i]->id;
								$detail->is_read            = '0';
								$detail->is_deleted         = '0';
								
								$detail->save();
							}
						}
						/*Notification for IOS End*/
						
						/*Notification for Android Start*/
						$firebaseTokenAndroid = User::whereNotNull('fcm_token')->where('user_type','CU')->where('notification_status','1')->where('platform','android')->pluck('fcm_token')->all();

						$SERVER_API_KEY = 'AAAAjmgEoyU:APA91bHMbmg4Ket-GIrlIMqo35zjP9-kecYSpfTUhIwTCKwoLAZiARHarpGpXBgIgGBSO9JgL9HkcJhMJvMLn-2ryYNvtnfpdcLN7iE-2vzPyQuCddZXteAsaOBKwm-dz4DV5lcji5sr';
						$data = [
							"registration_ids" => $firebaseTokenAndroid,
							"sound" => true,
							"data" => [
								"title" => $request->title,
								"body" => $request->description,  
							]
						];
						$dataStringAndroid = json_encode($data);
					
						$headers = [
							'Authorization: key=' . $SERVER_API_KEY,
							'Content-Type: application/json',
						];
					
						$ch = curl_init();
					  
						curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $dataStringAndroid);
							   
						$response = curl_exec($ch);
						$pjs = json_decode($response, TRUE);
						if($pjs['success']>0)
						{
							$users = User::whereNotNull('fcm_token')->where('user_type','CU')->where('notification_status','1')->where('platform','android')->get();
							for($i=0;$i<COUNT($users);$i++)
							{
								$detail                     = new NotificationDetail;
								$detail->notification_id    = $last_insert_id;
								$detail->user_id         	= $users[$i]->id;
								$detail->is_read            = '0';
								$detail->is_deleted         = '0';
								
								$detail->save();
							}
						}
						/*Notification for Android End*/
					}
					
					else if($request->type=='PushNotification' && $request->sendto=='OnlyVendors')
					{
						/*Notification for IOS Start*/
						$firebaseTokenIos = User::whereNotNull('fcm_token')->where('user_type','VU')->where('notification_status','1')->where('platform','IOS')->pluck('fcm_token')->all();

						$SERVER_API_KEY = 'AAAAjmgEoyU:APA91bHMbmg4Ket-GIrlIMqo35zjP9-kecYSpfTUhIwTCKwoLAZiARHarpGpXBgIgGBSO9JgL9HkcJhMJvMLn-2ryYNvtnfpdcLN7iE-2vzPyQuCddZXteAsaOBKwm-dz4DV5lcji5sr';
						$dataIos = [
							"registration_ids" => $firebaseTokenIos,
							"sound" => true,
							"notification" => [
								"title" => $request->title,
								"body" => $request->description,  
							]
						];
						$dataStringIos = json_encode($dataIos);
					
						$headers = [
							'Authorization: key=' . $SERVER_API_KEY,
							'Content-Type: application/json',
						];
					
						$ch = curl_init();
					  
						curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $dataStringIos);
							   
						$response = curl_exec($ch);
						$pjs = json_decode($response, TRUE);
						if($pjs['success']>0)
						{
							$users = User::whereNotNull('fcm_token')->where('user_type','VU')->where('notification_status','1')->where('platform','IOS')->get();
							for($i=0;$i<COUNT($users);$i++)
							{
								$detail                     = new NotificationDetail;
								$detail->notification_id    = $last_insert_id;
								$detail->user_id         	= $users[$i]->id;
								$detail->is_read            = '0';
								$detail->is_deleted         = '0';
								
								$detail->save();
							}
						}
						/*Notification for IOS End*/
						
						/*Notification for Android Start*/
						$firebaseTokenAndroid = User::whereNotNull('fcm_token')->where('user_type','VU')->where('notification_status','1')->where('platform','android')->pluck('fcm_token')->all();

						$SERVER_API_KEY = 'AAAAjmgEoyU:APA91bHMbmg4Ket-GIrlIMqo35zjP9-kecYSpfTUhIwTCKwoLAZiARHarpGpXBgIgGBSO9JgL9HkcJhMJvMLn-2ryYNvtnfpdcLN7iE-2vzPyQuCddZXteAsaOBKwm-dz4DV5lcji5sr';
						$data = [
							"registration_ids" => $firebaseTokenAndroid,
							"sound" => true,
							"data" => [
								"title" => $request->title,
								"body" => $request->description,  
							]
						];
						$dataStringAndroid = json_encode($data);
					
						$headers = [
							'Authorization: key=' . $SERVER_API_KEY,
							'Content-Type: application/json',
						];
					
						$ch = curl_init();
					  
						curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $dataStringAndroid);
							   
						$response = curl_exec($ch);
						$pjs = json_decode($response, TRUE);
						if($pjs['success']>0)
						{
							$users = User::whereNotNull('fcm_token')->where('user_type','VU')->where('notification_status','1')->where('platform','android')->get();
							for($i=0;$i<COUNT($users);$i++)
							{
								$detail                     = new NotificationDetail;
								$detail->notification_id    = $last_insert_id;
								$detail->user_id         	= $users[$i]->id;
								$detail->is_read            = '0';
								$detail->is_deleted         = '0';
								
								$detail->save();
							}
						}
						/*Notification for Android End*/
					}
					
					else if($request->type=='PushNotification' && $request->sendto=='AllUsers')
					{
						/*Notification for IOS Start*/
						$firebaseTokenIos = User::whereNotNull('fcm_token')->where('user_type','!=','A')->where('user_type','!=','SA')->where('notification_status','1')->where('platform','IOS')->pluck('fcm_token')->all();

						$SERVER_API_KEY = 'AAAAjmgEoyU:APA91bHMbmg4Ket-GIrlIMqo35zjP9-kecYSpfTUhIwTCKwoLAZiARHarpGpXBgIgGBSO9JgL9HkcJhMJvMLn-2ryYNvtnfpdcLN7iE-2vzPyQuCddZXteAsaOBKwm-dz4DV5lcji5sr';
						$dataIos = [
							"registration_ids" => $firebaseTokenIos,
							"sound" => true,
							"notification" => [
								"title" => $request->title,
								"body" => $request->description,  
							]
						];
						$dataStringIos = json_encode($dataIos);
					
						$headers = [
							'Authorization: key=' . $SERVER_API_KEY,
							'Content-Type: application/json',
						];
					
						$ch = curl_init();
					  
						curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $dataStringIos);
							   
						$response = curl_exec($ch);
						$pjs = json_decode($response, TRUE);
						if($pjs['success']>0)
						{
							$users = User::whereNotNull('fcm_token')->where('user_type','!=','A')->where('user_type','!=','SA')->where('notification_status','1')->where('platform','IOS')->get();
							for($i=0;$i<COUNT($users);$i++)
							{
								$detail                     = new NotificationDetail;
								$detail->notification_id    = $last_insert_id;
								$detail->user_id         	= $users[$i]->id;
								$detail->is_read            = '0';
								$detail->is_deleted         = '0';
								
								$detail->save();
							}
						}
						/*Notification for IOS End*/
						
						/*Notification for Android Start*/
						$firebaseTokenAndroid = User::whereNotNull('fcm_token')->where('user_type','!=','A')->where('user_type','!=','SA')->where('notification_status','1')->where('platform','android')->pluck('fcm_token')->all();

						$SERVER_API_KEY = 'AAAAjmgEoyU:APA91bHMbmg4Ket-GIrlIMqo35zjP9-kecYSpfTUhIwTCKwoLAZiARHarpGpXBgIgGBSO9JgL9HkcJhMJvMLn-2ryYNvtnfpdcLN7iE-2vzPyQuCddZXteAsaOBKwm-dz4DV5lcji5sr';
						$data = [
							"registration_ids" => $firebaseTokenAndroid,
							"sound" => true,
							"data" => [
								"title" => $request->title,
								"body" => $request->description,  
							]
						];
						$dataStringAndroid = json_encode($data);
					
						$headers = [
							'Authorization: key=' . $SERVER_API_KEY,
							'Content-Type: application/json',
						];
					
						$ch = curl_init();
					  
						curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $dataStringAndroid);
							   
						$response = curl_exec($ch);
						$pjs = json_decode($response, TRUE);
						if($pjs['success']>0)
						{
							$users = User::whereNotNull('fcm_token')->where('user_type','!=','A')->where('user_type','!=','SA')->where('notification_status','1')->where('platform','android')->get();
							for($i=0;$i<COUNT($users);$i++)
							{
								$detail                     = new NotificationDetail;
								$detail->notification_id    = $last_insert_id;
								$detail->user_id         	= $users[$i]->id;
								$detail->is_read            = '0';
								$detail->is_deleted         = '0';
								
								$detail->save();
							}
						}
						/*Notification for Android End*/
					}
					
					
					else if($request->type=='E-mail' && $request->sendto == 'OnlyVendors')
					{
						//echo "E-mail OnlyVendors"; exit;
						$emails = User::where('user_type','=','VU')->get();
						//dd($data['email']);
						for($i=0; $i<COUNT($emails); $i++)
						{
							//echo $emails[$i]['email'];
							$email=$emails[$i]['email'];
							$name=$emails[$i]['first_name'].' '.$emails[$i]['last_name'];
							
							$mailData = array(
								'organisationName'           => 'Custom Black Index',
								'organisationEmail'          => 'debnidhi.kuila@gmail.com',
								'title'                      => 'Notification',
								'userName'                   => $name,
								'emailHeaderSubject'         => $request->title,   	
								'emailDescription'           => $request->description  	
							);
							Mail::send('emails.user-notification', $mailData, function ($message) 
							use ($mailData,$email) {
								$message->from($mailData['organisationEmail'], $mailData['organisationName']);
								$message->to($email, $mailData['organisationName'])->subject(config('global.SITE_ADDRESS_NAME').' '.$mailData['emailHeaderSubject']);
							});
						}
						//exit;
					}
					else if ($request->type=='E-mail' && $request->sendto == 'OnlyCustomer')
					{
						//echo "E-mail OnlyCustomer"; exit;
						$emails = User::where('user_type','=','CU')->get();
						for($i=0; $i<COUNT($emails); $i++)
						{
							//echo $emails[$i]['email'];
							$email=$emails[$i]['email'];
							$name=$emails[$i]['first_name'].' '.$emails[$i]['last_name'];
							
							$mailData = array(
								'organisationName'           => 'Custom Black Index',
								'organisationEmail'          => 'debnidhi.kuila@gmail.com',
								'title'                      => 'Notification',
								'userName'                   => $name,
								'emailHeaderSubject'         => $request->title,   	
								'emailDescription'           => $request->description  	
							);
							Mail::send('emails.user-notification', $mailData, function ($message) 
							use ($mailData,$email) {
								$message->from($mailData['organisationEmail'], $mailData['organisationName']);
								$message->to($email, $mailData['organisationName'])->subject(config('global.SITE_ADDRESS_NAME').' '.$mailData['emailHeaderSubject']);
							});
						}
					}
					else if($request->type=='E-mail' && $request->sendto == 'other')
					{
						//echo "E-mail Other"; exit;
						$email=$request->email;
						$name='';
							
						$mailData = array(
							'organisationName'           => 'Custom Black Index',
							'organisationEmail'          => 'debnidhi.kuila@gmail.com',
							'title'                      => 'Notification',
							'userName'                   => '',
							'emailHeaderSubject'         => $request->title,   	
							'emailDescription'           => $request->description  	
						);
						Mail::send('emails.user-notification', $mailData, function ($message) 
						use ($mailData,$email) {
							$message->from($mailData['organisationEmail'], $mailData['organisationName']);
							$message->to($email, $mailData['organisationName'])->subject(config('global.SITE_ADDRESS_NAME').' '.$mailData['emailHeaderSubject']);
						});
					}
					else
					{
						//echo "E-mail All"; exit;
						$emails = User::where('id','!=','1')->get();
						for($i=0; $i<COUNT($emails); $i++)
						{
							//echo $emails[$i]['email'];
							$email=$emails[$i]['email'];
							$name=$emails[$i]['first_name'].' '.$emails[$i]['last_name'];
							
							$mailData = array(
								'organisationName'           => 'Custom Black Index',
								'organisationEmail'          => 'debnidhi.kuila@gmail.com',
								'title'                      => 'Notification',
								'userName'                   => $name,
								'emailHeaderSubject'         => $request->title,   	
								'emailDescription'           => $request->description  	
							);
							Mail::send('emails.user-notification', $mailData, function ($message) 
							use ($mailData,$email) {
								$message->from($mailData['organisationEmail'], $mailData['organisationName']);
								$message->to($email, $mailData['organisationName'])->subject(config('global.SITE_ADDRESS_NAME').' '.$mailData['emailHeaderSubject']);
							});
						}
					}
				}
			
				return \Redirect::Route($this->listUrl)->with('success', 'Notification sent Successfully');
			}	
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }       
    }
	
	
	public function sendNotification(Request $request)
	{
		$friendToken = [];
		$usernames = $request->all()['friend_usernames'];
		$dialog_id = $request->all()['dialog_id'];
		foreach ($usernames as $username) {
			$friendToken[] = DB::table('users')->where('user_name', $username)
				->get()->pluck('device_token')[0];
		}

		$url = 'https://fcm.googleapis.com/fcm/send';
		foreach ($friendToken as $tok) {
			$fields = array(
				'to' => $tok,
				'data' => $message = array(
					"message" => $request->all()['message'],
					"dialog_id" => $dialog_id
				)
			);
			$headers = array(
				'Authorization: key=*mykey*',
				'Content-type: Application/json'
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			curl_exec($ch);
			curl_close($ch);
		}

		$res = ['error' => null, 'result' => "friends invited"];

		return $res;
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
             $model = Notification::find($id);
             $model->delete();
            //return \Redirect::Route($this->listUrl)->with('success', trans('admin_common.record_deleted'));
			return \Redirect::Route($this->listUrl)->with('success', 'Record deleted Successfully');
		 }catch(Exception $e){
             throw new \App\Exceptions\AdminException($e->getMessage());
         }
    }
}