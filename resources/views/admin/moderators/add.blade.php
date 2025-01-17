@extends('admin.layouts.default')
@section('title', $management.' '.$pageType)
@section('content')
<div class="content-wrapper">
   <!-- Content Header Section Start -->
   <section class="content-header">
      <h1> {{ $management.' '.$pageType }}</h1>
      <!-- Breadcrumb Start -->
      <ol class="breadcrumb">
         @foreach($breadcrumb['CREATEPAGE'] as $eachBreadcrumb)
         @if($loop->first)
         <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
         @endif
         @if($eachBreadcrumb['url'] == 'THIS')
         <li>{{ $eachBreadcrumb['label'] }}</li>
         @else
         <li><a href="{{ $eachBreadcrumb['url'] }}">{{ $eachBreadcrumb['label'] }}</a></li>
         @endif                                                        
         @endforeach        
      </ol>
      <!-- Breadcrumb End -->		
   </section>
   <!-- Content Header Section End -->
   <section class="content">
      <!-- Message Start -->
      @include('admin.includes.messages')
      <!-- Message End -->    		
      <!-- <div class="container"> -->
         <nav aria-label="breadcrumb mt50">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a class="link" href="{{ route('moderators.list') }}">Moderator Management</a></li>
               <!--<li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="View">View</a></li>-->
               <li class="breadcrumb-item active" aria-current="page">Add</li>
               <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  <!--<li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Active / Deactive"> Active / Deactive</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Delete">Delete</a></li>
                  <li class="breadcrumb-item"><a class="btn btn-default" value="edit">Edit</a></li>-->
               </ul>
            </ol>
         </nav>
         <div class="row">
		 {{ Form::open(array('route'=>array($routePrefix.'.store'), 'name'=>'', 'class'=>'form-horizontal', 'autocomplete'=>'off', 'enctype'=>'multipart/form-data')) }}
            <!-- right column -->
            <div class="col-xs-12 col-sm-6 text-center right">
               <div class="text-center">
				  <img src="{{asset('admin/images/no_img.jpg')}}" class="avatar img-circle prelative" alt="avatar">
                  <input type="file" id="file" name="user_logo" class="imgcam">
               </div>
            </div>
            <!-- left edit form column -->
            <div class=" col-sm-6 col-xs-12 personal-info ">
               <!--<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">-->
                  
				  <div class="form-group">
                     <label class="col-lg-3 control-label">First Name*:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="text" name="first_name" maxlength="50" required value="">
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Last Name*:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="text" name="last_name" maxlength="50" required value="">
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Email Address*:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="text" name="email" maxlength="150" autocomplete="off" required value="">
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Phone Number*:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="text" maxlength="20" name="phone" required value="">
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Password*:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="password" name="password" maxlength="50" required value="">
                     </div>
                  </div>
                  <!--<div class="form-group">
                     <label class="col-lg-3 control-label">Registered On:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="date" name="regdate" value="">
                     </div>
                  </div>-->
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Status*:</label>
                     <div class="col-lg-8">
                        <div class="dropdown">
                           <select class="custom-select btn btn-primary" name="status" required id="inputGroupSelect01">
                              <option value="">Choose...</option>
                              <option value="1">Active</option>
                              <option value="2">Deactive</option>
                           </select>
                        </div>
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Permission*:</label>
                     <div class="col-lg-8">
					  <input type="checkbox" id="user_management" name="mod_management[]" value="users">
					  <label for="user_management"> Users Management</label><br>
					  
					  <input type="checkbox" id="business_management" name="mod_management[]" value="business">
					  <label for="business_management"> Business Management</label><br>
					  
					  <input type="checkbox" id="category_management" name="mod_management[]" value="category">
					  <label for="category_management"> Category Management</label><br>
					  
					  <input type="checkbox" id="subscription_management" name="mod_management[]" value="subscription">
					  <label for="subscription_management"> Subscription Management</label><br>
					 
					 <input type="checkbox" id="advertise_management" name="mod_management[]" value="advertise">
					  <label for="advertise_management"> Advertise Management</label><br>
					  
					  <input type="checkbox" id="transaction_management" name="mod_management[]" value="transaction">
					  <label for="transaction_management"> Transaction Management</label><br>
					  
					  <input type="checkbox" id="review_management" name="mod_management[]" value="review">
					  <label for="review_management"> Review Management</label><br>
					  
					  <input type="checkbox" id="notification_management" name="mod_management[]" value="notification">
					  <label for="notification_management"> Notification Management</label><br>
					  
					  <input type="checkbox" id="static_cont_management" name="mod_management[]" value="cms">
					  <label for="static_cont_management"> Cms Management</label><br>
					  
					  <input type="checkbox" id="city_management" name="mod_management[]" value="city">
					  <label for="city_management"> City Management</label><br>
					  
					  <input type="checkbox" id="app_settings_management" name="mod_management[]" value="appsettings">
					  <label for="app_settings_management"> Appsettings Management</label><br><br>
					  
                     </div>
					 
                  </div>
				  <div class="col-md-12 col-sm-12 col-xs-12 text-center">
					<input type="submit" name="submit" class="btn btn-primary" value="Submit">
				</div>
               <!--</form>-->
			   {!! Form::close() !!}
				
            </div>
            <!--<div class="col-md-12 col-sm-12 col-xs-12 text-center">
               <input type="button" class="btn btn-primary" value="Save">
            </div>-->
         </div>
      <!-- </div> -->
   </section>
</div>
@endsection