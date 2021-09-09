<?php
/***********************************************/
# Company Name    :                             
# Author          : KB                            
# Created Date    :                            
# Controller Name : CityController               
# Purpose         : City Management             
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
//use App\User;
use App\City;

class CityController extends Controller
{
    use GeneralMethods;
    public $controllerName  = 'City';
    public $management;
    public $modelName       = 'City';
    public $breadcrumb;
    public $routePrefix     = 'cities';
    public $listUrl         = 'cities.list';
    public $viewFolderPath  = 'admin/cities';

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
        $this->management      = 'City';
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
            $data['records'] = City::where('id','!=','');

            if($request->keyword !=''){
                // search section
                $data['keyword'] = $request->keyword;
                $keywordArray = explode(' ', $data['keyword']);
                
                if(count($keywordArray) == 2){ // If keyword have two words
                     $data['records'] = $data['records']->where(function($q) use ($data, $keywordArray){
                        $q->where('city_name','like','%'.$keywordArray[0].'%')
                     ->where('city_name','like','%'.$keywordArray[1].'%');
                     });                     
                }else{

                     $data['records'] = $data['records']->where(function($q) use ($data){
                        $q->Where('city_name','like','%'.$data['keyword'].'%');
                     });
                }
            }

            if($data['user_type']){
                $data['records'] = $data['records']->where('user_type', $data['user_type']);
            }            

            $data['records'] = $data['records']->orderBy('city_name','ASC')->paginate(GlobalVars::ADMIN_RECORDS_PER_PAGE);

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
			$data['record'] = City::find($id);
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
                'city_name'            => 'required',
               
            ],
            [
                'city_name.required'     => 'City Name is Required',
                
            ]
        );
        try{
            $model                      = new City;
            $model->city_name           = $request->city_name;
			
            $model->save();
			
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
            $data['record'] = City::find($id);
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
                'city_name'    => 'required',
            ],
            [
                'city_name.required'     => 'City Name is Required',
            ]
        );
        try{
            $model = City::find($id);
            $model->city_name          = $request->city_name;
			
            $model->save();
            return \Redirect::Route($this->listUrl)->with('success', 'Record updated Successfully');
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
             $model = City::find($id);
             $model->delete();
            //return \Redirect::Route($this->listUrl)->with('success', trans('admin_common.record_deleted'));
			return \Redirect::Route($this->listUrl)->with('success', 'Record deleted Successfully');
		 }catch(Exception $e){
             throw new \App\Exceptions\AdminException($e->getMessage());
         }
    }

}