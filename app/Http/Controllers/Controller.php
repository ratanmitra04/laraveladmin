<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {	
    //     $routePrefix = ltrim(\Route::getCurrentRoute()->getPrefix(),'/');
    //     list($prefix) = explode('/', $routePrefix);
    //    // if($prefix=='control-panel'){
    //         $routeArray = \Route::getCurrentRoute()->getAction();   
    //         $controllerAction = class_basename($routeArray['controller']);
    //         list($controller, $action) = explode('@', $controllerAction);
    //         $this->helpData = Help::where(['controller_name' => $controller, 'method_name' => $action])->first();
    //         \View::share(['helpData' => $this->helpData]);
    //    // }
    }     
}
