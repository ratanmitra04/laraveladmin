<?php
/***********************************************/
# Company Name    :                             
                        
# Created Date    :                                
# Controller Name : BusinessController               
# Purpose         : Business Management             
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
use App\Business;
use App\BusinessImage;
use App\Category;
use App\SubCategory;
use App\City;
use App\Location;
use App\Tag;
use App\User;
use Auth;

class BusinessController extends Controller
{
    
    use GeneralMethods;
    public $controllerName  = 'Business';
    public $management;
    public $modelName       = '';
    public $breadcrumb;
    public $routePrefix     = 'business';
    public $listUrl         = 'business.list';
    public $viewFolderPath  = 'admin/business';
    
    public function __construct()
    {
        parent::__construct();
        $this->management      = 'Business';
        // Begin: Assign breadcrumb for view pages
        $this->assignBreadcrumb();
        // End: Assign breadcrumb for view pages
        // Variables assign for view page
        $this->assignShareVariables();
        
    }
	
	public function export()
	{
		$headers = [
				'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
			,   'Content-type'        => 'text/csv'
			,   'Content-Disposition' => 'attachment; filename=business.csv'
			,   'Expires'             => '0'
			,   'Pragma'              => 'public'
		];

		$list = Business::all('business_name','business_email','business_phone','business_category','business_subcategory','business_website','facebook_link','twitter_link','instagram_link','status','description','featured','top_clicked','latitude','longitude','post_code','address','created_at','search_tag','city','approved_by_admin','business_type','show_public')->sortByDesc('created_at')->toArray();
		//echo "<pre>"; print_r($list); echo "</pre>"; exit;
		foreach($list as $key=>$value)
		{
			if(empty($value['city']))
			{
				$list[$key]['city_name']= 'N/A';
			}
			else
			{
				$cityDetails = City::where('id', $value['city'])->first();
				$list[$key]['city_name']= $cityDetails->city_name;
			}
			if(empty($value['business_category']))
			{
				$list[$key]['category_name']= 'N/A';
			}
			else
			{
				$catDetails = Category::where('id', $value['business_category'])->first();
				$list[$key]['category_name']= $catDetails->category_name;
			}	
			if(empty($value['business_subcategory']))
			{
				$list[$key]['subcategory_name']= 'N/A';
			}
			else
			{
				$subcatDetails = SubCategory::where('id', $value['business_subcategory'])->first();
				$list[$key]['subcategory_name']= $subcatDetails->subcategory_name;
			}
			if($value['featured']==0)	
			{
				$list[$key]['featured']= 'Not Featured';
			}
			if($value['featured']==1)	
			{
				$list[$key]['featured']= 'Featured';
			}
			if($value['approved_by_admin']==0)	
			{
				$list[$key]['approved_by_admin']= 'Pending';
			}
			if($value['approved_by_admin']==1)	
			{
				$list[$key]['approved_by_admin']= 'Approved';
			}
			if($value['approved_by_admin']==2)	
			{
				$list[$key]['approved_by_admin']= 'Rejected';
			}
			if($value['business_type']==1)	
			{
				$list[$key]['business_type']= 'Online';
			}
			if($value['business_type']==2)	
			{
				$list[$key]['business_type']= 'On Site';
			}
			if($value['business_type']==3)	
			{
				$list[$key]['business_type']= 'Service';
			}
			if($value['show_public']==0)	
			{
				$list[$key]['show_public']= 'No';
			}
			if($value['show_public']==1)	
			{
				$list[$key]['show_public']= 'Yes';
			}	
		}
		# add headers for each column in the CSV download
		array_unshift($list, ['Name','Email','Phone','Website','Facebook Link','Twitter Link','Instagram Link','Status','Description','Featured','Clicked','Latitude','Longitude','Zip Code','Address','Registered on','Tag','Admin approval','Business type','Address Show Publicly','City','Category','Sub Category']);

	   $callback = function() use ($list)
		{
			$FH = fopen('php://output', 'w');
			foreach ($list as $key=>$row) {
				unset($row['business_subcategory']);
				unset($row['city']);
				unset($row['business_category']);
				//unset($row['city']);
				fputcsv($FH, $row);
			}
			fclose($FH);
		};

		return response()->stream($callback, 200, $headers);
	}
    
    
    public function index(Request $request)
    {        
       try{
            $data['keyword']        = '';
           // $data['userTypes']                  = ['W'=>'Wholesale','R'=>'Retail'];
            $data['user_type']          = $request->user_type;
            $data['records'] = Business::where('id','!=','');

            if($request->keyword !=''){
                // search section
                $data['keyword'] = $request->keyword;
                $keywordArray = explode(' ', $data['keyword']);
                
                if(count($keywordArray) == 2){ // If keyword have two words
                     $data['records'] = $data['records']->where(function($q) use ($data, $keywordArray){
                        $q->where('business_name','like','%'.$keywordArray[0].'%')
                     ->where('business_name','like','%'.$keywordArray[1].'%');
                     });                     
                }else{

                     $data['records'] = $data['records']->where(function($q) use ($data){
                        $q->Where('business_name','like','%'.$data['keyword'].'%')
                         ->orWhere('business_email','like','%'.$data['keyword'].'%');
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
			$data['record'] = Business::find($id);
			$data['businessimages'] = BusinessImage::where('business_id', $id)->get();
			
			$data['cityname'] = City::where('id', $data['record']['city'])->first();
			//$data['locationname'] = Location::where('id', $data['record']['area'])->first();
			
			$data['catname'] = Category::where('id', $data['record']['business_category'])->first();
			$data['subcatname'] = SubCategory::where('id', $data['record']['business_subcategory'])->first();
			
            if(!$data['record']){
                throw new Exception("No result was found for id: $id");
            }	
            return view($this->viewFolderPath.'.view', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    } 
	
	/*public function getCategoryDropDown(Request $request){
    {
		$categories = Category::where('id','!=',1)->get();
        foreach ($categories as $cat) {
            $categoryList .= '<option value='.$cat->id.'>'.$user->category_name.'</option>';
        }
        return $categoryList;
    }*/

    public function add()
    {
        return view($this->viewFolderPath.'.add');
    }
	
	public function edit($id)
    {
        try{
            $data['id'] = $id;
            $data['record'] = Business::find($id);
			$data['city'] = City::where('id','!=','')->orderby('city_name', 'ASC')->get();
			$data['area'] = location::where('city_id', $data['record']['city'])->get();
			$data['businessimages'] = BusinessImage::where('business_id', $id)->get();
			
			$data['tags'] = Tag::where('id','!=',NULL)->orderby('tag_name', 'ASC')->groupBy('tag_name')->get();
			
			$data['category'] = Category::where('id','!=','')->orderby('category_name', 'ASC')->get();
			$data['subcategory'] = SubCategory::where('category_id', $data['record']['business_category'])->orderby('subcategory_name', 'ASC')->get();
            if(!$data['record']){
                throw new Exception("No result was found for id: $id");
            }
            return view($this->viewFolderPath.'.edit', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }      
    }
	
	public function getAreas($id)
	{
		// Fetch Location by City id
        $dataArea['record'] = Location::orderby("location_name","asc")
        			->select('id','location_name')
        			->where('city_id',$id)
        			->get();
  
        return response()->json($dataArea);
	}
	
	public function getSubCategory($id)
	{
		// Fetch Sub Category by Category id
        $dataArea['record'] = SubCategory::orderby("subcategory_name","asc")
        			->select('id','subcategory_name')
        			->where('category_id',$id)
        			->get();
  
        return response()->json($dataArea);
	}
	
	public function imageUpload(Request $request, $business_id)
	{
		$image_list = BusinessImage::where('business_id',$business_id)->get();
		$number= COUNT($image_list);
		if($number<4)
		{
			$model                      = new BusinessImage;
			$model->business_id       	= $business_id;
			$image      				= $request->fileinfo;
			/*echo "<pre>";
			print_r($image );exit;*/
			
		   $image->getClientOriginalExtension();
		   $destinationPath = 'public/uploads/business_images/'; // upload path
		   $profilefile = 'image_'.time() . "." . $image->getClientOriginalExtension();
		   $image->move($destinationPath, $profilefile);
		   
		   $model->image      = 'image_'.time() . "." . $image->getClientOriginalExtension();
		   $model->save();exit;
		}
		else
		{
			echo '0';
			exit;
		}	
	}
	
	public function imageDelete($remove_id,$businessid)
	{
		$image_list = BusinessImage::where('business_id',$businessid)->get();
		$number= COUNT($image_list);
		if($number==1)
		{
			echo '0';
			exit;
		}
		else
		{
			$model 		= BusinessImage::find($remove_id);
			$oldImgName = $model->image;
			
			$img_directory='business_images';
			$destinationPath = 'public/uploads/'.$img_directory.'/'; // upload path
			if(file_exists($destinationPath.$oldImgName))
			{
				@unlink($destinationPath.$oldImgName);
			}
			
			$model->delete();
			exit;
		}
	}
	
	public function delete($id)
    {
         try{
             $model = Business::find($id);
             $model->delete();
			 
			 $actionBy= Auth::guard('admin')->user()->id;
			 $this->createLog($actionBy,2,3,$id,$model->business_name);
			 
            //return \Redirect::Route($this->listUrl)->with('success', trans('admin_common.record_deleted'));
			return \Redirect::Route($this->listUrl)->with('success', 'Record deleted Successfully');
		 }catch(Exception $e){
             throw new \App\Exceptions\AdminException($e->getMessage());
         }
    }
	
	public function businessApproved(Request $request, $approved_id)
    {
        
		 $model = Business::find($approved_id);
		 $model->approved_by_admin   = '1';
		 $model->save();
		 
		 /* 20-01-2021 */
		 $userID=$model->user_id;
		 $getUsersDetails = User::find($userID);
		 $userEmail=$getUsersDetails->email;
		 $first_name=$getUsersDetails->first_name;
		 $last_name=$getUsersDetails->last_name;
		 if($userEmail)
		 {
			$mailData = array(
				'organisationName'                  => 'Custom Black Index',
				'organisationEmail'                 => 'debnidhi.kuila@gmail.com',
				'title'                             => 'Business Approved',
				'userName'                          => $first_name.' '.$last_name,
				'emailHeaderSubject'                => 'Approved Business',   	
				'businessName'                		=> $model->business_name,	
				'userEmail'                			=> $userEmail	
			);
			Mail::send('emails.user-businessapproved', $mailData, function ($message) 
				use ($mailData) {
					$message->from($mailData['organisationEmail'], $mailData['organisationName']);
					$message->to($mailData['userEmail'], $mailData['organisationName'])->subject(config('global.SITE_ADDRESS_NAME').' '.$mailData['emailHeaderSubject']);
			});	
		 }
		  /* 20-01-2021 */
		 
		 $actionBy= Auth::guard('admin')->user()->id;
		 $this->createLog($actionBy,2,4,$approved_id,$model->business_name);
		 
		 exit;
    }
	
	public function businessRejected(Request $request, $rejected_id)
    {
        
		 $model = Business::find($rejected_id);
		 $model->approved_by_admin   = '2';
		 $model->save();
		 
		 /* 20-01-2021 */
		 $userID=$model->user_id;
		 $getUsersDetails = User::find($userID);
		 $userEmail=$getUsersDetails->email;
		 $first_name=$getUsersDetails->first_name;
		 $last_name=$getUsersDetails->last_name;
		 if($userEmail)
		 {
			$mailData = array(
				'organisationName'                  => 'Custom Black Index',
				'organisationEmail'                 => 'debnidhi.kuila@gmail.com',
				'title'                             => 'Business Rejected',
				'userName'                          => $first_name.' '.$last_name,
				'emailHeaderSubject'                => 'Reject Business',   	
				'businessName'                		=> $model->business_name,	
				'userEmail'                			=> $userEmail	
			);
			Mail::send('emails.user-businessrejected', $mailData, function ($message) 
				use ($mailData) {
					$message->from($mailData['organisationEmail'], $mailData['organisationName']);
					$message->to($mailData['userEmail'], $mailData['organisationName'])->subject(config('global.SITE_ADDRESS_NAME').' '.$mailData['emailHeaderSubject']);
			});	
		 }
		  /* 20-01-2021 */
		 
		 $actionBy= Auth::guard('admin')->user()->id;
		 $this->createLog($actionBy,2,5,$rejected_id,$model->business_name);
		 
		 exit;
    }

	
	public function addTags($searchTag)  
	{
		//dd($searchTag);exit;
		//$tags=json_decode($searchTag);
		$tags=$searchTag;
        $insert_tags=[];
		if (is_array($tags) && count($tags)>0) {
			for ($i=0; $i < count($tags); $i++) { 
			$tag_find=Tag::where('tag_name','=',$tags[$i])->get();
			if(count($tag_find)=="0"){
			    $insert_tags=Tag::insert(['tag_name'=>$tags[$i]]);
			}
		}

			if ($insert_tags) {
				# code...
				$resultarray=array(
						  'flag'=>"true",
						  'status_code'=>"200",             
						  'message'=>count($tags)." "."tags inserted"
						);
			            //echo json_encode($resultarray);

			}else{
				$resultarray=array(
						  'flag'=>"true",
						  'status_code'=>"101",             
						  'message'=>"tags not inserted."
						);
			            //echo json_encode($resultarray);

			}
		}else{
			$resultarray=array(
						  'flag'=>"false",
						  'status_code'=>"100",             
						  'message'=>"parameter is not an array or empty"
						);
			            //echo json_encode($resultarray);

		}

	}
	
	/**
        * Function Name :  getLatLong
        * Purpose       :  Get Latitude Longitude by Address.
        * Author        :
        * Created Date  :  2020-12-18
        * Input Params  :  address
        * Return Value  :  latitude,longitude,status
	*/
	public function getLatLong($address)
	{
		$key='AIzaSyAhW3b61dIpR79QW7ozhu3OBTKbP6lKP-8';
		$address = $address;
		//$url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($address);
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=".$key;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
		$responseJson = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($responseJson);
		$result=[];
		if ($response->status == 'OK') 
		{
			$latitude = $response->results[0]->geometry->location->lat;
			$longitude = $response->results[0]->geometry->location->lng;
			
			$result['latitude']=$latitude;
			$result['longitude']=$longitude;
			$result['status']=$response->status;
		}
		else 
		{
			$result['latitude']='';
			$result['longitude']='';
			$result['status']=$response->status;
		}  
		return $result;
	}
	
		
	public function update(Request $request, $id)
    {
		
		$this->validate(
            $request,
            [
                'business_name'    		=> 'required',
				'business_category'     => 'required',
				/*'business_subcategory'  => 'required',*/
				/*'business_phone'        => 'required|numeric|digits_between:10,15',*/
				'business_phone'        => 'required|numeric',
                'business_email'        => 'required|email',
                'city'     				=> 'required',
                'business_type'     	=> 'required',
                
                'address'     			=> 'required',
                /*'post_code'     		=> 'required|numeric|digits_between:7,10',*/
                'post_code'     		=> 'required',
            ],
            [
                'business_name.required'     	=> 'Business Name is Required',
				'business_category.required'    => 'Business Category is Required',
                /*'business_subcategory.required' => 'Business Sub Category is Required',*/
				'business_phone.required'       => 'Business Phone is Required',
                'business_email.required'       => 'Business Email is Required',
                'city.required'    				=> 'Business City is Required',
                'business_type.required'    	=> 'Business Type is Required',
                
                'address.required'    			=> 'Business Address is Required',
                'post_code.required'    		=> 'Business Post Code is Required',
            ]
        );
        try{
			if($request->business_category!='others' && $request->business_subcategory=='')
			{
				return \Redirect::back()->with('error', 'Business Sub Category is Required');
			}
			elseif($request->business_category=='others' && $request->add_cat=='')
			{
				return \Redirect::back()->with('error', 'Business Category is Required');
			}
			elseif($request->business_category=='others' && $request->add_sub_cat=='')
			{
				return \Redirect::back()->with('error', 'Business Sub Category is Required');
			}
			else
			{
				$model = Business::find($id);
				
				//others
				$last_insert_id='';
				$last_insert_id1='';
				if($request->business_category == 'others')
				{
					$category = new Category;
					$category->category_name = strtolower($request->add_cat);
					$category->save(); // Save category
					$last_insert_id=$category->id; // get last inserted id
					
					$subcategory = new SubCategory;
					$subcategory->category_id = $last_insert_id;
					$subcategory->subcategory_name = strtolower($request->add_sub_cat);
					$subcategory->save(); // Save sub category
					$last_insert_id1=$subcategory->id; // get last inserted id
				}
				self::addTags($request->search_tag);
				
				$model->business_name       = $request->business_name;
				
				if($last_insert_id){
					$model->business_category   = $last_insert_id;
				}else{
					$model->business_category   = $request->business_category;
				}
				
				if($last_insert_id1){
					$model->business_subcategory    = $last_insert_id1;
				}else{
					$model->business_subcategory    = $request->business_subcategory;
				}
				//$model->business_subcategory    = $request->business_subcategory;
				$model->business_phone      = $request->business_phone;
				$model->business_email      = $request->business_email;
				
				$model->business_website    = $request->business_website;
				$model->facebook_link    	= $request->facebook_link;
				$model->twitter_link    	= $request->twitter_link;
				$model->instagram_link    	= $request->instagram_link;
				$model->city    			= $request->city;
				$model->business_type    	= $request->business_type;
				$model->radius    			= $request->radius;
				$model->address             = $request->address;
				$model->show_public         = ($request->show_public)? '1': '0';
				$model->post_code           = $request->post_code;
				/*$model->area    			= $request->area;*/
				$model->search_tag    		= $request->search_tag;
				
				$model->description    		= $request->description;
				
				/*$model->origin    		= $request->origin;*/
				$model->featured    		= ($request->featured)? '1': '0';
				/*$model->added_by    		= $request->added_by;*/
				$model->status              = $request->status;
				//dd($model);exit;
				
				/*if ($files = $request->file('user_logo')) 
				{
					$destinationPath = 'uploads/business_images/';
					$oldImgName=$model->user_logo;
					if(file_exists($destinationPath.$model->user_logo)){
						@unlink($destinationPath.$oldImgName);
					}
					
				   $files->getClientOriginalExtension();
				   $profilefile = 'image_'.time() . "." . $files->getClientOriginalExtension();
				   $files->move($destinationPath, $profilefile);
				   $insert['file'] = "$profilefile";
				   
				   $model->user_logo      = 'image_'.time() . "." . $files->getClientOriginalExtension();
				}*/
				/***18-12-2020***/
					$addr=$request->address;
					$city=$request->city;
					$post=$request->post_code;
					$fullAddress=$addr.','.$post;
			
					$location=$this->getLatLong($fullAddress);
					$model->latitude=$location['latitude'];
					$model->longitude=$location['longitude'];
				/***18-12-2020***/
				
				$model->save();
				
				$actionBy= Auth::guard('admin')->user()->id;
				$this->createLog($actionBy,2,2,$id,$request->business_name);
				
				return \Redirect::Route($this->listUrl)->with('success', 'Record updated Successfully');
			}	
        }
		catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }        
    }

}