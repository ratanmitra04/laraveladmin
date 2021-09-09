<?php

namespace App\Http\Controllers\api;

use \App\Business;
use \App\BusinessFavorite;
use \App\User;
use \App\BusinessReview;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiHelper;
use \Auth;
use \Response;
use \Helper;
use \Validator;
use \Hash;
use Image;
use \App\BusinessImage;
use App\City;
use App\Location;
use App\Category;
use App\SubCategory;
use App\Notification;
use App\NotificationDetail;
use \App\Tags;
use App;
use DB;
use Illuminate\Support\Facades\Storage;


class BusinessController extends Controller
{
    public function imageRemoveFromFolder(request $request)  
	{ 
		$file_data 		= $request->input('image');
		$img_directory 	= $request->input('image_folder');
		
		$Validator = Validator::make($request->all(),[
            'image'             => 'required',
            'image_folder'      => 'required',
            ]
        );
		if($Validator->fails()) 
		{
			return response()->json(['flag'=>'false', 'status' => 401, 'message'=> $Validator->errors()]);
        }
		else
		{
			
			$destinationPath = 'uploads/'.$img_directory.'/'; // upload path
			$oldImgName=$request->input('image');
			$baseurl=url("uploads/".$img_directory);
			if(file_exists($destinationPath.$oldImgName))
			{
				@unlink($destinationPath.$oldImgName);
			}
			$resultarray=array(
				  'flag'=>"true",
				  'status'=>'200',
				  'message'=>"image removed successfully",
				  'image_name'=>$oldImgName,
				  'base_url'=>$baseurl
			); 
		   
			echo json_encode($resultarray);
		}
	}
	
	public function base64ImageUpload(request $request)  
	{ 
		//req parameter= image which is a base 64 encoded image

		$file_data = $request->input('image');
		//$img_directory = $request->input('business_images');

		$img_directory = $request->input('image_folder');
		$business_id = $request->input('business_id');
		
		//generating unique file name;
		
		$file_name = 'image_'.time().'.png';
		@list($type, $file_data) = explode(';', $file_data);
        @list(, $file_data)      = explode(',', $file_data);
        $baseurl=url("uploads/".$img_directory);
			if(!empty($img_directory) && $file_data!="")
			{
				// storing image in storage/app/public Folder
				//\Storage::disk('public_uploads')->put($file_name,base64_decode($file_data));
				Storage::disk($img_directory)->put($file_name, base64_decode($file_data));
				   
				$resultarray=array(
					  'flag'=>"true",
					  'message'=>"image uploaded successfully",
					  'image_name'=>$file_name,
					  'base_url'=>$baseurl
				  );
				  
				/*$businessimage                   	  = new BusinessImage;
				$businessimage->business_id       	  = $request->business_id;
				$businessimage->image        		  = $file_name;
			
				$businessimage->save();*/ // Save  
		   
				echo json_encode($resultarray);    
		  }
		  else
		  {
			  $noti=array(
				  'flag'=>"false",
				 
				  'message'=>"Missing Parameter."
				  );
			 echo json_encode($noti); 
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
	
	
	/**
        * Function Name :  vendorAddBusiness
        * Purpose       :  Add business.
        * Author        :
        * Created Date  :  2020-11-03
        * Input Params  :  user_id,business_name,business_email,business_phone,business_category,address,post_code,business_website,status,description
        * Return Value  :  flag,details
	*/  
	public function vendorAddBusiness(request $request)  
	{  
		$Validator = Validator::make($request->all(),[
            'user_id'             	=> 'required',
            'business_name'         => 'required',
            'business_phone'        => 'required',
            'business_email'        => 'required|email',
            'business_category'     => 'required',
            'city'  				=> 'required',
            
            ]
        );
		if($Validator->fails()) 
		{
			return response()->json(['flag'=>"false", 'status' => 401, 'message'=> $Validator->errors()]);
            // return response()->json(ApiHelper::showValidationError($Validator->errors()));
        }
		else
		{


			self::addTags($request->search_tag);
			//====================NED to work on tag======================

			$business                   	  = new Business;
			$business->user_id       	      = $request->user_id;
			$business->business_name       	  = $request->business_name;
			$business->business_phone         = $request->business_phone;
			$business->business_email         = $request->business_email;
			$business->business_category      = $request->business_category;
			
			
			$business->city    				  = $request->city;
            $business->area    				  = $request->area;
			
			$business->business_subcategory   = $request->business_subcategory;
			$business->business_website		=$request->business_website;
			//$business->status				=$request->status;
			$business->description			=$request->description;
			$business->origin				=$request->origin;
			$business->added_by				=$request->added_by;
			$business->search_tag			=$request->search_tag;
			$business->facebook_link	    =$request->facebook_link;
			$business->twitter_link			=$request->twitter_link;
			$business->instagram_link		=$request->instagram_link;
			$business->address   			= $request->address;
			$business->business_type   		= $request->business_type;
			$business->radius   			= $request->radius;
			$business->post_code   			= $request->post_code;
			$business->show_public   		= $request->show_public;
			$business->featured   		    = $request->featured;

			/***31-12-2020***/
			$location=$this->getLatLong($request->address);
			$business->latitude=$location['latitude'];
			$business->longitude=$location['longitude'];
			/***31-12-2020***/
			
			$business->save(); // Save business
			$last_insert_id=$business->id; // get last inserted id
			
			 for ($i = 0; $i < count($request->imageList); $i++) {
				/*$imgArray[] = [
					'image' => $request->imageList[$i],
				];*/
				$businessimage                   	  = new BusinessImage;
				$businessimage->business_id       	  = $last_insert_id;
				$businessimage->image        		  = $request->imageList[$i]['image'];
			
				$businessimage->save();
				
			}
			//dd($imgArray); exit;	
			
			if($business)
			{
				// return response()->json(['data' => $user, 'access_token'=> auth()->user()->createToken('authToken')->accessToken, 'status' => 200, 'message' => ['Registration successful']]);
                return response()->json(['flag'=>"true" , 'status' => 200, 'message' => ['Business added successfully'], 'data' => $business]);
			}
			else
			{
				return response()->json(['flag'=>"false", 'status' => 501, 'message' => ['Add business failed']]);
            }
		}
	}

	//==================ADD TAG===================


public function addTags($searchTag)  
	{

		$tags=json_decode($searchTag);
        $insert_tags=[];
		if (is_array($tags) && count($tags)>0) {
			# code...
			for ($i=0; $i < count($tags); $i++) { 
			# code...
			$tag_find=Tags::where('tag_name','=',$tags[$i])->get();
			if(count($tag_find)=="0"){
			    $insert_tags=Tags::insert(['tag_name'=>$tags[$i]]);
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


	//=============ADD TAG END=================
	
	public function vendorBusinessListing(request $request)  
	{
		$vendor_id=$request->input('user_id');
		$start=$request->input('start');
        $limit=$request->input('limit');
		if($vendor_id=='')
		{
			$resultarray=array(
			  'flag'=>"false",              
			  'details'=>'Missing Parameters'
			);
		}
		else
		{
			if (is_null($limit)) {

				$data['vendorbusiness_list'] = Business::where('user_id',$vendor_id)->get();

			}else{
				$data['vendorbusiness_list'] = Business::where('user_id',$vendor_id)->orderby('id', 'DESC')->limit($limit)->offset($start)->get();

			}
			for($i=0; $i<count($data['vendorbusiness_list']); $i++)
			{
				$data['vendorbusiness_list'][$i]['cityname'] = City::where('id', $data['vendorbusiness_list'][$i]['city'])->first();
				$data['vendorbusiness_list'][$i]['locationname'] = Location::where('id', $data['vendorbusiness_list'][$i]['area'])->first();
				
				$data['vendorbusiness_list'][$i]['catname'] = Category::where('id', $data['vendorbusiness_list'][$i]['business_category'])->first();
				
				$data['vendorbusiness_list'][$i]['subcatname'] = SubCategory::where('id', $data['vendorbusiness_list'][$i]['business_subcategory'])->first();
			
				$data['vendorbusiness_list'][$i]['businessimages'] = BusinessImage::where('business_id', $data['vendorbusiness_list'][$i]['id'])->get();
				
				$noOfComment=BusinessReview::where('business_id',$data['vendorbusiness_list'][$i]['id'])->get();
				$totalCount=COUNT($noOfComment);
				$sum=0;
				if($totalCount>0){
					for($j=0;$j<$totalCount;$j++)
					{
						$noOfComment[$j]['star_marks'];
						$sum=$sum+$noOfComment[$j]['star_marks'];
					}
				}
				
				//echo $sum;
				if($sum>0)
				{
					$data['vendorbusiness_list'][$i]['average']=number_format(($sum/$totalCount),1);
				}
				else
				{
					$data['vendorbusiness_list'][$i]['average']=0;
				}
				
			}
			$img_directory = 'business_images';
			$baseurl=url("uploads/".$img_directory);
			
			if(count($data['vendorbusiness_list'])>0){
				
				$vendorbusiness_list = $data['vendorbusiness_list'];
				//$vendorbusiness_list['baseurl'] 			= $baseurl;
				
						$resultarray=array(
						  'flag'=>"true",              
						  'status'=>"200",              
						  'message'=>"Business list found",   
						  'baseurl'=>$baseurl,  						  
						  'details'=>$data['vendorbusiness_list']
						);
			}else{
						$resultarray=array(
						  'flag'=>"false", 
						  'status'=>"501",						  
						  'message'=>'No data Found'
						);
			}
		}	
		echo json_encode($resultarray);
	}
	
	public function vendorBusinessDetails(request $request)  
	{
		$id=$request->input('business_id');
		$user_id=$request->input('user_id');
		
		if($id=='' || $user_id=='')
		{
			$resultarray=array(
			  'flag'=>"false", 
			  'status'=>"501",	
			  'message'=>'Missing Parameters'
			);
		}
		else
		{
			$data['vendor_business_detail'] = Business::where('id',$id)->first();
			
			$data['cityname'] = City::where('id', $data['vendor_business_detail']['city'])->first();
			$data['locationname'] = Location::where('id', $data['vendor_business_detail']['area'])->first();
			$data['catname'] = Category::where('id', $data['vendor_business_detail']['business_category'])->first();
			$data['subcatname'] = SubCategory::where('id', $data['vendor_business_detail']['business_subcategory'])->first();
			$data['businessimages'] = BusinessImage::where('business_id', $id)->get();
			
			$img_directory = 'business_images';
			$baseurl=url("uploads/".$img_directory);
			
			/***16-12-2020***/
			$isComment=BusinessReview::where('user_id',$user_id)->where('business_id',$id)->first();
			if(is_null($isComment))
			{
				$is_comment=0;
			}
			else
			{
				$is_comment=1;
			}
			$noOfComment=BusinessReview::where('business_id',$id)->get();
			$totalCount=COUNT($noOfComment);
			$sum=0;
			for($i=0;$i<COUNT($noOfComment);$i++)
			{
				//echo $noOfComment[$i]['star_marks'];
				$sum=$sum+$noOfComment[$i]['star_marks'];
			}
			//echo $sum;
			if($sum>0)
			{
				$average=number_format(($sum/$totalCount),1);
			}
			else
			{
				$average=0;
			}
			
			//echo bcdiv($average, 1, 1);  
			//exit;
			/***16-12-2020***/
			
			/***17-12-2020***/
			$limit=3;
			$start=0;
			$data['review_list'] = BusinessReview::where('business_id',$id)->where('status','1')->orderby('id', 'DESC')->limit($limit)->offset($start)->get();
			for($i=0; $i<count($data['review_list']); $i++)
			{
				$userId= $data['review_list'][$i]['user_id'];
				$User = User::where('id', $userId)->first();
				$data['review_list'][$i]['first_name']=$User['first_name'];
				$data['review_list'][$i]['last_name']=$User['last_name'];
				$data['review_list'][$i]['user_logo']=$User['user_logo'];
				$img_directory = 'user_images';
				$data['review_list'][$i]['baseurl']=url("uploads/".$img_directory);
				
			}
			/***17-12-2020***/
			
			if($data['vendor_business_detail']){
				
				/***17-12-2020***/
				$business = Business::find($id);
				if($business)
				{
					$old_count = $business->top_clicked;
					
					$business->top_clicked = $old_count+1;
					$business->save(); // Increase number of count
				}
				/***17-12-2020***/
				
				$vendor_business_detail = $data['vendor_business_detail'];
				$vendor_business_detail['city_details'] = $data['cityname'];
				$vendor_business_detail['location_details'] = $data['locationname'];
				$vendor_business_detail['category_details'] =$data['catname'];
				$vendor_business_detail['sub_category_details'] = $data['subcatname'];
				$vendor_business_detail['businessimages'] = $data['businessimages'];
				//$vendor_business_detail['baseurl'] 			= $baseurl;
				
				/***17-12-2020***/
				$vendor_business_detail['latest_review'] = $data['review_list'];
				/***17-12-2020***/
				
						$resultarray=array(
						  'flag'=>"true",    
						  'status'=>"200",              
						  'message'=>"Business details",
						  'baseurl'=>$baseurl,
						  'is_commented'=>$is_comment,
						  'comment_count'=>$totalCount,
						  'average_rating'=>$average,
						  'latest_clicked_count'=>$business->top_clicked,
						  'details'=>$data['vendor_business_detail'],
						);
			}else{
						$resultarray=array(
						  'flag'=>"false", 
						'status'=>"501",
						  'message'=>'No data Found'
						);
			}
		}	
		echo json_encode($resultarray);
	}
	
	public function editBusinessDetails(Request $request) {
		
		$Validator = Validator::make($request->all(),[
            

            'user_id'             	=> 'required',
            'business_name'         => 'required',
            'business_phone'        => 'required',
            'business_email'        => 'required|email',
            'business_category'     => 'required',
            'address'  				=> 'required',
            
            'city'  				=> 'required',
            
            ]
        );
		$id=$request->input('business_id');
		
		if($id=='')
		{
			$resultarray=array(
			  'flag'=>"false",              
			  'message'=>'Missing Parameters'
			);
		}
		else
		{
			$data['record'] = Business::find($id);
            if(!$data['record']){
                return response()->json(['flag'=>"false", 'status' => 501, 'message' =>"No result found for id: $id"]);
            }
			else
			{
				if($Validator->fails()) {
					return response()->json(['flag'=>"false", 'status' => 401, 'message'=> $Validator->errors()]);
					// return response()->json(ApiHelper::showValidationError($Validator->errors()));
				}
				else
				{
					$business = Business::find($id);
					
					
			$business->user_id       	      = $request->user_id;
			$business->business_name       	  = $request->business_name;
			$business->business_phone         = $request->business_phone;
			$business->business_email         = $request->business_email;
			$business->business_category      = $request->business_category;
			
			
			$business->city    				  = $request->city;
            $business->area    				  = $request->area;
			
			$business->business_subcategory   = $request->business_subcategory;
			$business->business_website		=$request->business_website;
			//$business->status				=$request->status;
			$business->description			=$request->description;
			$business->origin				=$request->origin;
			$business->added_by				=$request->added_by;
			$business->search_tag			=$request->search_tag;
			$business->facebook_link	    =$request->facebook_link;
			$business->twitter_link			=$request->twitter_link;
			$business->instagram_link		=$request->instagram_link;
			$business->address   			= $request->address;
			$business->business_type   		= $request->business_type;
			$business->radius   			= $request->radius;
			$business->post_code   			= $request->post_code;
			$business->show_public   		= $request->show_public;
			$business->featured   		    = $request->featured;
			
			/***18-12-2020***/
				$location=$this->getLatLong($request->address);
				$business->latitude=$location['latitude'];
				$business->longitude=$location['longitude'];
			/***18-12-2020***/
					
					$business->save(); // Save business
					
					if(count($request->imageList)>0)
					{
						/*DB::table('business_images')->where('business_id', $id)
													->update(['active' => 0]);*/
						DB::table('business_images')->where('business_id', $id)->delete();							
						for ($i = 0; $i < count($request->imageList); $i++) 
						{
							$businessimage                   	  = new BusinessImage;
							$businessimage->business_id       	  = $id;
							$businessimage->image        		  = $request->imageList[$i]['image'];
							
							$businessimage->save();
						}
					}	
					
					if($business){
					   // return response()->json(['data' => $user, 'access_token'=> auth()->user()->createToken('authToken')->accessToken, 'status' => 200, 'message' => ['Registration successful']]);
					 return response()->json(['flag'=>"true", 'status' => 200,'message' =>"Edit business successful",  'details' => $business]);
					}else{
						return response()->json(['flag'=>"false", 'status' => 502, 'message' => "Edit business failed"]);
					} 
				}	
			}
		}
		echo json_encode($resultarray);
	}
	
	
	public function FeaturedBusinessListing(request $request)  
	{
		/*$customer_id=$request->input('user_id');
		$post_code=$request->input('post_code');
		$business_category=$request->input('business_category');*/
		$keyword = $request->input('keyword');
		$user_id=$request->input('user_id');
		$latitude=$request->input('latitude');
        $longitude=$request->input('longitude');
		$start=$request->input('start');
        $limit=$request->input('limit');
		
		$searchQuery = Business::query()->where('featured','1')->where('approved_by_admin','1')->where('status','Active');
		
		if($keyword!='')
		{
			$searchQuery = $searchQuery
			->where(function($q) use($keyword){
				 $q->where('business_name', 'LIKE', '%'.$keyword.'%')->orWhere('description','LIKE','%'.$keyword.'%');
			});
		}
		if($user_id!='' && $latitude!='' && $longitude!='')
		{
			$setDistance=20;
			$User = User::where('id', $user_id)->first();
			if($User)
			{
				$distance=$User['radious'];
				if($distance==0)
				{
					$newDIstance=$setDistance; 
				}
				else
				{
					$newDIstance=$User['radious'];
				}
				
				$searchQuery=$searchQuery->select(DB::raw('*, (((acos(sin(('.$latitude.'*pi()/180)) * sin((`latitude`*pi()/180)) + cos(('.$latitude.'*pi()/180)) * cos((`latitude`*pi()/180)) * cos((('.$longitude.' - `longitude`)*pi()/180)))) * 180/pi()) * 60 * 1.1515) as distance'))->having('distance','<=', $newDIstance);
			}
			else
			{
				$resultarray=array(
				'flag'=>"false",
				'status'=>"501",
				'message'=>'User not found'
				);
				echo json_encode($resultarray);
				return;
			}
		}
		if(is_null($limit)) 
		{
			$searchResult = $searchQuery->orderby('id', 'DESC')->get();
		}
		else
		{
			$searchResult = $searchQuery->orderby('id', 'DESC')->limit($limit)->offset($start)->get();
		}
		//$searchResult = $searchQuery;
		
		//echo count($searchResult);exit;
		$data['featuredbusiness_list']=$searchResult;
		for($i=0; $i<count($data['featuredbusiness_list']); $i++)
		{
			$cityDetails = City::where('id',$data['featuredbusiness_list'][$i]->city)->first();
			if($cityDetails)
			{
				$data['featuredbusiness_list'][$i]->cityname = $cityDetails->city_name;
			}
			
			$locationDetails = Location::where('id', $data['featuredbusiness_list'][$i]->area)->first();
			if($locationDetails)
			{
				$data['featuredbusiness_list'][$i]->locationname = $locationDetails->location_name;
			}
			
			
			$catDetails = Category::where('id', $data['featuredbusiness_list'][$i]->business_category)->first();
			if($catDetails)
			{
				$data['featuredbusiness_list'][$i]->catname = $catDetails->category_name;
			}
			
			
			$subCatDetails = SubCategory::where('id', $data['featuredbusiness_list'][$i]->business_subcategory)->first();
			if($subCatDetails)
			{
				$data['featuredbusiness_list'][$i]->subCatname = $subCatDetails->subcategory_name;
			}
			
			
			$data['featuredbusiness_list'][$i]->businessimages = BusinessImage::where('business_id', $data['featuredbusiness_list'][$i]->id)->get();
			
			$noOfComment=BusinessReview::where('business_id',$data['featuredbusiness_list'][$i]->id)->get();
			$totalCount=COUNT($noOfComment);
			$sum=0;
			if($totalCount>0){
				for($j=0;$j<$totalCount;$j++)
				{
					$noOfComment[$j]['star_marks'];
					$sum=$sum+$noOfComment[$j]['star_marks'];
				}
			}
			
			//echo $sum;
			if($sum>0)
			{
				$data['featuredbusiness_list'][$i]->average=number_format(($sum/$totalCount),1);
			}
			else
			{
				$data['featuredbusiness_list'][$i]->average=0;
			}
		}
		
			$img_directory = 'business_images';
			$baseurl=url("uploads/".$img_directory);
			
			if(count($data['featuredbusiness_list'])>0)
			{
				$featuredbusiness_list = $data['featuredbusiness_list'];
				//$featuredbusiness_list['baseurl'] 			= $baseurl;
				
				$resultarray=array(
				  'flag'=>"true",              
				  'status'=>"200",              
				  'message'=>"Featured Business list found",   
				  'baseurl'=>$baseurl,  						  
				  'details'=>$data['featuredbusiness_list']
				);
			}
			else
			{
				$resultarray=array(
				  'flag'=>"false", 
				  'status'=>"501",						  
				  'message'=>'No data Found'
				);
			}
			echo json_encode($resultarray);
	}
	
	
	public function customerBusinessDetailFetch(request $request)  
	{
		$id=$request->input('business_id');
		
		if($id=='')
		{
			$resultarray=array(
			  'flag'=>"false",              
			  'details'=>'Missing Parameters'
			);
		}
		else
		{
			$data['customer_business_detail'] = Business::where('id',$id)
			->where('added_by','customer')->first();
			//dd($data['customer_business_detail']->businessimages);exit;
			if($data['customer_business_detail']){
						$resultarray=array(
						  'flag'=>"true",              
						  'details'=>$data['customer_business_detail'],
						  /*'Images'=>$data['customer_business_detail']->businessimages,*/
						);
			}else{
						$resultarray=array(
						  'flag'=>"false",              
						  'details'=>'No data Found'
						);
			}
		}	
		echo json_encode($resultarray);
	}
	
	public function cityList(request $request)  
	{
		$data['city_info'] = City::get();
		if(count($data['city_info'])>0){
					$resultarray=array(
					  'flag'=>"true",              
					  'status'=>"200",    
					  'message'=>'City list found',					  
					  'details'=>$data['city_info']
					);
		}else{
					$resultarray=array(
					  'flag'=>"false", 
					  'status' => 501,					  
					  'message'=>'No data Found'
					);
		}
		echo json_encode($resultarray);
	}
		
	public function getLocationByCity(request $request)
	{
		$id=$request->input('city_id');
		
		if($id=='')
		{
			$resultarray=array(
			  'flag'=>"false",   
			  'status' => 501,			  
			  'message'=>'Missing Parameters'
			);
		}
		else
		{
			$data['location_info'] = Location::where('city_id',$id)->get();
			if(count($data['location_info'])>0){
						$resultarray=array(
						  'flag'=>"true",  
						  'status' => 200,	
						  'message'=>'Location found for this city id',						  
						  'details'=>$data['location_info']
						);
			}else{
						$resultarray=array(
						  'flag'=>"false",      
						  'status' => 501,						  
						  'message'=>'No data Found'
						);
			}
		}	
		echo json_encode($resultarray);
	}
	
	
	public function searchByLocationTab(request $request)
	{
		/* search items */
		$category_id		=	$request->input('category_id');
		$sub_category_id	=	$request->input('sub_category_id');
		$search_tag			=	$request->input('search_tag');
		$city_id			=	$request->input('city_id');
		$post_code			=	$request->input('post_code');
		$region				=	$request->input('region');
		$start				=	$request->input('start');
        $limit				=	$request->input('limit');
		/* search items */
		$searchQuery = Business::query()->where('approved_by_admin','1')->where('status','Active');

		if($category_id!='')
		{
			$searchQuery = $searchQuery->where('business_category', '=', $category_id);
		}
		if($sub_category_id!='')
		{
			$searchQuery = $searchQuery->where('business_subcategory', '=', $sub_category_id);
		}
		if($search_tag!='')
		{
			$searchQuery = $searchQuery->where('search_tag', 'LIKE', '%'.$search_tag.'%');
		}
		if($city_id!='')
		{
			$searchQuery = $searchQuery->where('city', '=', $city_id);
		}
		if($post_code!='')
		{
			$searchQuery = $searchQuery->where('post_code', '=', $post_code);
		}
		
		if($region!='' && $post_code!='')
		{
			$address=$post_code;
			$location=$this->getLatLong($address);
			//echo "<pre>"; print_r($location);exit;
			$latitude=$location['latitude'];
			$longitude=$location['longitude']; 
			//exit;
			$searchQuery=$searchQuery->select(DB::raw('*, (((acos(sin(('.$latitude.'*pi()/180)) * sin((`latitude`*pi()/180)) + cos(('.$latitude.'*pi()/180)) * cos((`latitude`*pi()/180)) * cos((('.$longitude.' - `longitude`)*pi()/180)))) * 180/pi()) * 60 * 1.1515) as distance'))->having('distance','<=', $region);
		}
		if($region!='' && $city_id!='')
		{
			$city = City::where('id',$city_id)->first();
			//echo "<pre>"; print_r($city);
			//echo $city->city_name;exit;
			$address=$city->city_name;
			$location=$this->getLatLong($address);
			//echo "<pre>"; print_r($location);exit;
			$latitude=$location['latitude'];
			$longitude=$location['longitude']; 
			//exit;
			$searchQuery=$searchQuery->select(DB::raw('*, (((acos(sin(('.$latitude.'*pi()/180)) * sin((`latitude`*pi()/180)) + cos(('.$latitude.'*pi()/180)) * cos((`latitude`*pi()/180)) * cos((('.$longitude.' - `longitude`)*pi()/180)))) * 180/pi()) * 60 * 1.1515) as distance'))->having('distance','<=', $region);
		}
		
		if(is_null($limit)) 
		{
			$searchResult = $searchQuery->orderby('id', 'DESC')->get();
		}
		else
		{
			$searchResult = $searchQuery->orderby('id', 'DESC')->limit($limit)->offset($start)->get();
		}
		
		$data['business_list']=$searchResult;
		for($i=0; $i<count($data['business_list']); $i++)
		{
			$data['business_list'][$i]['cityname'] = City::where('id', $data['business_list'][$i]['city'])->first();
			
			$data['business_list'][$i]['catname'] = Category::where('id', $data['business_list'][$i]['business_category'])->first();
			
			$data['business_list'][$i]['subcatname'] = SubCategory::where('id', $data['business_list'][$i]['business_subcategory'])->first();
		
			$data['business_list'][$i]['businessimages'] = BusinessImage::where('business_id', $data['business_list'][$i]['id'])->get();
			
			$noOfComment=BusinessReview::where('business_id',$data['business_list'][$i]['id'])->get();
			$totalCount=COUNT($noOfComment);
			$sum=0;
			if($totalCount>0){
				for($j=0;$j<$totalCount;$j++)
				{
					$noOfComment[$j]['star_marks'];
					$sum=$sum+$noOfComment[$j]['star_marks'];
				}
			}
			
			//echo $sum;
			if($sum>0)
			{
				$data['business_list'][$i]['average']=number_format(($sum/$totalCount),1);
			}
			else
			{
				$data['business_list'][$i]['average']=0;
			}
		}
		
		$img_directory = 'business_images';
		$baseurl=url("uploads/".$img_directory);	
		if(count($data['business_list'])>0)
		{
			$resultarray=array(
			  'flag'=>"true",              
			  'status'=>"200",              
			  'message'=>"Busines List Found",              
			  'baseurl'=>$baseurl,              
			  'details'=>$data['business_list']
			);
		}
		else
		{
			$resultarray=array(
			  'flag'=>"false",      
			  'status'=>"501", 		  
			  'message'=>'No data Found'
			);
		}	
		echo json_encode($resultarray);
	}
	
	
	public function searchByCategoryTab(request $request)
	{
		/* search items */
		$category_id		=	$request->input('category_id');
		$sub_category_id	=	$request->input('sub_category_id');
		$search_tag			=	$request->input('search_tag');
		$city_id			=	$request->input('city_id');
		$post_code			=	$request->input('post_code');
		$start				=	$request->input('start');
        $limit				=	$request->input('limit');
		/* search items */
		
		$searchQuery = Business::query()->where('approved_by_admin','1')->where('status','Active');

		if($category_id!='')
		{
			$searchQuery = $searchQuery->where('business_category', '=', $category_id);
		}
		if($sub_category_id!='')
		{
			$searchQuery = $searchQuery->where('business_subcategory', '=', $sub_category_id);
		}
		if($search_tag!='')
		{
			$searchQuery = $searchQuery->where('search_tag', 'LIKE', '%'.$search_tag.'%');
		}
		if($city_id!='')
		{
			$searchQuery = $searchQuery->where('city', '=', $city_id);
		}
		if($post_code!='')
		{
			$searchQuery = $searchQuery->where('post_code', '=', $post_code);
		}
		if(is_null($limit)) 
		{
			$searchResult = $searchQuery->where('id', '!=', '')->orderby('id', 'DESC')->get();
		}
		else
		{
			$searchResult = $searchQuery->where('id', '!=', '')->orderby('id', 'DESC')->limit($limit)->offset($start)->get();
		}
		//$searchResult = $searchQuery->where('top_clicked', '>=', 10)->where('featured', '=', '1')->get();
		
		$data['business_list']=$searchResult;
		for($i=0; $i<count($data['business_list']); $i++)
		{
			$data['business_list'][$i]['cityname'] = City::where('id', $data['business_list'][$i]['city'])->first();
			/*$data['business_list'][$i]['locationname'] = Location::where('id', $data['business_list'][$i]['area'])->first();*/
			
			$data['business_list'][$i]['catname'] = Category::where('id', $data['business_list'][$i]['business_category'])->first();
			
			$data['business_list'][$i]['subcatname'] = SubCategory::where('id', $data['business_list'][$i]['business_subcategory'])->first();
		
			$data['business_list'][$i]['businessimages'] = BusinessImage::where('business_id', $data['business_list'][$i]['id'])->get();
			
			$noOfComment=BusinessReview::where('business_id',$data['business_list'][$i]['id'])->get();
			$totalCount=COUNT($noOfComment);
			$sum=0;
			if($totalCount>0){
				for($j=0;$j<$totalCount;$j++)
				{
					$noOfComment[$j]['star_marks'];
					$sum=$sum+$noOfComment[$j]['star_marks'];
				}
			}
			
			//echo $sum;
			if($sum>0)
			{
				$data['business_list'][$i]['average']=number_format(($sum/$totalCount),1);
			}
			else
			{
				$data['business_list'][$i]['average']=0;
			}
		}
		
		$img_directory = 'business_images';
		$baseurl=url("uploads/".$img_directory);	
		if(count($data['business_list'])>0)
		{
			$resultarray=array(
			  'flag'=>"true",              
			  'status'=>"200",              
			  'message'=>"Busines List Found",              
			  'baseurl'=>$baseurl,              
			  'details'=>$data['business_list']
			);
		}
		else
		{
			$resultarray=array(
			  'flag'=>"false",      
			  'status'=>"501", 		  
			  'message'=>'No data Found'
			);
		}	
		echo json_encode($resultarray);
	}
	
	
	public function searchByFeaturedBusinessTab(request $request)
	{
		/* search items */
		$category_id		=	$request->input('category_id');
		$sub_category_id	=	$request->input('sub_category_id');
		$search_tag			=	$request->input('search_tag');
		$city_id			=	$request->input('city_id');
		$post_code			=	$request->input('post_code');
		$start				=	$request->input('start');
        $limit				=	$request->input('limit');
		/* search items */
		
		$searchQuery = Business::query()->where('approved_by_admin','=','1')->where('status','Active');

		if($category_id!='')
		{
			$searchQuery = $searchQuery->where('business_category', '=', $category_id);
		}
		if($sub_category_id!='')
		{
			$searchQuery = $searchQuery->where('business_subcategory', '=', $sub_category_id);
		}
		if($search_tag!='')
		{
			$searchQuery = $searchQuery->where('search_tag', 'LIKE', '%'.$search_tag.'%');
		}
		if($city_id!='')
		{
			$searchQuery = $searchQuery->where('city', '=', $city_id);
		}
		if($post_code!='')
		{
			$searchQuery = $searchQuery->where('post_code', '=', $post_code);
		}
		if(is_null($limit)) 
		{
			$searchResult = $searchQuery->where('featured', '=', '1')->orderby('id', 'DESC')->get();
		}
		else
		{
			$searchResult = $searchQuery->where('featured', '=', '1')->orderby('id', 'DESC')->limit($limit)->offset($start)->get();
		}
		//$searchResult = $searchQuery->where('top_clicked', '>=', 10)->where('featured', '=', '1')->get();
		
		$data['business_list']=$searchResult;
		for($i=0; $i<count($data['business_list']); $i++)
		{
			$data['business_list'][$i]['cityname'] = City::where('id', $data['business_list'][$i]['city'])->first();
			/*$data['business_list'][$i]['locationname'] = Location::where('id', $data['business_list'][$i]['area'])->first();*/
			
			$data['business_list'][$i]['catname'] = Category::where('id', $data['business_list'][$i]['business_category'])->first();
			
			$data['business_list'][$i]['subcatname'] = SubCategory::where('id', $data['business_list'][$i]['business_subcategory'])->first();
		
			$data['business_list'][$i]['businessimages'] = BusinessImage::where('business_id', $data['business_list'][$i]['id'])->get();
			
			$noOfComment=BusinessReview::where('business_id',$data['business_list'][$i]['id'])->get();
			$totalCount=COUNT($noOfComment);
			$sum=0;
			if($totalCount>0){
				for($j=0;$j<$totalCount;$j++)
				{
					$noOfComment[$j]['star_marks'];
					$sum=$sum+$noOfComment[$j]['star_marks'];
				}
			}
			
			//echo $sum;
			if($sum>0)
			{
				$data['business_list'][$i]['average']=number_format(($sum/$totalCount),1);
			}
			else
			{
				$data['business_list'][$i]['average']=0;
			}
		}
		
		$img_directory = 'business_images';
		$baseurl=url("uploads/".$img_directory);	
		if(count($data['business_list'])>0)
		{
			$resultarray=array(
			  'flag'=>"true",              
			  'status'=>"200",              
			  'message'=>"Busines List Found",              
			  'baseurl'=>$baseurl,              
			  'details'=>$data['business_list']
			);
		}
		else
		{
			$resultarray=array(
			  'flag'=>"false",      
			  'status'=>"501", 		  
			  'message'=>'No data Found'
			);
		}	
		echo json_encode($resultarray);
	}
	
	
	public function increaseTopClicked(request $request)
	{
		$business_id		=	$request->input('business_id');
		if(empty($business_id))
		{
			$resultarray=array(
			  'flag'=>"false",      
			  'status'=>"501", 		  
			  'message'=>'Missing Parameter'
			);
		}
		else
		{
			$business = Business::find($business_id);
			if($business)
			{
				$old_count = $business->top_clicked;
				
				$business->top_clicked = $old_count+1;
				$business->save(); // Increase number of count
				
				$resultarray=array(
				  'flag'=>"true",      
				  'status'=>"200", 		  
				  'message'=>'Count increase successfully',
				  'New Count'=>$business->top_clicked
				);
			}
			else
			{
				$resultarray=array(
				  'flag'=>"false",      
				  'status'=>"501", 		  
				  'message'=>'No data found'
				);
			}				
		}
		echo json_encode($resultarray);
	}

	/**
        * Function Name :  deleteVendorBusinessByBusinessId
        * Purpose       :  Delete business.
        * Author        :
        * Created Date  :  2020-12-11
        * Input Params  :  business_id
        * Return Value  :  flag,details
	*/
	public function deleteVendorBusinessByBusinessId(request $request)
	{

		$business_id		=	$request->input('business_id');
		$business = Business::find($business_id);
		if (is_null($business)) {
			# code...
			$resultarray=array(
				  'flag'=>"false",      
				  'status'=>"202" ,		  
				  'message'=>'business not found'
				  
				);
				echo json_encode($resultarray);
				return;
		}
		if (!empty($business_id)) {
			# code...
			
			$business_img_del = BusinessImage::where('business_id','=',$business_id)->get();
			
			$destinationPath = 'uploads/business_images'; // upload path
			for ($i=0; $i < count($business_img_del); $i++) { 
				# code...
				
				@unlink($destinationPath.$business_img_del[$i]->image);
			}

			
			$business_img_del = BusinessImage::where('business_id',$business_id)->delete();
				$business_del = Business::where('id',$business_id)->delete();
			if ($business_del && $business_img_del) {
				# code...
				$resultarray=array(
				  'flag'=>"true",      
				  'status'=>"200",	  
				  'message'=>'business deleted successfully'
				  
				);
				echo json_encode($resultarray);
			}else{
				$resultarray=array(
				  'flag'=>"false",      
				  'status'=>"201",
				   		  
				  'message'=>'unable to delete'
				  
				);
				echo json_encode($resultarray);

			}

		}else{
			$resultarray=array(
			  'flag'=>"false",      
			  'status'=>"501", 		  
			  'message'=>'Missing Parameter'
			);
			echo json_encode($resultarray);
		}

	}  

	
	/**
        * Function Name :  reviewPostToBusiness
        * Purpose       :  Post Review To Business.
        * Author        :
        * Created Date  :  2020-12-16
        * Input Params  :  user_id,business_id,star_marks,comment
        * Return Value  :  flag,details
	*/
	public function reviewPostToBusiness(request $request)
	{
		$user_id		=	$request->input('user_id');
		$business_id	=	$request->input('business_id');
		$star_marks		=	$request->input('star_marks');
		$comment		=	$request->input('comment');
		if($user_id=='' || $business_id=='' || $star_marks=='' || $comment=='')
		{
			$resultarray=array(
			'flag'=>"false",
			'status'=>"501",
			'message'=>'Missing Parameters'
			);
			echo json_encode($resultarray);
			return;
		}
		else
		{
			$business = Business::find($business_id);
			$user = User::find($user_id);
			if (is_null($business)) 
			{
				$resultarray=array(
					  'flag'=>"false",      
					  'status'=>"502",		  
					  'message'=>'Business not found'
					  
					);
				echo json_encode($resultarray);
				return;
			}
			elseif(is_null($user))
			{
				$resultarray=array(
					  'flag'=>"false",      
					  'status'=>"506",		  
					  'message'=>'User not found'
					  
					);
				echo json_encode($resultarray);
				return;
			}
			
			else
			{
				$checkSelfBusiness = Business::where('user_id', $user_id)->where('id',$business_id)->get();
				if (count($checkSelfBusiness)>0) 
				{
					$resultarray=array(
						  'flag'=>"false",      
						  'status'=>"503" ,		  
						  'message'=>'It is your own business, You can not comment/review post'
						  
						);
					echo json_encode($resultarray);
					return;
				}
				else
				{
					$alreadyPost = BusinessReview::where('user_id', $user_id)->where('business_id',$business_id)->get();
					if (count($alreadyPost)>0) 
					{
						$resultarray=array(
							  'flag'=>"false",      
							  'status'=>"504" ,		  
							  'message'=>'Sorry, you already posted review on the business'
							  
							);
						echo json_encode($resultarray);
						return;
					}
					else
					{
						
						$checkUserType = User::where('id', $user_id)->where('user_type','VU')->first();
						if(is_null($checkUserType)) 
						{
							$user = User::find($user_id);
							$firstname=$user->first_name;
							$lastname=$user->last_name;
							
							$business = Business::find($request->business_id);
							$businessname=$business->business_name;
							
							$businessreview                = new BusinessReview;
							$businessreview->user_id       = $request->user_id;
							
							$businessreview->first_name    = $firstname;
							$businessreview->last_name     = $lastname;
							$businessreview->business_name = $businessname;
							
							$businessreview->business_id   = $request->business_id;
							$businessreview->star_marks    = $request->star_marks;
							$businessreview->comment       = $request->comment;
							
							$businessreview->save();
							
							$resultarray=array(
							  'flag'=>"true",      
							  'status'=>"200",	  
							  'message'=>'Review submitted successfully',
							  'review'=>$businessreview
							  
							);
							echo json_encode($resultarray);
						}
						else
						{
							$resultarray=array(
							  'flag'=>"false",      
							  'status'=>"505" ,		  
							  'message'=>'Sorry, you are not eligible for post a review'
							  
							);
							echo json_encode($resultarray);
							return;
						}
						
					}	
				}
			}
		}
	}
	
	
	/**
        * Function Name :  reviewList
        * Purpose       :  Review List With Pagination.
        * Author        :
        * Created Date  :  2020-12-16
        * Input Params  :  start,limit
        * Return Value  :  flag,details
	*/
	public function reviewList(request $request)  
	{
		$business_id=$request->input('business_id');
		$start=$request->input('start');
        $limit=$request->input('limit');
		if($business_id=='')
		{
			$resultarray=array(
			'flag'=>"false",
			'status'=>"501",
			'message'=>'Missing Parameters'
			);
			echo json_encode($resultarray);
			return;
		}
		if (is_null($limit)) 
		{
			$data['review_list'] = BusinessReview::where('business_id', $business_id)->where('status','1')->get();
		}
		else
		{
			$data['review_list'] = BusinessReview::where('business_id', $business_id)->where('status','1')->orderby('id', 'DESC')->limit($limit)->offset($start)->get();
		}
			
			if(count($data['review_list'])>0)
			{
				for($i=0; $i<count($data['review_list']); $i++)
				{
					$userId= $data['review_list'][$i]['user_id'];
					$User = User::where('id', $userId)->first();
					$data['review_list'][$i]['first_name']=$User['first_name'];
					$data['review_list'][$i]['last_name']=$User['last_name'];
					$data['review_list'][$i]['user_logo']=$User['user_logo'];
					$img_directory = 'user_images';
					$data['review_list'][$i]['baseurl']=url("uploads/".$img_directory);
					
				}
				$review_list = $data['review_list'];
				
				$resultarray=array(
				  'flag'=>"true",              
				  'status'=>"200",              
				  'message'=>"Review list found", 						  
				  'details'=>$data['review_list']
				);
			}
			else
			{
				$resultarray=array(
				  'flag'=>"false", 
				  'status'=>"501",						  
				  'message'=>'No data Found'
				);
			}
			echo json_encode($resultarray);
	}
	
	/**
        * Function Name :  nearByBusinessList
        * Purpose       :  Find Near by Business List With Pagination.
        * Author        :
        * Created Date  :  2020-12-18
        * Input Params  :  user_id,start,limit
        * Return Value  :  flag,details
	*/
	public function nearByBusinessList(request $request)
	{
		$keyword = $request->input('keyword');
		$user_id=$request->input('user_id');
		$start=$request->input('start');
        $limit=$request->input('limit');
        $latitude=$request->input('latitude');
        $longitude=$request->input('longitude');
		
		$searchQuery = Business::query()->where('featured','1')->where('approved_by_admin','1')->where('status','Active');
		
		if($keyword!='')
		{
			$searchQuery = $searchQuery
			->where(function($q) use($keyword){
				 $q->where('business_name', 'LIKE', '%'.$keyword.'%')->orWhere('description','LIKE','%'.$keyword.'%');
			});
		}
		
		if($user_id=='' || $latitude=='' || $longitude=='')
		{
			$resultarray=array(
			'flag'=>"false",
			'status'=>"501",
			'message'=>'Missing Parameters'
			);
			echo json_encode($resultarray);
			return;
		}
		
			$setDistance=20;
			$User = User::where('id', $user_id)->first();
			if($User)
			{
				$distance=$User['radious'];
				if($distance==0)
				{
					$newDIstance=$setDistance; 
				}
				else
				{
					$newDIstance=$User['radious'];
				}
				$searchQuery=$searchQuery->select(DB::raw('*, (((acos(sin(('.$latitude.'*pi()/180)) * sin((`latitude`*pi()/180)) + cos(('.$latitude.'*pi()/180)) * cos((`latitude`*pi()/180)) * cos((('.$longitude.' - `longitude`)*pi()/180)))) * 180/pi()) * 60 * 1.1515) as distance'))->having('distance','<=', $newDIstance);
			}
			else
			{
				$resultarray=array(
				'flag'=>"false",
				'status'=>"501",
				'message'=>'User not found'
				);
				echo json_encode($resultarray);
				return;
			}
			
			if(is_null($limit)) 
			{
				$searchResult = $searchQuery->orderby('id', 'DESC')->get();
			}
			else
			{
				$searchResult = $searchQuery->orderby('id', 'DESC')->limit($limit)->offset($start)->get();
			}				
			
			//echo json_encode($data['nearBy_business_list']);
			//exit;

		$data['nearBy_business_list']=$searchResult;	
		if(count($data['nearBy_business_list'])>0)
			{
				for($i=0; $i<count($data['nearBy_business_list']); $i++)
				{
					//echo $data['nearBy_business_list'][$i]->area;exit;
					
					$cityDetails = City::where('id',$data['nearBy_business_list'][$i]->city)->first();
					if($cityDetails)
					{
						$data['nearBy_business_list'][$i]->cityname = $cityDetails->city_name;
					}
					
					$locationDetails = Location::where('id', $data['nearBy_business_list'][$i]->area)->first();
					if($locationDetails)
					{
						$data['nearBy_business_list'][$i]->locationname = $locationDetails->location_name;
					}
					
					
					$catDetails = Category::where('id', $data['nearBy_business_list'][$i]->business_category)->first();
					if($catDetails)
					{
						$data['nearBy_business_list'][$i]->catname = $catDetails->category_name;
					}
					
					
					$subCatDetails = SubCategory::where('id', $data['nearBy_business_list'][$i]->business_subcategory)->first();
					if($subCatDetails)
					{
						$data['nearBy_business_list'][$i]->subCatname = $subCatDetails->subcategory_name;
					}
					
					$data['nearBy_business_list'][$i]->businessimages = BusinessImage::where('business_id', $data['nearBy_business_list'][$i]->id)->get();
					
					$noOfComment=BusinessReview::where('business_id',$data['nearBy_business_list'][$i]->id)->get();
					$totalCount=COUNT($noOfComment);
					$sum=0;
					if($totalCount>0){
						for($j=0;$j<$totalCount;$j++)
						{
							$noOfComment[$j]['star_marks'];
							$sum=$sum+$noOfComment[$j]['star_marks'];
						}
					}
					
					//echo $sum;
					if($sum>0)
					{
						$data['nearBy_business_list'][$i]->average=number_format(($sum/$totalCount),1);
					}
					else
					{
						$data['nearBy_business_list'][$i]->average=0;
					}
					
					$favourite = BusinessFavorite::where('business_id', $data['nearBy_business_list'][$i]->id)->where('user_id',$user_id)->first();
					
					if($favourite)
					{
						$data['nearBy_business_list'][$i]->is_favourite=1;
					}
					else
					{
						$data['nearBy_business_list'][$i]->is_favourite=0;
					}
					
				}
			//exit;
			$img_directory = 'business_images';
			$baseurl=url("uploads/".$img_directory);
				
				$resultarray=array(
				  'flag'=>"true",              
				  'status'=>"200",              
				  'message'=>"Business list found", 
				  'baseurl'=>$baseurl,	
				  'details'=>$data['nearBy_business_list']
				);
			}
			else
			{
				$resultarray=array(
				  'flag'=>"false", 
				  'status'=>"501",						  
				  'message'=>'No data Found'
				);
			}
			echo json_encode($resultarray);
	}
	
	public function FeaturedBusinessListWithMostClick(request $request)  
	{
		$start=0;
        $limit=10;
		if (is_null($limit)) 
		{
			$data['featuredbusiness_list'] = Business::where('featured','1')->where('approved_by_admin','1')->where('status','Active')->get();
		}
		else
		{
			$data['featuredbusiness_list'] = Business::where('featured','1')->where('approved_by_admin','1')->where('status','Active')->orderby('top_clicked', 'DESC')->limit($limit)->offset($start)->get();
		}
		for($i=0; $i<count($data['featuredbusiness_list']); $i++)
		{
			$data['featuredbusiness_list'][$i]['cityname'] = City::where('id', $data['featuredbusiness_list'][$i]['city'])->first();
			/*$data['featuredbusiness_list'][$i]['locationname'] = Location::where('id', $data['featuredbusiness_list'][$i]['area'])->first();*/
			
			$data['featuredbusiness_list'][$i]['catname'] = Category::where('id', $data['featuredbusiness_list'][$i]['business_category'])->first();
			
			$data['featuredbusiness_list'][$i]['subcatname'] = SubCategory::where('id', $data['featuredbusiness_list'][$i]['business_subcategory'])->first();
		
			$data['featuredbusiness_list'][$i]['businessimages'] = BusinessImage::where('business_id', $data['featuredbusiness_list'][$i]['id'])->get();
			
			$noOfComment=BusinessReview::where('business_id',$data['featuredbusiness_list'][$i]['id'])->get();
			$totalCount=COUNT($noOfComment);
			$sum=0;
			if($totalCount>0){
				for($j=0;$j<$totalCount;$j++)
				{
					$noOfComment[$j]['star_marks'];
					$sum=$sum+$noOfComment[$j]['star_marks'];
				}
			}
			
			//echo $sum;
			if($sum>0)
			{
				$data['featuredbusiness_list'][$i]['average']=number_format(($sum/$totalCount),1);
			}
			else
			{
				$data['featuredbusiness_list'][$i]['average']=0;
			}
		}
		
			$img_directory = 'business_images';
			$baseurl=url("uploads/".$img_directory);
			
			if(count($data['featuredbusiness_list'])>0)
			{
				$featuredbusiness_list = $data['featuredbusiness_list'];
				//$featuredbusiness_list['baseurl'] 			= $baseurl;
				
				$resultarray=array(
				  'flag'=>"true",              
				  'status'=>"200",              
				  'message'=>"Featured Business list with top clicked found",   
				  'baseurl'=>$baseurl,  						  
				  'details'=>$data['featuredbusiness_list']
				);
			}
			else
			{
				$resultarray=array(
				  'flag'=>"false", 
				  'status'=>"501",						  
				  'message'=>'No data Found'
				);
			}
			echo json_encode($resultarray);
	}
	
	public function LatestTenBusinessList(request $request)  
	{
		$start=0;
        $limit=10;
		
		$data['latestbusiness_list'] = Business::where('id','!=','')->where('approved_by_admin','1')->where('status','Active')->orderby('created_at', 'DESC')->limit($limit)->offset($start)->get();

		for($i=0; $i<count($data['latestbusiness_list']); $i++)
		{
			$data['latestbusiness_list'][$i]['cityname'] = City::where('id', $data['latestbusiness_list'][$i]['city'])->first();
			/*$data['latestbusiness_list'][$i]['locationname'] = Location::where('id', $data['latestbusiness_list'][$i]['area'])->first();*/
			
			$data['latestbusiness_list'][$i]['catname'] = Category::where('id', $data['latestbusiness_list'][$i]['business_category'])->first();
			
			$data['latestbusiness_list'][$i]['subcatname'] = SubCategory::where('id', $data['latestbusiness_list'][$i]['business_subcategory'])->first();
		
			$data['latestbusiness_list'][$i]['businessimages'] = BusinessImage::where('business_id', $data['latestbusiness_list'][$i]['id'])->get();
			
			$noOfComment=BusinessReview::where('business_id',$data['latestbusiness_list'][$i]['id'])->get();
			$totalCount=COUNT($noOfComment);
			$sum=0;
			if($totalCount>0){
				for($j=0;$j<$totalCount;$j++)
				{
					$noOfComment[$j]['star_marks'];
					$sum=$sum+$noOfComment[$j]['star_marks'];
				}
			}
			
			//echo $sum;
			if($sum>0)
			{
				$data['latestbusiness_list'][$i]['average']=number_format(($sum/$totalCount),1);
			}
			else
			{
				$data['latestbusiness_list'][$i]['average']=0;
			}
		}
		
			$img_directory = 'business_images';
			$baseurl=url("uploads/".$img_directory);
			
			if(count($data['latestbusiness_list'])>0)
			{
				$latestbusiness_list = $data['latestbusiness_list'];
				//$latestbusiness_list['baseurl'] 			= $baseurl;
				
				$resultarray=array(
				  'flag'=>"true",              
				  'status'=>"200",              
				  'message'=>"Latest Business list found",   
				  'baseurl'=>$baseurl,  						  
				  'details'=>$data['latestbusiness_list']
				);
			}
			else
			{
				$resultarray=array(
				  'flag'=>"false", 
				  'status'=>"501",						  
				  'message'=>'No data Found'
				);
			}
			echo json_encode($resultarray);
	}
	
	public function getMostUsedBusinessCategory(request $request)
	{
		$start=0;
        $limit=10;
		
		$data['mostaddedcategory_list']=DB::table('businesses')->select(DB::raw('count(*) AS count, business_category'))->groupBy('business_category')->HAVING('count', '>', 0)->orderby('count', 'DESC')->limit($limit)->offset($start)->get();
		
		if(count($data['mostaddedcategory_list'])>0)
		{
			for($i=0; $i<count($data['mostaddedcategory_list']); $i++)
			{
				//echo $data['mostaddedcategory_list'][$i]->area;exit;
				$catDetails = Category::where('id', $data['mostaddedcategory_list'][$i]->business_category)->first();
				if($catDetails)
				{
					$data['mostaddedcategory_list'][$i]->catname = $catDetails->category_name;
				}
			}	
			$mostaddedcategory_list = $data['mostaddedcategory_list'];
			
			$resultarray=array(
			  'flag'=>"true",              
			  'status'=>"200",              
			  'message'=>"Most used category list found",   						  
			  'details'=>$data['mostaddedcategory_list']
			);
		}
		else
		{
			$resultarray=array(
			  'flag'=>"false", 
			  'status'=>"501",						  
			  'message'=>'No data Found'
			);
		}
		echo json_encode($resultarray);
	}
	
	public function homePageSearch(request $request)
	{
		/* search items */
		$keyword = $request->input('keyword');
		$start=$request->input('start');
        $limit=$request->input('limit');
		/* search items */
		
		$searchQuery = Business::query();

		if($keyword!='')
		{
			$searchQuery = $searchQuery->where('approved_by_admin','=','1')->where('status','Active')
			->where(function($q) use($keyword){
				 $q->where('business_name', 'LIKE', '%'.$keyword.'%')->orWhere('description','LIKE','%'.$keyword.'%');
			});
		}
		if(is_null($limit)) 
		{
			$searchResult = $searchQuery->where('id', '!=', '')->orderby('id', 'DESC')->get();
		}
		else
		{
			$searchResult = $searchQuery->where('id', '!=', '')->orderby('id', 'DESC')->limit($limit)->offset($start)->get();
		}
		
		$data['business_list']=$searchResult;
		
		for($i=0; $i<count($data['business_list']); $i++)
		{
			$data['business_list'][$i]['cityname'] = City::where('id', $data['business_list'][$i]['city'])->first();
			/*$data['business_list'][$i]['locationname'] = Location::where('id', $data['business_list'][$i]['area'])->first();*/
			
			$data['business_list'][$i]['catname'] = Category::where('id', $data['business_list'][$i]['business_category'])->first();
			
			$data['business_list'][$i]['subcatname'] = SubCategory::where('id', $data['business_list'][$i]['business_subcategory'])->first();
		
			$data['business_list'][$i]['businessimages'] = BusinessImage::where('business_id', $data['business_list'][$i]['id'])->get();
			
			$noOfComment=BusinessReview::where('business_id',$data['business_list'][$i]['id'])->get();
			$totalCount=COUNT($noOfComment);
			$sum=0;
			if($totalCount>0){
				for($j=0;$j<$totalCount;$j++)
				{
					$noOfComment[$j]['star_marks'];
					$sum=$sum+$noOfComment[$j]['star_marks'];
				}
			}
			
			//echo $sum;
			if($sum>0)
			{
				$data['business_list'][$i]['average']=number_format(($sum/$totalCount),1);
			}
			else
			{
				$data['business_list'][$i]['average']=0;
			}
		}
		
		$img_directory = 'business_images';
		$baseurl=url("uploads/".$img_directory);
		
		if(count($data['business_list'])>0)
		{
			$resultarray=array(
			  'flag'=>"true",              
			  'status'=>"200",              
			  'message'=>"Busines List Found",              
			  'baseurl'=>$baseurl,              
			  'details'=>$data['business_list']
			);
		}
		else
		{
			$resultarray=array(
			  'flag'=>"false",      
			  'status'=>"501", 		  
			  'message'=>'No data Found'
			);
		}	
		echo json_encode($resultarray);
	}
	
	public function autoSuggestCategoryList(request $request)
	{
		/* search items */
		$keyword = $request->input('keyword');
		$start=$request->input('start');
        $limit=$request->input('limit');
		/* search items */
		$searchQuery = Category::query();

		if($keyword!='')
		{
			$searchQuery = $searchQuery->where('category_name', 'LIKE', $keyword.'%');
		}
		if(is_null($limit)) 
		{
			$searchResult = $searchQuery->orderby('id', 'DESC')->get();
		}
		else
		{
			$searchResult = $searchQuery->orderby('id', 'DESC')->limit($limit)->offset($start)->get();
		}
		
		$data['category_list']=$searchResult;
		
		if(count($data['category_list'])>0)
		{
			$resultarray=array(
				  'flag'=>"true",              
				  'status'=>"200",              
				  'message'=>"category list found",
				  'details'=>$data['category_list']
				);
		}
		else
		{
			$resultarray=array(
			  'flag'=>"false", 
			  'status'=>"501",						  
			  'message'=>'No data Found'
			);
		}
		echo json_encode($resultarray);
	}
	
	public function notificationList(request $request)
	{
		/* search items */
		$user_id=$request->input('user_id');
		$start=$request->input('start');
        $limit=$request->input('limit');
		/* search items */
		if($user_id=='')
		{
			$resultarray=array(
			'flag'=>"false",
			'status'=>"501",
			'message'=>'Missing Parameters'
			);
			echo json_encode($resultarray);
			return;
		}
		$User = User::where('id', $user_id)->first();
		if($User)
		{
			if (is_null($limit)) 
			{
				$data['notification_list'] = NotificationDetail::where('user_id', $user_id)->where('is_deleted','=','0')->get();
			}
			else
			{
				$data['notification_list'] = NotificationDetail::where('user_id', $user_id)->where('is_deleted','=','0')->limit($limit)->offset($start)->get();
			}
			/*$data['notification_list'] = NotificationDetail::where('user_id', $user_id)->where('is_deleted','=','0')->get();*/
		}
		else
		{
			$resultarray=array(
			'flag'=>"false",
			'status'=>"501",
			'message'=>'User not found'
			);
			echo json_encode($resultarray);
			return;
		}
		
		if(count($data['notification_list'])>0)
		{
			for($i=0; $i<count($data['notification_list']); $i++)
			{
				$data['notification_list'][$i]['notification'] = Notification::where('id', $data['notification_list'][$i]['notification_id'])->first();
			}
			$resultarray=array(
				  'flag'=>"true",              
				  'status'=>"200",              
				  'message'=>"Notification list found",
				  'details'=>$data['notification_list']
				);
		}
		else
		{
			$resultarray=array(
			  'flag'=>"false", 
			  'status'=>"501",						  
			  'message'=>'No data Found'
			);
		}
		echo json_encode($resultarray);
	}
	
	public function updateUnreadNotificationToRead(request $request)
	{
		/* search items */
		$user_id			=	$request->input('user_id');
		$notification_id	=	$request->input('notification_id');
		/* search items */
		if($user_id=='' || $notification_id=='')
		{
			$resultarray=array(
			'flag'=>"false",
			'status'=>"501",
			'message'=>'Missing Parameters'
			);
			echo json_encode($resultarray);
			return;
		}
		else
		{
			$notification = NotificationDetail::where('user_id', $user_id)->where('notification_id', $notification_id)->first();
			if($notification)
			{
				$notification->is_read = '1';
				$notification->save(); 
				
				$resultarray=array(
				  'flag'=>"true",      
				  'status'=>"200", 		  
				  'message'=>'Notification read successfully'
				);
			}
			else
			{
				$resultarray=array(
				  'flag'=>"false",      
				  'status'=>"501", 		  
				  'message'=>'No data found'
				);
			}	
		}
		echo json_encode($resultarray);
	}
	
	
	public function deleteNotifications(request $request)
	{
		/* search items */
		$user_id			=	$request->input('user_id');
		/* search items */
		if($user_id=='')
		{
			$resultarray=array(
			'flag'=>"false",
			'status'=>"501",
			'message'=>'Missing Parameters'
			);
			echo json_encode($resultarray);
			return;
		}
		else
		{
			$notification = NotificationDetail::where('user_id', $user_id)->where('is_deleted', '=', '0')->get();
			if(count($notification)>0)
			{
				for($i=0; $i<count($notification); $i++)
				{
					//echo $notification[$i]; 
					$notification[$i]->is_deleted = '1';
					$notification[$i]->save();
				}	
				$resultarray=array(
				  'flag'=>"true",      
				  'status'=>"200", 		  
				  'message'=>'Notification cleared successfully'
				);
				
			}
			else
			{
				$resultarray=array(
				  'flag'=>"false",      
				  'status'=>"501", 		  
				  'message'=>'No data found'
				);
			}	
		}
		echo json_encode($resultarray);
	}
	
	
	public function unreadNotificationCount(request $request)
	{
		/* search items */
		$user_id			=	$request->input('user_id');
		/* search items */
		if($user_id=='')
		{
			$resultarray=array(
			'flag'=>"false",
			'status'=>"501",
			'message'=>'Missing Parameters'
			);
			echo json_encode($resultarray);
			return;
		}
		else
		{
			$notification = NotificationDetail::where('user_id', $user_id)->where('is_read', '=', '0')->where('is_deleted', '=', '0')->get();
			if(count($notification)>0)
			{
				$resultarray=array(
				  'flag'=>"true",      
				  'status'=>"200", 		  
				  'message'=>'Notification count found',
				  'unread_notification_count'=>count($notification)
				);
				
			}
			else
			{
				$resultarray=array(
				  'flag'=>"false",      
				  'status'=>"501", 		  
				  'message'=>'No data found'
				);
			}	
		}
		echo json_encode($resultarray);
	}	
		

}