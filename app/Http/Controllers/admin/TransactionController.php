<?php
/***********************************************/
# Company Name    :                             
                        
# Created Date    :                                
# Controller Name : TransactionController                 
# Purpose         : Transaction Management             
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

class TransactionController extends Controller
{
    
    use GeneralMethods;
    public $controllerName  = 'Transaction';
    public $management;
    public $modelName       = '';
    public $breadcrumb;
    public $routePrefix     = 'transaction';
    public $listUrl         = 'transaction.list';
    public $viewFolderPath  = 'admin/transaction';
    
    public function __construct()
    {
        parent::__construct();
        $this->management      = 'Transaction';
        // Begin: Assign breadcrumb for view pages
        $this->assignBreadcrumb();
        // End: Assign breadcrumb for view pages
        // Variables assign for view page
        $this->assignShareVariables();
        
    }
    
    public function index(Request $request)
    {        
        try{
            $data['keyword']        = '';
            $data['records']        = '';
            return view($this->viewFolderPath.'.list', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    }

     public function view($id)
    {
        try{ 
            $data['id'] = $id;           
            return view($this->viewFolderPath.'.view', $data);
        }catch(Exception $e){
            throw new \App\Exceptions\AdminException($e->getMessage());
        }
    } 

     public function add()
    {
        return view($this->viewFolderPath.'.add');
    }
}