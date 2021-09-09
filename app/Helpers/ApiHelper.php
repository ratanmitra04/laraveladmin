<?php
namespace App\Helpers;
use \Helper;
use Request;

class ApiHelper
{

    /*
        * Function Name : getWithResource
        * Purpose       :  
        * Input Params  :  void
        * Return Value  :  Array 
   */

    public static function getWithResource()
    {
        return [];
        // return [
        //     'errors' => [
        //         'status' => 0,
        //         'message' => '',
        //     ]
        // ];
    }

    /*
        * Function name : showValidationError
        * Purpose : formatted validation error
        * Author  :
        * Created Date : 25-03-2019
        * Modified date :
        * Params : 
        * Return : array
    */    

    static function showValidationError($errorsRequired){
        $resp_required  = [];
        foreach ($errorsRequired->all() as $key => $value) {
            array_push($resp_required,$value);
        }
        return ['data' => [], 'status' => 422, 'message' => $resp_required];
    }
    
    /*****************************************************/
    # ApiHelper
    # Function name : generateResponseBody
    # Author        :
    # Created Date  : 20-05-2019
    # Purpose       : To generate api response body
    # Params        : $code, $data, $success = true, $errorCode = null
    /*****************************************************/
    public static function generateResponseBody($code, $data, $success = true, $errorCode = null)
    {
        $result         = [];
        $collectedData  = [];
        $finalCode      = $code;

        $functionName   = null;
        
        if (strpos($code, '#') !== false) {
            $explodedCode   = explode('#',$code);
            $finalCode      = $explodedCode[0];
            $functionName   = $explodedCode[1];
        }

        $collectedData['code'] = $finalCode;
        if ($success) {
            $collectedData['status'] = '1';     //for success
        } else {
            $collectedData['status'] = '0';     //for error
            if ($errorCode) {
                $collectedData['error_code'] = $errorCode;
            }
        }
        
        if (gettype($data) === 'string') {
            $collectedData['message'] = $data;
        } else if(gettype($data) === 'array' && array_key_exists('errors',$data)){
            $collectedData['message'] = implode(",",$data['errors']);
        }else {
            $collectedData['message'] = "";
            $collectedData['details'] = $data;
        }

        if ($functionName != null) {
            $result[$functionName] = $collectedData;
        } else {
            $result = $collectedData;
        }       

        return $result;
    }

    /*****************************************************/
    # ApiHelper
    # Function name : generateResponseBodyForLoginRegister
    # Author        :
    # Created Date  : 19-09-2019
    # Purpose       : To generate api response body
    # Params        : $code, $data, $success = true, $errorCode = null
    /*****************************************************/
    public static function generateResponseBodyForLoginRegister($code, $data, $success = true, $errorCode = null,$encryptedUserData="")
    {
        $result         = [];
        $collectedData  = [];
        $finalCode      = $code;

        $functionName   = null;
        
        if (strpos($code, '#') !== false) {
            $explodedCode   = explode('#',$code);
            $finalCode      = $explodedCode[0];
            $functionName   = $explodedCode[1];
        }

        $collectedData['code'] = $finalCode;
        if ($success === true) {
            $collectedData['status'] = '1';     //for success
        }else if($success === 2){
            $collectedData['status'] = '2';
        } else {
            $collectedData['status'] = '0';     //for error
            if ($errorCode) {
                $collectedData['error_code'] = $errorCode;
            }
        }
        
        if (gettype($data) === 'string') {
            $collectedData['message'] = $data;
        } else if(gettype($data) === 'array' && array_key_exists('errors',$data)){
            $collectedData['message'] = implode(",",$data['errors']);
        }else {
            $collectedData['message'] = "";
            $collectedData['details'] = $data;
        }
        $collectedData['encryptedUserData'] = $encryptedUserData;
        if ($functionName != null) {
            $result[$functionName] = $collectedData;
        } else {
            $result = $collectedData;
        }       

        return $result;
    }
    
    /*****************************************************/
    # ApiHelper
    # Function name : getUserFromHeader
    # Author :
    # Created Date : 20-05-2019
    # Purpose :  to get header user
    # Params : $request
    /*****************************************************/
    public static function getUserFromHeader($request)
    {   
        $headers = $request->header();
        $token = $headers['x-access-token'][0];
        $userData = \App\User::where('auth_token', $token)->first();
        return $userData;
    }


}
