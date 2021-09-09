<?php
namespace App\Helpers;

use Request;
use DB;
use App\Setting;
use App\TargetSubmission;
use App\User;
use App\Moderator;
use App\Target;
use App\TargetPair;
use App\ClassWall;
use Hashids\Hashids;

class CommonHelper
{

    /*
        * Function Name :  getCancelbuttonUrl
        * Purpose       :  This function return the redirect url on clicking cancel button of add/edit page
        * Author        :  KB
        * Created Date  :             
        * Input Params  :  string $routePrefix, string $fromPage, array $extraParams
        * Return Value  :  string $url
   */

    public static function getCancelbuttonUrl($routePrefix,$fromPage,$extraParams=array()){

        if(trim($routePrefix)!='' && trim($fromPage)!=''){
            $url = \Route($routePrefix.'.'.$fromPage);
        }elseif(trim($fromPage)!=''){
            $url = \Route($fromPage);
        }else{
            if(count($extraParams))
                $url = \Route($routePrefix.'.list',$extraParams);
            else
                $url = \Route($routePrefix.'.list');
        }
        return $url;
    }  

    /*
        * Function Name :  getSiteSettingsData
        * Purpose       :  This function returns the common site settings data
        * Author        :  KB
        * Created Date  :  
        * Input Params  :  NA
        * Return Value  :  array

   */
    public static function getSiteSettingsData() {
        $siteSettingData = Setting::first();
        return $siteSettingData;
    }      

    /*
        * Function Name :  encrypt
        * Purpose       :  This function is use for encrypt a string.
        * Author        :  KB
        * Created Date  :             
        * Input Params  :  string $value
        * Return Value  :  string
   */

    public static function encrypt($value)
    {
        $cipher = 'AES-128-ECB'; 
        $key = \Config::get('app.key');
        return openssl_encrypt($value, $cipher, $key);
    }

    /*
        * Function Name :  decrypt
        * Purpose       :  This function is use for decrypt the encrypted string.
        * Author        :  KB
        * Created Date  :             
        * Input Params  :  string $value
        * Return Value  :  string
   */

    public static function decrypt($value)
    {
        $cipher = 'AES-128-ECB'; 
        $key = \Config::get('app.key');
        return openssl_decrypt($value, $cipher, $key);
    }

    /*
        * Function Name :  partialEmailidDisplay
        * Purpose       :  This function is use for hiding some characters of en email id.
        * Author        :  KB
        * Created Date  :             
        * Input Params  :  string $value
        * Return Value  :  string
   */

    public static function partialEmailidDisplay($email){
        $rightPartPos = strpos($email,'@');
        $leftPart = substr($email, 0, $rightPartPos);
        $displayChars = (strlen($leftPart)/2);
        if($displayChars<1){
            $displayChars = 1;
        }
        return substr($leftPart, 0, $displayChars) . '*******' . substr($email, $rightPartPos);
    }

    public static function encryptId($value)
    {
        // $hashids = new Hashids(\Config::get('app.key'));
        // return $hashids->encode($value);     
        $cipher = 'AES-128-ECB'; 
        $key = \Config::get('app.key');
        return base64_encode(openssl_encrypt($value, $cipher, $key));          
    }

    public static function decryptId($value)
    {
        // $hashids = new Hashids(\Config::get('app.key'));
        // return (count($decptid = $hashids->decode($value))? $decptid[0]: '');    
        $cipher = 'AES-128-ECB'; 
        $key = \Config::get('app.key');
        return openssl_decrypt(base64_decode($value), $cipher, $key);           
    }

    public static function getCmsContentBySlug($dataArr, $slug){
        $matchedItem = [];
        foreach($dataArr as $key => $content){
            if($content['slug']==$slug){
                $matchedItem = $dataArr[$key];
                break;
            }
        }
        return $matchedItem;

    }

    public static function getStudentPlan($target_id,$student_id){
        $data = TargetSubmission::where('target_id',$target_id)->where('student_id',$student_id)->first();
        //dd($data);
        return $data;
    }

    public static function getStudentInfo($student_id){
        $data = User::where('id',$student_id)->first();
        //dd($data);
        return $data;
    }

    public static function isStudentOnClassWall($student_id,$class_id){
        $data = ClassWall::where('student_id',$student_id)->where('classes_id',$class_id)->first();
        //dd($data);
        return $data;
    }

    public static function getUserInfo($user_id){
        $data = User::where('id',$user_id)->first();
        //dd($data);
        return $data;
    }

    public static function getTargetInfo($target_id){
        $data = Target::where('id',$target_id)->first();
        //dd($data);
        return $data;
    }

    public static function getTargetPairInfo($target_id,$student_id){
        
        $pairObj = new TargetPair();
       /* $dataPair = $pairObj->where(function($q) use ($student_id, $target_id){
            $q->where('target_id', $target_id)->where('student_one_id', $student_id)->orwhere('student_two_id', $student_id);
        })->first();*/
        $dataPair =$pairObj->where('target_id', $target_id)->where(function($q) use ($student_id){
            $q->where('student_one_id', $student_id)->orwhere('student_two_id', $student_id);
            })->first();

        // DB::enableQueryLog();
        // $queries =DB::getQueryLog();
        // if($k>0)
        
        //     var_dump($queries);

        $partner_id='';
        //getting partner id
        if($dataPair->student_one_id==$student_id) $partner_id=$dataPair->student_two_id;
        if($dataPair->student_two_id==$student_id) $partner_id=$dataPair->student_one_id;
        $data['partner_id']=$partner_id;
        $data['pair_info']=$dataPair;
        $data['student_info']= User::where('id',$partner_id)->first();
        
        // echo '<br>';
        // echo 'pairid='.$data['pair_info']->id.' targetid='.$target_id.'studentid='.$student_id;
        //dd($data);
        return $data;
    }
	
	
	public static function checkPermission($user_id, $name)
	{
		//echo "====>".$user_id; exit;
		$modInfo= Moderator::where('user_id',$user_id)->where('management_name',$name)->first();
		//echo "<pre>"; print_r($modInfo); exit;
		if($user_id==1 || $modInfo)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
}
