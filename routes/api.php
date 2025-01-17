<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::middleware('api')
    ->namespace('api')
    /*->prefix("v1")*/
    ->group(function () {
        Route::post('/cms', "HomeController@cmsFetch");
        Route::post('/isuserexist', "UserController@isUserExist");
        Route::post('/signup', "UserController@signup");
        Route::post('/login', "UserController@login");
        Route::post('/socialLogin', "UserController@socialLogin");
        Route::post('/fetchuserdetails', "UserController@fetchuserdetails");
        Route::post('/edituserdetails', "UserController@edituserdetails");
        Route::post('/forgotPassword', "UserController@forgotPassword");
        Route::post('/resetForgotPassword', "UserController@resetForgotPassword");
        Route::post('/getCategoryList', "CategoryController@getCategoryList");
        Route::post('/getSubCategorybyCategoryId', "CategoryController@getSubCategorybyCategoryId");
        Route::post('/vendorAddBusiness', "BusinessController@vendorAddBusiness");
        Route::post('/vendorBusinessListing', "BusinessController@vendorBusinessListing");
        Route::post('/vendorBusinessDetails', "BusinessController@vendorBusinessDetails");
        Route::post('/editBusinessDetails', "BusinessController@editBusinessDetails");
        Route::post('/base64ImageUpload', "BusinessController@base64ImageUpload");
        Route::post('/FeaturedBusinessListing' , "BusinessController@FeaturedBusinessListing");
		Route::post('/customerBusinessDetailFetch', "BusinessController@customerBusinessDetailFetch");
		Route::post('/cityList', "BusinessController@cityList");
		Route::post('/getLocationByCity', "BusinessController@getLocationByCity");
		Route::post('/searchByCategoryTab' , "BusinessController@searchByCategoryTab");
		Route::post('/searchByLocationTab' , "BusinessController@searchByLocationTab");
		Route::post('/searchByFeaturedBusinessTab' , "BusinessController@searchByFeaturedBusinessTab");
		Route::post('/increaseTopClicked' , "BusinessController@increaseTopClicked");
        Route::post('/addCatagorySubCatagoryByName' , "CategoryController@addCatagorySubCatagoryByName");
        Route::post('/addTags' , "CategoryController@addTags");
        Route::post('/listTags' , "CategoryController@listTags");
        Route::post('/deleteVendorBusinessByBusinessId' , "BusinessController@deleteVendorBusinessByBusinessId");
		Route::post('/EditNotificationAndRadious', "UserController@EditNotificationAndRadious");
		Route::post('/make_favorite_business' , "UserController@makeFavoriteBusiness");
        Route::post('/list_favorite_business' , "UserController@listFavoriteBusiness");
		Route::post('/reviewPostToBusiness' , "BusinessController@reviewPostToBusiness");
		Route::post('/reviewList' , "BusinessController@reviewList");
		Route::post('/nearByBusinessList' , "BusinessController@nearByBusinessList");
		Route::post('/FeaturedBusinessListWithMostClick' , "BusinessController@FeaturedBusinessListWithMostClick");
		Route::post('/LatestTenBusinessList' , "BusinessController@LatestTenBusinessList");
		Route::post('/getMostUsedBusinessCategory' , "BusinessController@getMostUsedBusinessCategory");
		Route::post('/homePageSearch' , "BusinessController@homePageSearch");
		Route::post('/autoSuggestCategoryList' , "BusinessController@autoSuggestCategoryList");
		Route::post('/notificationList' , "BusinessController@notificationList");
		Route::post('/updateUnreadNotificationToRead' , "BusinessController@updateUnreadNotificationToRead");
		Route::post('/deleteNotifications' , "BusinessController@deleteNotifications");
		Route::post('/unreadNotificationCount' , "BusinessController@unreadNotificationCount");
		Route::post('/updateFCMtokenPlatform' , "UserController@updateFCMtokenPlatform");
		
		Route::post('/sendNotificationAndroid' , "HomeController@sendNotificationAndroid");
		Route::post('/InCaseOthersaddCatagorySubCatagoryInApp' , "CategoryController@InCaseOthersaddCatagorySubCatagoryInApp");
		
		Route::post('/recommendedBusinessById', "UserController@recommendedBusinessById");
		Route::post('/individualNotificationDelete' , "BusinessController@individualNotificationDelete");
		
		Route::post('/createAdvertise', "AdvertiseController@createAdvertise");
		Route::post('/listOfMyAdvertise', "AdvertiseController@listOfMyAdvertise");
		Route::post('/updateMyAdvertise', "AdvertiseController@updateMyAdvertise");
		Route::post('/matchedAdvertiseList', "AdvertiseController@matchedAdvertiseList");
		
		Route::post('/create', "HomeController@create");
		Route::post('/getDateTime', "AdvertiseController@getDateTime");
		Route::post('/logoutFromDevice', "UserController@logoutFromDevice");
		Route::post('/loggedinuserLatlong', "UserController@loggedinuserLatlong");
		
		Route::post('/homeFeaturedListAfterSearch', "BusinessController@homeFeaturedListAfterSearch");
		Route::post('/homeNearByListAfterSearch', "BusinessController@homeNearByListAfterSearch");
    });
