<?php

namespace App\Traits;

use GlobalVars;
use App\Activities;
use App\User;

trait GeneralMethods
{

    public function assignBreadcrumb()
    {
        $this->breadcrumb = $breadcrumb = [
            'LISTPAGE' =>
            [
                ['label' => $this->management . ' List', 'url' => 'THIS']
            ],
            'CREATEPAGE' =>
            [
                ['label' => $this->management . ' List', 'url' => \URL::route($this->listUrl)],
                ['label' => 'Add', 'url' => 'THIS']
            ],
            'EDITPAGE' =>
            [
                ['label' => $this->management . ' List', 'url' => \URL::route($this->listUrl)],
                ['label' => 'Edit', 'url' => 'THIS']
            ],
            'VIEWPAGE' =>
            [
                ['label' => $this->management . ' List', 'url' => \URL::route($this->listUrl)],
                ['label' => $this->management . ' View', 'url' => 'THIS']
            ],
            'CHANGEPASSWORDPAGE' =>
            [
                ['label' => $this->management . ' List', 'url' => \URL::route($this->listUrl)],
                ['label' => $this->management . ' Change Password', 'url' => 'THIS']
            ],
			'MODERATORACTIVITY' =>
            [
                ['label' => $this->management . ' List', 'url' => \URL::route($this->listUrl)],
                ['label' => $this->management . ' Activity', 'url' => 'THIS']
            ]
        ];
    }

    public function assignShareVariables()
    {
        \View::share([
            'management'    => $this->management,
            'modelName'     => $this->modelName,
            'breadcrumb'    => $this->breadcrumb,
            'routePrefix'   => $this->routePrefix,
            'urlPrefix'     => isset($this->urlPrefix) ? $this->urlPrefix : '',
            'controllerName' => $this->controllerName
        ]);
        // Declare variables as per current method
        if (\Route::current()->getActionMethod() == 'index') {
            \View::share(['pageType' => 'List']);
        } elseif (\Route::current()->getActionMethod() == 'add') {
            \View::share(['pageType' => 'Add']);
        } elseif (\Route::current()->getActionMethod() == 'edit') {
            \View::share(['pageType' => 'Edit']);
        } elseif (\Route::current()->getActionMethod() == 'import' || \Route::current()->getActionMethod() == 'storecsv') {
            \View::share(['pageType' => 'List']);
        } elseif (\Route::current()->getActionMethod() == 'view') {
            \View::share(['pageType' => 'View']);
        } elseif (\Route::current()->getActionMethod() == 'changePassword') {
            \View::share(['pageType' => 'Change Password']);
        } elseif (\Route::current()->getActionMethod() == 'activity') {
            \View::share(['pageType' => 'Activity']);
        }
    }

	
	public function createLog($actionBy,$management_id,$action_id,$action_for,$name_action_for)
	{
		$activity                      = new Activities;
		
		$activity->action_by       	   = $actionBy;
		$activity->management_id       = $management_id;
		$activity->action_id           = $action_id;
		$activity->action_for          = $action_for;
		$activity->name_action_for     = $name_action_for;	
		
		$activity->save();
		
	}
}
