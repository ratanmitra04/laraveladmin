<?php
/***********************************************/
# Company Name    :                             
# Author          : KB                            
# Created Date    :                               
# Controller Name : DashboardController               
# Purpose         : Dashboard features, user pa             
/***********************************************/

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use \Validator;
use \Hash;
use \Redirect;
use App\User;
use App\City;
use Exception;
use GlobalVars;
use App\Business;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        // echo $d = \Helpers::encrypt(4);
        // echo \Helpers::decrypt($d);
        try{
            $data['title']              =   'Dashboard';
            $data['js_array']           =   array('morris.min.js','raphael.min.js','dashboard.js');

            // $contactCount               =   Contact::count();
            // $data['contact_count']      =   $contactCount;

            // $clienteleCount             =   Clientele::count();
            // $data['clientele_count']    =   $clienteleCount;

            // $testimonialCount           =   Testimonial::count();
            // $data['testimonial_count']  =   $testimonialCount;

            // $jobPostingCount            =   JobPosting::count();
            // $data['job_posting_count']  =   $jobPostingCount;
			
			$data = Business::where('id','!=','')->get();
			$data['business_count']=COUNT($data);
			
			$dataFeatured = Business::where('featured','1')->get();
			$data['featuredbusiness_count'] = COUNT($dataFeatured);
			
			$Customer = User::where('id','!=','1')->where('user_type','CU')->get();
			$data['customers']=COUNT($Customer); 
			
            //$data['contact_count'] = 0;
            $data['clientele_count'] = 0;
            $data['testimonial_count'] = 0;
            $data['job_posting_count'] = 0;
			
			
			$data['city'] = City::where('id','!=','')->orderby('city_name', 'ASC')->get();
			
			$data['customerCount']=DB::table('users')->select(DB::raw('COUNT(*) as total,date(created_at) as pp'))
			->whereRaw('created_at > now() - INTERVAL 7 day')
			->where('user_type','CU')
			->groupby ('pp')
			->get();
			
			$start=0;
			$limit=20;
			$data['topClickedBusinesses'] = Business::where('approved_by_admin','1')->where('status','Active')->orderby('top_clicked', 'DESC')->limit($limit)->offset($start)->get();
			
            return view('admin.adminuser.dashboard', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }       
    }
	
	
	public function getCityWiseBusiness()
	{
		// Fetch Business by City id
		$start=0;
		$limit=20;
		
		$city_id=$_REQUEST['city_id'];
		$fromdate=$_REQUEST['fromdate'];
		$todate=$_REQUEST['todate'];
			
		$topClickedBusinesses = Business::where('approved_by_admin','1')->where('status','Active');
		
		if(!empty($city_id))
		{
			$topClickedBusinesses = $topClickedBusinesses->where('city',$city_id);
		}
		if(!empty($fromdate))
		{
			$topClickedBusinesses = $topClickedBusinesses->where('created_at','>=',$fromdate.' 00:00:00');
		}
		if(!empty($todate))
		{
			$topClickedBusinesses = $topClickedBusinesses->where('created_at','<=',$todate.' 23:59:59');
		}
		$topClickedBusinesses = $topClickedBusinesses->orderby('top_clicked', 'DESC')->limit($limit)->offset($start)->get();
		
		$jsonDAta = [];
		$colorCode=['#b87333','#8000ff','#ffff00','#e5e4e2','#ff8000','#ff00ff','#b87333','silver','#bf00ff','#40ff00','#b87333','#ff4000','#ffbf00','#00bfff','#ff00bf','#4000ff','#e5e4e2','#ff00ff','#8000ff','#e5e4e2'];
		$jsonDAtaBusiness=[];
		$i=0;
		foreach($topClickedBusinesses as $value)
		{
			
			$businessName=$value['business_name'];
			if(empty($value['top_clicked']))
			{
				$noOfClick=0;
			}
			else
			{
				$noOfClick=$value['top_clicked'];
			}
			$jsonDAtaBusiness[]=[$businessName,$noOfClick,$colorCode[$i]];
			$i++;
		}
		$response['rec'] = $jsonDAtaBusiness;
		echo json_encode($response);
		exit;
	}
	
	
	/**
        * Function Name :  getCityWiseCustomers
        * Purpose       :  This function is use for on change get city realated Customers Count.
        * Author        :
        * Created Date  : 14-01-2021
        * Modified date :          
        * Input Params  :  \Illuminate\Http\Request $request
        * Return Value  :  void
    */
	public function getCityWiseCustomers($id)
	{
		// Fetch Customers by City id
		$customerCount=DB::table('users')->select(DB::raw('COUNT(*) as total,date(created_at) as pp'))
			->whereRaw('created_at > now() - INTERVAL 7 day')
			->where('user_type','CU')
			->where('city_id',$id)
			->groupby ('pp')
			->orderby('created_at', 'DESC')->get();
		$colorCode=['#00ffff','#8000ff','#ffff00','#e5e4e2','#ff8000','#ff00ff','#b87333','silver','#bf00ff','#40ff00'];
		//$jsonDAta='';
		$totalCustomer=0;
		$jsonDAta = [];
		$noOfCustomers='';
		$currentDate=DATE('Y-m-d');
		for($i=1;$i<=7; $i++)
		{
			$prevDate=date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
			if(COUNT($customerCount)>0){
				foreach($customerCount as $value)
				{
					$noOfCustomers=0;
					if($value->pp == $currentDate)
					{
						$noOfCustomers=$value->total;
						break;
					}
				}
			}
			else
			{
				$noOfCustomers=0;
			}
			$totalCustomer=$totalCustomer+$noOfCustomers;
			$jsonDAta[] = [date('d/m/Y',strtotime($currentDate)),$noOfCustomers,$colorCode[$i-1]];			
			//$jsonDAta.='["'.date('d/m/Y',strtotime($currentDate)).'",'.$noOfCustomers.',"'.$colorCode[$i-1].'"]';
			$currentDate=$prevDate;
		}
	 
		$response['rec'] = $jsonDAta;
		$response['totalCustomer'] = $totalCustomer;
		echo json_encode($response);
		exit;
	}

   
    /**
        * Function Name :  change_password
        * Purpose       :  This function is use for change password of a user.
        * Author        :
        * Created Date  : 18-03-2019
        * Modified date :          
        * Input Params  :  \Illuminate\Http\Request $request
        * Return Value  :  void
    */

    public function change_password(Request $request) {
        try{
            $data['title']      = 'Change Password';
            if ($request->isMethod('post')) {
                $Validator = Validator::make($request->all(), [
                    'old_password'      => 'required',
                    'new_password'      => 'required|min:6',
                    'confirm_password'  => 'required|same:new_password'
                ]);

                if ($Validator->fails()) {
                    return \Redirect::route('admin_change_password')->withErrors($Validator);
                } else {
                    $user = User::find(auth()->guard('admin')->user()->id);
                    if ((Hash::check($request->input('old_password'), $user->password))) {
                        if (!(Hash::check($request->input('new_password'), $user->password))) {
                            $user->password         = $request->input('new_password');
                            $user->save();
                            $request->session()->flash('success', 'Your password has been changed successfully.');
                            return \Redirect::Route('admin_change_password');                            
                        }else{
                            $request->session()->flash('error', 'Current password should not be same with the old password.');
                            return \Redirect::Route('admin_change_password');
                        }
                    } else {
                        $request->session()->flash('error', 'Current password is not matched with record.');
                        return \Redirect::Route('admin_change_password');
                    }
                }
            }
            return view('admin/adminuser.change_password', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }        
    }


}
