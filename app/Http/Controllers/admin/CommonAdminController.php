<?php
/***********************************************/
# Company Name    :                             
# Author          : KB                            
# Created Date    :                                
# Controller Name : CommonWebController               
# Purpose         : Use for common functions which are accessible throughout all the controllers             
/***********************************************/

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;
use Response;
use Image;
use DB;
use GlobalVars;
use Exception;

class CommonAdminController extends Controller
{
    /*
        * Function Name :  statusChange 
        * Purpose       :  This function is use for change the record status. 
        * Input Params  :  request $request  
        * Return Value  :  return string
   */
    public function statusChange(Request $request)
    {
        try{
            $id     = $request->id;
            $model  = $request->model;
            $model  = "\App\\".$model;
            $record = $model::find($id);
            if(!$record){
                throw new Exception("No result was found for id: $id");
            }
            if ($record->status == GlobalVars::ACTIVE_STATUS) {
                $record->status = GlobalVars::INACTIVE_STATUS;
                $status = '<span class="fa fa-close text-red"></span>';
            } else {
                $record->status = GlobalVars::ACTIVE_STATUS;
                $status = '<span class="fa fa-check-square-o"></span>';
            }
            $record->save();
            echo $status;
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }

    /**
        * Function Name :  downloadImage
        * Purpose       :  This function used for downloading an image.
        * Author        :
        * Created Date  : 02/08/2019
        * Modified date :          
        * Input Params  :  $id[integer], $token[integer]
        * Return Value  :  void
    */

    public function downloadImage(Request $request){
        $filepath = public_path('admin/images/itemimages/main/').$request->name;
        return Response::download($filepath);
    }  
    

}
