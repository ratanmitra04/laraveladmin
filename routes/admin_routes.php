<?php

Route::group(['prefix' => 'da-admin', 'namespace' => 'admin'], function () {
    Route::get('/', ['as' => 'admin_login',     'uses' => 'LoginController@index']);
    Route::post('/dologin', ['as' => 'do_admin_login', 'uses' => 'LoginController@dologin']);
    Route::any('/forgot-password', array('as' => 'admin_forgot_password',   'uses' => 'LoginController@forgot_password'));
    Route::get('/reset-password/{token}', array('as' => 'admin_reset_newpassword', 'uses' => 'LoginController@resetPassword'));
    Route::post('/reset-password/{token}', array('as' => 'admin_password_update', 'uses' => 'LoginController@updatePassword'));
});

//******* Routes without login End *********//

//******* Routes with login Start *********//

Route::group(['prefix' => 'da-admin', 'namespace' => 'admin', 'middleware' => 'admin'], function () {

    Route::any('/dashboard', array('as' => 'dashboard',                  'uses' => 'DashboardController@index'));
    Route::get('/logout', array('as' => 'admin_logout',                  'uses' => 'LoginController@logout'));
    Route::any('/change-password', array('as' => 'admin_change_password', 'uses' => 'DashboardController@change_password'));
    Route::post('/statusChange', ['as' => 'statusChange',                'uses' => 'CommonAdminController@statusChange']);
    Route::get('/downloadImage/{name}', ['as' => 'downloadImage',       'uses' => 'CommonAdminController@downloadImage']);
    Route::any('/update-profile', array('as' => 'admin_update_profile', 'uses' => 'UserController@updateAdminProfile'));
	Route::get('/getCityWiseCustomers/{id}', 'DashboardController@getCityWiseCustomers');
	Route::get('/getCityWiseBusiness', 'DashboardController@getCityWiseBusiness');
    // Setting routes Start
    Route::group(['prefix' => 'settings'], function () {
        Route::get('/',             ['as' => 'settings.edit',                  'uses' =>  'SettingController@edit']);
        Route::post('/edit/{id}',   ['as' => 'settings.update',                'uses' =>  'SettingController@update']);
    });
    // Setting routes End
	
	// App Setting routes Start
    Route::group(['prefix' => 'appsettings'], function () {
        Route::get('/',             ['as' => 'appsettings.edit',                  'uses' =>  'AppsettingController@edit']);
        Route::post('/edit/{id}',   ['as' => 'appsettings.update',                'uses' =>  'AppsettingController@update']);
    });
    // App Setting routes End
    
    
    // Teachers routes Start
    Route::group(['prefix' => 'teachers'], function () {
        Route::get('/',             ['as' => 'teachers.list',                  'uses' =>  'TeacherController@index']);
        Route::get('/add',          ['as' => 'teachers.add',                   'uses' =>  'TeacherController@add']);
        Route::post('/add',         ['as' => 'teachers.store',                 'uses' =>  'TeacherController@store']);
        Route::get('/edit/{id}',    ['as' => 'teachers.edit',                  'uses' =>  'TeacherController@edit']);
        Route::post('/edit/{id}',   ['as' => 'teachers.update',                'uses' =>  'TeacherController@update']);
        // Route::post('/statusChange', ['as' => 'users.status',                'uses' =>  'TeacherController@statusChange']);
        Route::post('/delete/{id}', ['as' => 'teachers.delete',                'uses' =>  'TeacherController@delete']);
        Route::get('/change-user-password/{id}', ['as' => 'teachers.changeUserPassword',   'uses' =>  'TeacherController@changePassword']);
        Route::post('/change-user-password/{id}', ['as' => 'teachers.updateUserPassword',   'uses' =>  'TeacherController@updatePassword']);
        // Route::post('/get-users-dropdown', ['as' => 'users.getUsersDropdown',    'uses' =>  'UserController@getUserDropDownByType']);

        Route::get('/view/{id}', ['as' => 'teachers.view',               'uses' =>  'TeacherController@view']);

    });
    // Teachers routes End
	
	// Moderator routes Start
    Route::group(['prefix' => 'moderators'], function () {
        Route::get('/',             ['as' => 'moderators.list',    'uses' =>  'ModeratorController@index']);
        Route::get('/add',          ['as' => 'moderators.add',     'uses' =>  'ModeratorController@add']);
        Route::post('/add',         ['as' => 'moderators.store',   'uses' =>  'ModeratorController@store']);
        Route::get('/view/{id}',    ['as' => 'moderators.view',    'uses' =>  'ModeratorController@view']);
		Route::get('/edit/{id}',    ['as' => 'moderators.edit',    'uses' =>  'ModeratorController@edit']);
        Route::post('/edit/{id}',   ['as' => 'moderators.update',  'uses' =>  'ModeratorController@update']);
        Route::post('/statusChange', ['as' => 'moderators.status', 'uses' =>  'ModeratorController@statusChange']);
        Route::post('/delete/{id}', ['as' => 'moderators.delete',  'uses' =>  'ModeratorController@delete']);
		Route::get('/export',['as' => 'moderators.export', 'uses' =>  'ModeratorController@export']);
		Route::get('/activity/{id}', ['as' => 'moderators.activity', 'uses' =>  'ModeratorController@activity']);

    });
    // Moderator routes End

    // Users routes Start
    Route::group(['prefix' => 'users'], function () {
        Route::get('/',             ['as' => 'users.list',                  'uses' =>  'UserController@index']);
        Route::get('/add',          ['as' => 'users.add',                   'uses' =>  'UserController@add']);
        Route::post('/add',         ['as' => 'users.store',                 'uses' =>  'UserController@store']);
        Route::get('/view/{id}',    ['as' => 'users.view',               	'uses' =>  'UserController@view']);
		Route::get('/edit/{id}',    ['as' => 'users.edit',                  'uses' =>  'UserController@edit']);
        Route::post('/edit/{id}',   ['as' => 'users.update',                'uses' =>  'UserController@update']);
        Route::post('/statusChange', ['as' => 'users.status',                'uses' =>  'UserController@statusChange']);
        Route::post('/delete/{id}', ['as' => 'users.delete',                'uses' =>  'UserController@delete']);
        Route::get('/change-user-password/{id}', ['as' => 'users.changeUserPassword',   'uses' =>  'UserController@changePassword']);
        Route::post('/change-user-password/{id}', ['as' => 'users.updateUserPassword',   'uses' =>  'UserController@updatePassword']);
        Route::post('/get-users-dropdown', ['as' => 'users.getUsersDropdown',    'uses' =>  'UserController@getUserDropDownByType']);
		
		Route::get('/export',['as' => 'users.export', 'uses' =>  'UserController@export']);
		Route::get('/getAreas/{id}', 'UserController@getAreas');

    });
    // Users routes End


    // CMS routes Start
    Route::group(['prefix' => 'cms'], function () {
        Route::get('/',             ['as' => 'cms.list',                  'uses' =>  'CmsController@index']);
        Route::get('/view/{id}',    ['as' => 'cms.view',                'uses' =>  'CmsController@view']);
		Route::get('/edit/{id}',    ['as' => 'cms.edit',                  'uses' =>  'CmsController@edit']);
        Route::post('/edit/{id}',   ['as' => 'cms.update',                'uses' =>  'CmsController@update']);
    });
    // CMS routes End   

    // CMS routes Start
    Route::group(['prefix' => 'cmscontents'], function () {
        Route::get('/',             ['as' => 'cmscontents.list',                  'uses' =>  'CmsContentController@index']);
        Route::get('/edit/{id}',    ['as' => 'cmscontents.edit',                  'uses' =>  'CmsContentController@edit']);
        Route::post('/edit/{id}',   ['as' => 'cmscontents.update',                'uses' =>  'CmsContentController@update']);
    });
    // CMS routes End  

    // Country routes Start
    Route::group(['prefix' => 'countries' ], function () {
        Route::get('/',              ['as' => 'countries.list',                 'uses' =>  'CountryController@index']);
         Route::get('/add',          ['as' => 'countries.add',                  'uses' =>  'CountryController@add']);
         Route::post('/add',         ['as' => 'countries.store',                'uses' =>  'CountryController@store']);
         Route::get('/edit/{id}',    ['as' => 'countries.edit',                 'uses' =>  'CountryController@edit']);
         Route::post('/edit/{id}',   ['as' => 'countries.update',               'uses' =>  'CountryController@update']);
         Route::post('/delete/{id}', ['as' => 'countries.delete',               'uses' =>  'CountryController@delete']);   
    });
    // Country routes End
	
	// City routes Start
    Route::group(['prefix' => 'cities' ], function () {
		 Route::get('/',             ['as' => 'cities.list',      'uses' =>  'CityController@index']);
		 Route::get('/view/{id}',    ['as' => 'cities.view',       'uses' =>  'CityController@view']);
         Route::get('/add',          ['as' => 'cities.add',       'uses' =>  'CityController@add']);
         Route::post('/add',         ['as' => 'cities.store',     'uses' =>  'CityController@store']);
         Route::get('/edit/{id}',    ['as' => 'cities.edit',      'uses' =>  'CityController@edit']);
         Route::post('/edit/{id}',   ['as' => 'cities.update',    'uses' =>  'CityController@update']);
         Route::post('/delete/{id}', ['as' => 'cities.delete',    'uses' =>  'CityController@delete']);   
    });
    // City routes End
	
	// Area routes Start
    Route::group(['prefix' => 'locations' ], function () {
		 Route::get('/',             ['as' => 'locations.list',      'uses' =>  'LocationController@index']);
		 Route::get('/view/{id}',    ['as' => 'locations.view',      'uses' =>  'LocationController@view']);
         Route::get('/add',          ['as' => 'locations.add',       'uses' =>  'LocationController@add']);
         Route::post('/add',         ['as' => 'locations.store',     'uses' =>  'LocationController@store']);
         Route::get('/edit/{id}',    ['as' => 'locations.edit',      'uses' =>  'LocationController@edit']);
         Route::post('/edit/{id}',   ['as' => 'locations.update',    'uses' =>  'LocationController@update']);
         Route::post('/delete/{id}', ['as' => 'locations.delete',    'uses' =>  'LocationController@delete']);   
    });
    // Area routes End
    
   // Business routes Start
    Route::group(['prefix' => 'business'], function () {
        Route::get('/',             ['as' => 'business.list',                  'uses' =>  'BusinessController@index']);
        Route::get('/edit/{id}',    ['as' => 'business.edit',                  'uses' =>  'BusinessController@edit']);
        Route::post('/edit/{id}',   ['as' => 'business.update',                'uses' =>  'BusinessController@update']);
        Route::get('/view/{id}', ['as' => 'business.view',               'uses' =>  'BusinessController@view']);
        Route::get('/add',          ['as' => 'business.add',                   'uses' =>  'BusinessController@add']);
		Route::post('/delete/{id}', ['as' => 'business.delete',                'uses' =>  'BusinessController@delete']);
		
		Route::get('/getAreas/{id}', 'BusinessController@getAreas');
		Route::get('/getSubCategory/{id}', 'BusinessController@getSubCategory');
		Route::post('/imageUpload/{id}', 'BusinessController@imageUpload');
		Route::post('/imageDelete/{id}/{businessid}', 'BusinessController@imageDelete');
		Route::post('/businessApproved/{id}', 'BusinessController@businessApproved');
		Route::post('/businessRejected/{id}', 'BusinessController@businessRejected');
		
		Route::get('/export',['as' => 'business.export', 'uses' =>  'BusinessController@export']);
    });
    // Business routes End   
	
	// Subscription routes Start
		Route::group(['prefix' => 'subscription'], function () {
        Route::get('/',             ['as' => 'subscription.list',                  'uses' =>  'SubscriptionController@index']);
        Route::get('/add',          ['as' => 'subscription.add',                   'uses' =>  'SubscriptionController@add']);
        Route::post('/add',         ['as' => 'subscription.store',                 'uses' =>  'SubscriptionController@store']);
        Route::get('/view/{id}', 	['as' => 'subscription.view',               		'uses' =>  'SubscriptionController@view']);
		Route::get('/edit/{id}',    ['as' => 'subscription.edit',                  'uses' =>  'SubscriptionController@edit']);
        Route::post('/edit/{id}',   ['as' => 'subscription.update',                'uses' =>  'SubscriptionController@update']);
        Route::post('/statusChange', ['as' => 'subscription.status',                'uses' =>  'SubscriptionController@statusChange']);
        Route::post('/delete/{id}', ['as' => 'subscription.delete',                'uses' =>  'SubscriptionController@delete']);
       
    });
    // Subscription routes End
	
	// Advertise routes Start
		Route::group(['prefix' => 'advertise'], function () {
        Route::get('/',             ['as' => 'advertise.list',                  'uses' =>  'AdvertiseController@index']);
        Route::get('/add',          ['as' => 'advertise.add',                   'uses' =>  'AdvertiseController@add']);
        Route::post('/add',         ['as' => 'advertise.store',                 'uses' =>  'AdvertiseController@store']);
        Route::get('/view/{id}', 	['as' => 'advertise.view',               		'uses' =>  'AdvertiseController@view']);
		Route::get('/edit/{id}',    ['as' => 'advertise.edit',                  'uses' =>  'AdvertiseController@edit']);
        Route::post('/edit/{id}',   ['as' => 'advertise.update',                'uses' =>  'AdvertiseController@update']);
        Route::post('/statusChange', ['as' => 'advertise.status',                'uses' =>  'AdvertiseController@statusChange']);
        Route::post('/delete/{id}', ['as' => 'advertise.delete',                'uses' =>  'AdvertiseController@delete']);
       
    });
    // Advertise routes End
	
	// Transaction routes Start
		Route::group(['prefix' => 'transaction'], function () {
        Route::get('/',             ['as' => 'transaction.list',                  'uses' =>  'TransactionController@index']);
        Route::get('/add',          ['as' => 'transaction.add',                   'uses' =>  'TransactionController@add']);
        Route::post('/add',         ['as' => 'transaction.store',                 'uses' =>  'TransactionController@store']);
        Route::get('/view/{id}', 	['as' => 'transaction.view',               	'uses' =>  'TransactionController@view']);
		Route::get('/edit/{id}',    ['as' => 'transaction.edit',                  'uses' =>  'TransactionController@edit']);
        Route::post('/edit/{id}',   ['as' => 'transaction.update',                'uses' =>  'TransactionController@update']);
        Route::post('/statusChange', ['as' => 'transaction.status',                'uses' =>  'TransactionController@statusChange']);
        Route::post('/delete/{id}', ['as' => 'transaction.delete',                'uses' =>  'TransactionController@delete']);
       
    });
    // Transaction routes End
	
	// Review routes Start
		Route::group(['prefix' => 'review'], function () {
        Route::get('/',             ['as' => 'review.list',   'uses' =>  'ReviewController@index']);
        Route::get('/add',          ['as' => 'review.add',    'uses' =>  'ReviewController@add']);
        Route::post('/add',         ['as' => 'review.store',  'uses' =>  'ReviewController@store']);
        Route::get('/view/{id}', 	['as' => 'review.view',   'uses' =>  'ReviewController@view']);
		Route::get('/edit/{id}',    ['as' => 'review.edit',   'uses' =>  'ReviewController@edit']);
        Route::post('/edit/{id}',   ['as' => 'review.update', 'uses' =>  'ReviewController@update']);
        Route::post('/statusChange', ['as' => 'review.status','uses' =>  'ReviewController@statusChange']);
        Route::post('/delete/{id}', ['as' => 'review.delete', 'uses' =>  'ReviewController@delete']);
		
		Route::post('/reviewApproved/{id}', 'ReviewController@reviewApproved');
		Route::post('/reviewRejected/{id}', 'ReviewController@reviewRejected');
       
    });
    // Review routes End
	
	// Notification routes Start
		Route::group(['prefix' => 'notification'], function () {
        Route::get('/',             ['as' => 'notification.list',                  'uses' =>  'NotificationController@index']);
        Route::get('/add',          ['as' => 'notification.add',                   'uses' =>  'NotificationController@add']);
        Route::post('/add',         ['as' => 'notification.store',                 'uses' =>  'NotificationController@store']);
        Route::get('/view/{id}', 	['as' => 'notification.view',               	'uses' =>  'NotificationController@view']);
		Route::get('/edit/{id}',    ['as' => 'notification.edit',                  'uses' =>  'NotificationController@edit']);
        Route::post('/edit/{id}',   ['as' => 'notification.update',                'uses' =>  'NotificationController@update']);
        Route::post('/statusChange', ['as' => 'notification.status',                'uses' =>  'NotificationController@statusChange']);
        Route::post('/delete/{id}', ['as' => 'notification.delete',                'uses' =>  'NotificationController@delete']);
       
    });
    // Notification routes End

    // Category routes Start
    Route::group(['prefix' => 'category'], function () {

        Route::get('/',             ['as' => 'category.list', 'uses' =>  'CategoryController@index']);
        Route::get('/edit/{id}',    ['as' => 'category.edit',  'uses' =>  'CategoryController@edit']);
        Route::post('/edit/{id}',   ['as' => 'category.update', 'uses' =>  'CategoryController@update']);
        Route::get('/view/{id}', ['as' => 'category.view', 'uses' =>  'CategoryController@view']);
        Route::get('/add',          ['as' => 'category.add', 'uses' =>  'CategoryController@add']); 
        Route::post('/add',         ['as' => 'category.store',   'uses' =>  'CategoryController@store']);
		Route::post('/subcatRemoved/{id}', 'CategoryController@subcatRemoved');   
		Route::post('/delete/{id}', ['as' => 'category.delete',  'uses' =>  'CategoryController@delete']);		
    });
    // Category routes End   
	
	// Idea routes Start
    Route::group(['prefix' => 'ideas' ], function () {
        Route::get('/',              ['as' => 'ideas.list',                 'uses' =>  'IdeaController@index']);
         Route::get('/add',          ['as' => 'ideas.add',                  'uses' =>  'IdeaController@add']);
         Route::post('/add',         ['as' => 'ideas.store',                'uses' =>  'IdeaController@store']);
         Route::get('/edit/{id}',    ['as' => 'ideas.edit',                 'uses' =>  'IdeaController@edit']);
         Route::post('/edit/{id}',   ['as' => 'ideas.update',               'uses' =>  'IdeaController@update']);
         Route::post('/delete/{id}', ['as' => 'ideas.delete',               'uses' =>  'IdeaController@delete']);  
    });
    // Idea routes End

 });




?>