<?php
/***********************************************/
# Company Name    :                             
# Author          : KB                            
# Created Date    :                                
# Controller Name : CmsContentController               
# Purpose         : CmsContent Management             
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
use Exception;
use App\Cms;
use App\CmsContent;

class CmsContentController extends Controller
{
    use GeneralMethods;
    public $controllerName  = 'CmsContent';
    public $management;
    public $modelName       = 'CmsContent';
    public $breadcrumb;
    public $routePrefix     = 'cmscontents';
    public $listUrl         = 'cmscontents.list';
    public $viewFolderPath  = 'admin/cmscontents';

    /*
        * Function Name :  __construct
        * Purpose       : It sets some public variables for being accessed throughout this
        *                   controller and its related view pages
        * Author        : KB  
        * Created Date  : 
        * Modified date :          
        * Input Params  : Void
        * Return Value  : Mixed
   */
    public function __construct()
    {
        parent::__construct();
        $this->management      = 'Content';
        // Begin: Assign breadcrumb for view pages
        $this->assignBreadcrumb();
        // End: Assign breadcrumb for view pages
        // Variables assign for view page
        $this->assignShareVariables();
    }

    /**
        * Function Name :  index
        * Purpose       :  This function is for the listing and searching
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
            $model = new CmsContent;
            $pageid = $request->pageid;
            // if($request->keyword !=''){
            //     // search section
            //     $data['keyword'] = $request->keyword;
            //     $model = $model->Where('slug','like','%'.$data['keyword'].'%');  
            // }
            $data['records'] = $model->where('cms_id', $pageid)->paginate(GlobalVars::ADMIN_RECORDS_PER_PAGE);

            return view($this->viewFolderPath.'.list', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }


    /**
        * Function Name :  edit
        * Purpose       :  This function renders the edit form
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  intiger $id
        * Return Value  :  loads Order edit page
    */

    public function edit(Request $request, $id)
    {
        try{
            $data['id'] = $id;
            $data['page_id'] = $request->pageid;
            $data['record'] = CmsContent::find($id);
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
        * Purpose       :  This function use for update records.
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
                'content'   => 'required'
            ]
        );
        try{ 
            $model = CmsContent::find($id);
            if(!$model){
                throw new Exception("No result was found for id: $id");
            }          
            $page_id                    = $request->pageid;   
            $model->content             = $request->content;
            $model->save();
            return \Redirect::Route($this->listUrl,['pageid'=>$page_id])->with('success', 'Record updated Successfully');
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }
 

}
