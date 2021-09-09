<?php

namespace App\Http\Controllers\api;

use \App\Category;
use \App\SubCategory;
use \App\Tags;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiHelper;
use \Auth;
use \Response;
use \Helper;
use \Validator;
use \Hash;
use Image;
use App;

class CategoryController extends Controller
{
    /**
        * Function Name :  getCategoryList
        * Purpose       :  Fetch CategoryList.
        * Author        :
        * Created Date  :  2020-11-03
        * Input Params  :  
        * Return Value  :  flag,details
	*/  
	public function getCategoryList(request $request)  
	{  
		$data['category_info'] = Category::orderby('category_name', 'ASC')->get();
		if(count($data['category_info'])>0){
					$resultarray=array(
					  'flag'=>"true",              
					  'details'=>$data['category_info']
					);
		}else{
					$resultarray=array(
					  'flag'=>"false",              
					  'details'=>'No data Found'
					);
		}
		echo json_encode($resultarray);
	}
	
	/**
        * Function Name :  getSubCategorybyCategoryId
        * Purpose       :  Fetch SubCategory.
        * Author        :
        * Created Date  :  2020-11-03
        * Input Params  :  id
        * Return Value  :  flag,details
	*/ 
	public function getSubCategorybyCategoryId(request $request)  
	{
		$id=$request->input('category_id');
		
		if($id=='')
		{
			$resultarray=array(
			  'flag'=>"false",              
			  'details'=>'Missing Parameters'
			);
		}
		else
		{
			$data['subcategory_info'] = SubCategory::where('category_id',$id)->orderby('subcategory_name', 'ASC')->get();
			if(count($data['subcategory_info'])>0){
						$resultarray=array(
						  'flag'=>"true",              
						  'details'=>$data['subcategory_info']
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


	/**
        * Function Name :  addCatagorySubCatagoryByName
        * Purpose       :  Add Catagory With Respective SubCategory.
        * Author        :
        * Created Date  :  2020-12-01
        * Input Params  :  id
        * Return Value  :  flag,details
	*/ 
	public function addCatagorySubCatagoryByName(request $request)  
	{
		$category_name=$request->input('category_name');
		$sub_category_name=$request->input('sub_category_name');

		if (empty($category_name) || empty($sub_category_name)) {
			# code...
			$resultarray=array(
						  'flag'=>"false", 
						  'status_code'=>"100",             
						  'details'=>'parameter missing'
						);
			echo json_encode($resultarray);
			return;

		}else{

			$chk_cat_name = Category::where('category_name','=',$category_name)->get();

			if (count($chk_cat_name)>0) {
				# code...
				$resultarray=array(
						  'flag'=>"false",
						  'status_code'=>"101",             
						  'details'=>'Catagory name already there please select that.'
						);
			echo json_encode($resultarray);
			return;
			}else{
				$insert_query=Category::insert(['category_name'=>$category_name]);

				if ($insert_query) {
					# code...
					$get_cat = Category::where('category_name',$category_name)->get();

					$insert_sub_cat_query=SubCategory::insert(['category_id'=>$get_cat[0]->id,'subcategory_name'=>$sub_category_name]);

					if ($insert_sub_cat_query) {
						# code...
						$get_sub_cat=SubCategory::where('category_id',$get_cat[0]->id)->where('subcategory_name',$sub_category_name)->get();

						$resultarray=array(
						  'flag'=>"true",
						  'status_code'=>"200",
						  'catagory_id'=>$get_cat[0]->id,
						  'sub_catagory_id'=>$get_sub_cat[0]->id,             
						  'details'=>'Successfully Inserted'
						);
			            echo json_encode($resultarray);
					}else{
						$resultarray=array(
						  'flag'=>"false",
						  'status_code'=>"102",             
						  'details'=>'can not inserted sub catagory'
						);
			            echo json_encode($resultarray);

					}

				}else{
					$resultarray=array(
						  'flag'=>"false",
						  'status_code'=>"103",             
						  'details'=>'can not inserted catagory'
						);
			            echo json_encode($resultarray);
				}

			}

		}

	}

	/**
        * Function Name :  addTags
        * Purpose       :  Add Tags which have to be an array and each array->object will be store on 
        				   tags table(N.B- the tag array will add in business(tag) field) .
        * Author        :
        * Created Date  :  2020-12-01
        * Input Params  :  id
        * Return Value  :  flag,details
	*/ 
	public function addTags(request $request)  
	{
		$tags=$request->input('tags');
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
			            echo json_encode($resultarray);

			}else{
				$resultarray=array(
						  'flag'=>"true",
						  'status_code'=>"101",             
						  'message'=>"tags not inserted."
						);
			            echo json_encode($resultarray);

			}
		}else{
			$resultarray=array(
						  'flag'=>"false",
						  'status_code'=>"100",             
						  'message'=>"parameter is not an array or empty"
						);
			            echo json_encode($resultarray);

		}

	}

	/**
        * Function Name :  listTags
        * Purpose       :  List tags .
        * Author        :
        * Created Date  :  2020-12-02
        * Input Params  :  id
        * Return Value  :  flag,details
	*/ 
	public function listTags(request $request)  
	{
		$key=$request->input('key');
		$start=$request->input('start');
          $limit=$request->input('limit');
		
			# code...
			if (is_null($limit)) {
            # code...
            $data = Tags::get();
                              
                            
          }else{

                 $data = Tags::where('tag_name','LIKE','%'.$key.'%')->limit($limit)->offset($start)->orderby('id', 'ASC')->get();
                            
          }
			
		
		
		if (count($data)>0) {
			# code...
			$resultarray=array(
						  'flag'=>"true",
						  'status_code'=>"200",
						  'key'=>$key,             
						  'list'=>$data
						);
			            echo json_encode($resultarray);
		}else{
			$resultarray=array(
						  'flag'=>"false",
						  'status_code'=>"100",             
						  'message'=>"no data found"
						);
			            echo json_encode($resultarray);

		}
	}

	
	/**
        * Function Name :  Others Case addCatagory and SubCatagory
        * Purpose       :  Add Catagory With Respective SubCategory.
        * Author        :
        * Created Date  :  18-01-2021
        * Input Params  :  id
        * Return Value  :  flag,details
	*/ 
	public function InCaseOthersaddCatagorySubCatagoryInApp(request $request)  
	{
		$category_name=$request->input('category_name');
		$sub_category_name=$request->input('sub_category_name');
		
		//$datesent 	= now()->timestamp;
		$datesent 	= now();
		if (empty($category_name) || empty($sub_category_name)) {
			# code...
			$resultarray=array(
						  'flag'=>"false", 
						  'status_code'=>"100",             
						  'details'=>'parameter missing'
						);
			echo json_encode($resultarray);
			return;

		}else{

			$chk_cat_name = Category::where('category_name','=',$category_name)->get();

			if (count($chk_cat_name)>0) {
				# code...
				$resultarray=array(
						  'flag'=>"false",
						  'status_code'=>"101",             
						  'details'=>'Catagory name already there please select that.'
						);
			echo json_encode($resultarray);
			return;
			}else{
				$insert_query=Category::insert(['category_name'=>strtolower($category_name),'created_at'=>$datesent]);

				if ($insert_query) {
					# code...
					$get_cat = Category::where('category_name',strtolower($category_name))->get();

					$insert_sub_cat_query=SubCategory::insert(['category_id'=>$get_cat[0]->id,'subcategory_name'=>strtolower($sub_category_name)]);

					if ($insert_sub_cat_query) {
						# code...
						$get_sub_cat=SubCategory::where('category_id',$get_cat[0]->id)->where('subcategory_name',strtolower($sub_category_name))->get();

						$resultarray=array(
						  'flag'=>"true",
						  'status_code'=>"200",
						  'catagory_id'=>$get_cat[0]->id,
						  'catagory_name'=>strtolower($category_name),
						  'sub_catagory_id'=>$get_sub_cat[0]->id,  
						  'sub_catagory_name'=>strtolower($sub_category_name),	
						  'details'=>'Successfully Inserted'
						);
			            echo json_encode($resultarray);
					}else{
						$resultarray=array(
						  'flag'=>"false",
						  'status_code'=>"102",             
						  'details'=>'can not inserted sub catagory'
						);
			            echo json_encode($resultarray);

					}

				}else{
					$resultarray=array(
						  'flag'=>"false",
						  'status_code'=>"103",             
						  'details'=>'can not inserted catagory'
						);
			            echo json_encode($resultarray);
				}

			}

		}

	}

}
