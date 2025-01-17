<?php
/***********************************************/
# Company Name    :                             
                        
# Created Date    :                                
# Controller Name : ReviewController                 
# Purpose         : Review Management             
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
use App\Review;
use App\BusinessReview;
use App\User;
use Auth;

class ReviewController extends Controller
{
    
    use GeneralMethods;
    public $controllerName  = 'Review';
    public $management;
    public $modelName       = '';
    public $breadcrumb;
    public $routePrefix     = 'review';
    public $listUrl         = 'review.list';
    public $viewFolderPath  = 'admin/review';
    
    public function __construct()
    {
        parent::__construct();
        $this->management      = 'Review';
        // Begin: Assign breadcrumb for view pages
        $this->assignBreadcrumb();
        // End: Assign breadcrumb for view pages
        // Variables assign for view page
        $this->assignShareVariables();
        
    }
    
    public function index(Request $request)
    {
        try{
            $data['pagenum']        = isset($request->page)?$request->page:1;
            $data['keyword']        = '';
           // $data['userTypes']                  = ['W'=>'Wholesale','R'=>'Retail'];
            $data['user_type']          = $request->user_type;
            $data['records'] = BusinessReview::where('id','!=','');
		
            if($request->keyword !=''){
                // search section
                $data['keyword'] = $request->keyword;
                $keywordArray = explode(' ', $data['keyword']);
                
                if(count($keywordArray) == 2){ // If keyword have two words
                     $data['records'] = $data['records']->where(function($q) use ($data, $keywordArray){
                        $q->where('first_name','like','%'.$keywordArray[0].'%')
                     ->where('last_name','like','%'.$keywordArray[1].'%');
                     });                     
                }else{

                     $data['records'] = $data['records']->where(function($q) use ($data){
                        $q->Where('first_name','like','%'.$data['keyword'].'%')
                         ->orWhere('last_name','like','%'.$data['keyword'].'%')
                         ->orWhere('business_name','like','%'.$data['keyword'].'%')
                         ->orWhere('status','like','%'.$data['keyword'].'%');
                     });
                }
            }

            if($data['user_type']){
                $data['records'] = $data['records']->where('user_type', $data['user_type']);
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
			$data['record'] = BusinessReview::find($id);
            if(!$data['record']){
                throw new Exception("No result was found for id: $id");
            }	
            return view($this->viewFolderPath.'.view', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    } 

    public function reviewApproved(Request $request, $approved_id)
    {
        
		 $model = BusinessReview::find($approved_id);
		 $model->status   = 1;
		 $model->save();
		 
		 $actionBy= Auth::guard('admin')->user()->id;
		 $this->createLog($actionBy,7,4,$approved_id,$model->business_name);
		 
		 exit;
    }
	
	public function reviewRejected(Request $request, $rejected_id)
    {
        
		 $model = BusinessReview::find($rejected_id);
		 $model->status   = 2;
		 $model->save();
		 
		 $actionBy= Auth::guard('admin')->user()->id;
		 $this->createLog($actionBy,7,5,$rejected_id,$model->business_name);
		 
		 exit;
    }
	
	
	public function delete($id)
    {
         try{
             $model = BusinessReview::find($id);
             $model->delete();
			 
			 $actionBy= Auth::guard('admin')->user()->id;
			 $this->createLog($actionBy,7,3,$id,$model->business_name);
			 
            //return \Redirect::Route($this->listUrl)->with('success', trans('admin_common.record_deleted'));
			return \Redirect::Route($this->listUrl)->with('success', 'Record deleted Successfully');
		 }catch(Exception $e){
             throw new \App\Exceptions\AdminException($e->getMessage());
         }
    }
	
	public function add()
    {
        return view($this->viewFolderPath.'.add');
    }
}