<?php
/***********************************************/
# Company Name    :                             
# Author          : SM                            
# Created Date    : 20-04-2019                               
# Controller Name : CountryController               
# Purpose         : Country Management             
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
use App\Country;

class CountryController extends Controller
{
    use GeneralMethods;
    public $controllerName  = 'Country';
    public $management;
    public $modelName       = 'Country';
    public $breadcrumb;
    public $routePrefix     = 'countries';
    public $listUrl         = 'countries.list';
    public $viewFolderPath  = 'admin/countries';

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
        $this->management      = 'Country';
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
            if($request->keyword !=''){
                // search section
                $data['keyword'] = $request->keyword;
                $data['records'] = Country::Where('name','like','%'.$data['keyword'].'%')->paginate(GlobalVars::ADMIN_RECORDS_PER_PAGE);  
            }else{
                $data['records'] = Country::paginate(GlobalVars::ADMIN_RECORDS_PER_PAGE);
            }
            return view($this->viewFolderPath.'.list', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }

    /**
        * Function Name :  add
        * Purpose       :  This function renders the add form
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
        * Function Name :  Store
        * Purpose       :  This function use for addition.
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  Request $request
        * Return Value  :  loads listing page on success and load add page for any error during the operation

    */

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name'    => 'required',
            ],
            [
                'name.required'    => 'Country Name is Required',
            ]
        );
        try{ 
            $model              = new Country;
            $model->name        = $request->name;
            $model->ccode       = $request->ccode;

            $model->save();
            return \Redirect::Route($this->listUrl)->with('success', 'Record added Successfully');
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
        * Return Value  :  loads Team edit page
    */

    public function edit($id)
    {
        try{
            $data['id'] = $id;
            $data['record'] = Country::find($id);
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
                'name'    => 'required',
            ],
            [
                'name.required'    => 'Name is Required',
            ]
        );
        try{
            $model = Country::find($id);
            if(!$model){
                throw new Exception("No result was found for id: $id");
            }
            $model->name    = $request->name;
            $model->ccode   = $request->ccode;

            $model->save();
            return \Redirect::Route($this->listUrl)->with('success', 'Record updated Successfully');
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }
 
    /**
        * Function Name :  delete
        * Purpose       :  This function use for delete records.
        * Author        :
        * Created Date  : 
        * Modified date :          
        * Input Params  :  intiger $id
        * Return Value  :  loads listing page
    */

    public function delete($id)
    {
        try {
            $model = Country::find($id);
            if(!$model){
                throw new Exception("No result was found for id: $id");
            }             
            $model->delete();
            return \Redirect::Route($this->listUrl)->with('success', 'Record Deleted Successfully');
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }




}
