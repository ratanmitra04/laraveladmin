<?php
/***********************************************/
# Company Name    :                             
# Author          : KB                            
# Created Date    :                               
# Controller Name : SettingController               
# Purpose         : Setting Management             
/***********************************************/

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\Setting;
use DB;
use Mail;
use App\Helpers\CommonHelper;
use GlobalVars;
use App\Traits\GeneralMethods;
use Exception;

class SettingController extends Controller
{
    use GeneralMethods;
    public $controllerName  = 'Setting';
    public $management;
    public $modelName       = 'Setting';
    public $breadcrumb;
    public $routePrefix     = 'settings';
    public $viewFolderPath  = 'admin/settings';

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
        $this->management      = 'Setting';
        // Variables assign for view page
        $this->assignShareVariables();
    }

    /**
        * Function Name :  edit
        * Purpose       :  This function renders the Setting edit form
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  intiger $id
        * Return Value  :  loads Setting edit page
    */

    public function edit()
    {
        try{
            $id = 1;
            $data['id'] = $id;
            $data['record'] = Setting::find($id);
            return view($this->viewFolderPath.'.edit', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }

    }

    /**
        * Function Name :  update
        * Purpose       :  This function use for update records of Setting.
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  Request $request, integer $id
        * Return Value  :  loads listing page on success and load edit page for any error during the operation
    */

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'from_email'      => 'required',
                'contact_email'   => 'required',
                'career_email'   => 'required',
                'site_address'   => 'required',
            ],
            [
                'from_email.required'     => 'Site Sender Email is Required',
            ]
        );
        try{
            $model = Setting::find($id);
            if(!$model){
                throw new Exception("No result was found for id:$id");
            }            
            $model->from_email         = $request->from_email;
            $model->from_email_name    = $request->from_email_name;
            $model->contact_email      = $request->contact_email;

            $model->career_email       = $request->career_email;
            $model->site_address       = $request->site_address;
            $model->site_contact_no    = $request->site_contact_no;
            $model->site_fax_no        = $request->site_fax_no;
            $model->footer_logo_text   = $request->footer_logo_text;

            $model->facebook_url       = $request->facebook_url;
            $model->twitter_url        = $request->twitter_url;
            $model->instagram_url      = $request->instagram_url;
            $model->linkedin_url       = $request->linkedin_url;

            $model->save();
            return \Redirect::back()->with('success', 'Record updated Successfully');
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }  



}
