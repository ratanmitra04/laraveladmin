<?php
/***********************************************/
# Company Name    :                             
# Author          : KB                            
# Created Date    :                                
# Controller Name : CmsController               
# Purpose         : CMS Management             
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

class CmsController extends Controller
{
    use GeneralMethods;
    public $controllerName  = 'Cms';
    public $management;
    public $modelName       = 'Cms';
    public $breadcrumb;
    public $routePrefix     = 'cms';
    public $listUrl         = 'cms.list';
    public $viewFolderPath  = 'admin/cms';

    /*
        * Function Name :  __construct
        * Purpose       : It sets some public variables for being accessed throughout this
        *                   controller and its related view pages
        * Author        : KB  
        * Created Date  : 25/04/2019
        * Modified date :          
        * Input Params  : Void
        * Return Value  : Mixed
   */
    public function __construct()
    {
        parent::__construct();
        $this->management      = 'CMS';
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
        * Created Date  : 25/04/2019
        * Modified date :          
        * Input Params  :  Request $request
        * Return Value  :  return to listing page

    */

    public function index(Request $request)
    {
        try{
            $data['keyword']        = '';
            if($request->keyword !=''){
                // search section
                $data['keyword'] = $request->keyword;
                $data['records'] = Cms::Where('page_name','like','%'.$data['keyword'].'%')->paginate(GlobalVars::ADMIN_RECORDS_PER_PAGE);  
            }else{
                $data['records'] = Cms::paginate(GlobalVars::ADMIN_RECORDS_PER_PAGE);
            }
            return view($this->viewFolderPath.'.list', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }


    /**
        * Function Name :  edit
        * Purpose       :  This function renders the edit form
        * Author        :
        * Created Date  : 25/04/2019
        * Modified date :          
        * Input Params  :  intiger $id
        * Return Value  :  loads Order edit page
    */

    public function edit($id)
    {
        try{
            $data['id'] = $id;
            $data['record'] = Cms::find($id);
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
        * Created Date  : 25/04/2019
        * Modified date :          
        * Input Params  :  Request $request, intiger $id
        * Return Value  :  loads listing page on success and load edit page for any error during the operation
    */

    public function update(Request $request, $id)
    {
        // $this->validate(
        //     $request,
        //     [
        //         'content'   => 'required'
        //     ]
        // );
        try{ 
            $model = Cms::find($id);
            if(!$model){
                throw new Exception("No result was found for id: $id");
            }             
            // $model->content             = $request->content;
            $model->meta_title          = $request->meta_title;
            $model->meta_description    = $request->meta_description;
            $model->meta_keywords       = $request->meta_keywords;
            $model->save();
            return \Redirect::Route($this->listUrl)->with('success', 'Record updated Successfully');
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }
	
	public function view($id)
    {
        try{ 
            $data['id'] = $id;    
			$data['record'] = Cms::find($id);
            if(!$data['record']){
                throw new Exception("No result was found for id: $id");
            }	
            return view($this->viewFolderPath.'.view', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }
 

}
