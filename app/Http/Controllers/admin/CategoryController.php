<?php
/***********************************************/
# Company Name    :                             
                        
# Created Date    :                                
# Controller Name : CategoryController               
# Purpose         : Category Management             
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
use App\Category;
use App\SubCategory;
use App\Business;
use Auth;

class CategoryController extends Controller
{
    
    use GeneralMethods;
    public $controllerName  = 'Category';
    public $management;
    public $modelName       = '';
    public $breadcrumb;
    public $routePrefix     = 'category';
    public $listUrl         = 'category.list';
    public $viewFolderPath  = 'admin/category';
    
    public function __construct()
    {
        parent::__construct();
        $this->management      = 'Category';
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
            $data['records'] = Category::where('id','!=','');

            if($request->keyword !=''){
                // search section
                $data['keyword'] = $request->keyword;
                $keywordArray = explode(' ', $data['keyword']);
                
                if(count($keywordArray) == 2){ // If keyword have two words
                     $data['records'] = $data['records']->where(function($q) use ($data, $keywordArray){
                        $q->where('category_name','like','%'.$keywordArray[0].'%')
                     ->where('category_name','like','%'.$keywordArray[1].'%');
                     });                     
                }else{

                     $data['records'] = $data['records']->where(function($q) use ($data){
                        $q->Where('category_name','like','%'.$data['keyword'].'%');
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
			$data['record'] = Category::find($id);
			//echo "<pre>"; print_r($data);
			$data['subcategory'] = SubCategory::where('category_id', $data['record']['id'])->get();
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

	public function store(Request $request)
    {
	   //dd($request);
	   $this->validate(
            $request,
            [
                'cat_name'            => 'required',
                /*'last_name'             => 'required',*/
            ],
            [
                'cat_name.required'     => 'Category Name is Required',
                /*'last_name.required'      => 'Last Name is Required',*/
            ]
        );
        try{
            $category                      = new Category;
            $category->category_name       = strtolower($request->cat_name);
			
			/*12-01-2021*/
			if ($files = $request->file('cat_icon')) 
			{
			   $ext=$files->getClientOriginalExtension();
			   $imgdetails = getimagesize($files);
			   $width = $imgdetails[0];
			   $height = $imgdetails[1];
			   if($width>200 || $height>200)	
			   {
				   return \Redirect::back()->with('error', 'Size should be between 200x200');
			   }
			   else if($ext=='png' || $ext=='PNG' || $ext=='jpg' || $ext=='JPG' || $ext=='jpeg' || $ext=='JPEG')
			   {
				   $destinationPath = 'public/uploads/category_images/'; // upload path
				   $profilefile = 'image_'.time() . "." . $files->getClientOriginalExtension();
				   $files->move($destinationPath, $profilefile);
				   $category->cat_icon  = 'image_'.time() . "." . $files->getClientOriginalExtension();
			   }
			   else
			   {
				   return \Redirect::back()->with('error', 'Only .jpg,.jpeg,.png allowed');
			   }
			}
			/*12-01-2021*/
            $category->save();
			
			$actionBy= Auth::guard('admin')->user()->id;
			$last_insert_id=$category->id;
			$this->createLog($actionBy,3,1,$last_insert_id,$request->cat_name);
			
			$last_insert_id=$category->id;
			
			for ($i = 0; $i < count($request->field_name); $i++) {
				$subcat                   	  = new SubCategory;
				$subcat->category_id       	  = $last_insert_id;
				$subcat->subcategory_name     = strtolower($request->field_name[$i]);
			
				$subcat->save();
			}
			
            return \Redirect::Route($this->listUrl)->with('success', 'Record added Successfully');
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }       
    }
	
	public function edit($id)
    {
        try{
            $data['id'] = $id;
            $data['record'] = Category::find($id);
			$data['subcategory'] = SubCategory::where('category_id', $data['record']['id'])->get();
            if(!$data['record']){
                throw new Exception("No result was found for id: $id");
            }
            return view($this->viewFolderPath.'.edit', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }      
    }
	
	
	public function update(Request $request, $id)
    {
        //dd($request);
        //dd($request->field_name);
		$this->validate(
            $request,
            [
                'cat_name'    => 'required',
            ],
            [
                'cat_name.required'     => 'Category Name is Required',
            ]
        );
        try{
            $category = Category::find($id);
            $category->category_name = strtolower($request->cat_name);
			
			if ($files = $request->file('cat_icon')) 
			{
				$destinationPath = 'public/uploads/category_images/'; // upload path
				/*$oldImgName=$category->cat_icon;
				if(file_exists($destinationPath.$category->cat_icon))
				{
					@unlink($destinationPath.$oldImgName);
				}*/
			   $ext=$files->getClientOriginalExtension();
			   $imgdetails = getimagesize($files);
			   $width = $imgdetails[0];
			   $height = $imgdetails[1];
			   if($width>200 || $height>200)	
			   {
				   return \Redirect::back()->with('error', 'Size should be between 200x200');
			   }
			   else if($ext=='png' || $ext=='PNG' || $ext=='jpg' || $ext=='JPG' || $ext=='jpeg' || $ext=='JPEG')
			   {
				   $profilefile = 'image_'.time() . "." . $files->getClientOriginalExtension();
				   $files->move($destinationPath, $profilefile);
				   
				   $category->cat_icon = 'image_'.time() . "." . $files->getClientOriginalExtension();
			   }
			   else
			   {
				   return \Redirect::back()->with('error', 'Only .jpg,.jpeg,.png allowed');
			   }
			}
            $category->save();
			
			$actionBy= Auth::guard('admin')->user()->id;
			$this->createLog($actionBy,3,2,$id,$request->cat_name);
			
			$last_insert_id=$category->id;

			for ($i = 0; $i < count($request->field_name); $i++) 
			{
				if(!empty($request->field_id[$i]))
				{
					$subcat = SubCategory::find($request->field_id[$i]);
					$subcat->subcategory_name     = strtolower($request->field_name[$i]);

					$subcat->save();
				}
				else
				{
					$subcat                   	  = new SubCategory;

					$subcat->category_id       	  = $last_insert_id;
					$subcat->subcategory_name     = strtolower($request->field_name[$i]);
				
					$subcat->save();
				}	
			}
			
            return \Redirect::Route($this->listUrl)->with('success', 'Record updated Successfully');
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }        
    }
	
	public function subcatRemoved(Request $request, $removed_id)
	{
		 $data = Business::where('business_subcategory',$removed_id)->get();
		 if(COUNT($data)>0)
		 {
			 echo 1;
			 exit;
		 }
		 else
		 {
			 $subcat_del = SubCategory::where('id',$removed_id)->delete();
			 echo 0;
			 exit;
		 }	 
		 exit;
	}

	public function delete($id)
    {
         try{
             $isDelete=0;
			 $isAssigned = Business::where('business_category',$id)->first();
			 if(!$isAssigned)
			 {
				 $model = Category::find($id);
				 $subcat_del = SubCategory::where('category_id',$id)->delete();
				 $model->delete();
				 
				 $actionBy= Auth::guard('admin')->user()->id;
				 $this->createLog($actionBy,3,3,$id,$model->category_name);
				 
				 $isDelete = 1;
			 }
			 else
			 {
				 $isDelete = 0;
			 }			
            //return \Redirect::Route($this->listUrl)->with('success', trans('admin_common.record_deleted'));
			return response()->json(['status'=>'200', 'message'=>'', 'is_delete'=>$isDelete]);
         }catch(Exception $e){
			 return response()->json(['status'=>'500', 'message'=>'fail', 'is_delete'=>$isDelete]);
         }
    }
	
	
}