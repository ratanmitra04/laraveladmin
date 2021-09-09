@extends('admin.layouts.default')
@section('title', $management.' '.$pageType)
@section('content')
<div class="content-wrapper">
   <!-- Content Header Section Start -->
   <section class="content-header">
      <h1> {{ $management.' '.$pageType }}</h1>
      <!-- Breadcrumb Start -->
      <ol class="breadcrumb">
         @foreach($breadcrumb['LISTPAGE'] as $eachBreadcrumb)
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
   <!-- Content Section Start -->
   <section class="content">
      <!-- Message Start -->
      @include('admin.includes.messages')
      <!-- Message End -->    		
      <!-- <div class="container"> -->
         <nav aria-label="breadcrumb mt50">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="{{ route('users.list') }}">User Management</a></li>
               <li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',$record->id) }}" title="View">View</a></li>
               <li class="breadcrumb-item active" aria-current="page">{{ $record->first_name }} {{ $record->last_name }}</li>
               <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default change-status" value="Active / Inactive" data-id="{{ $record->id }}" data-model="User"><?php echo ($record->status =='Active')? 'Active' : 'Inactive' ?> </a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" onClick="destroyData('{{ URL::Route($routePrefix.'.delete', $record->id) }}')" value="Delete">Delete</a></li>
                  <li class="breadcrumb-item"><a class="btn btn-default" href="{{ URL::Route($routePrefix.'.edit', $record->id) }}" value="edit">Edit</a></li>
               </ul>
            </ol>
         </nav>
         <!-- right column -->
         <div class="col-xs-12 col-sm-6 text-center right">
            <div class="text-center">
			@if(!empty($record->user_logo))
                  <img src="{{asset('uploads/user_images')}}/{{ $record->user_logo }}" class="avatar img-circle prelative" alt="avatar">
			  @else
				  <img src="{{asset('admin/images/no_img.jpg')}}" class="avatar img-circle prelative" alt="avatar">
			  @endif
            </div>
         </div>
		 <?php
		 //echo "<pre>"; print_r($cityname);
		 ?>
         <!-- left edit form column -->
         <div class="col-sm-6 col-xs-12 personal-info  ">
            <form class="form-horizontal" role="form">
               <div class="form-group">
                  <label class="col-lg-3 control-label">Name:</label>
                  <div class="col-lg-8">
                     <label class="control-label">{{ $record->first_name }} {{ $record->last_name }}</label>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-lg-3 control-label">Email Address:</label>
                  <div class="col-lg-8">
                     <label class="control-label">{{ $record->email }}</label>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-lg-3 control-label">Mobile Number:</label>
                  <div class="col-lg-8">
                     <label class="control-label">{{ $record->phone}}</label>
                  </div>
               </div>
			   <div class="form-group">
                  <label class="col-lg-3 control-label">City:</label>
                  <div class="col-lg-8">
                     <label class="control-label">@if($cityname){{ $cityname->city_name }} @else  @endif</label>
                  </div>
               </div>
			   
			   <div class="form-group">
                     <label class="col-lg-3 control-label">Recommended Category:</label>
                     <div class="col-lg-8">
                        <label class="control-label">
						<?php 
						for($i=0; $i < count($category); $i++){
							if(in_array($category[$i]->id,$selectedRecommended))
							{
								echo $category[$i]->category_name.',';
							}
							else
							{
								echo "";
							}
						}
						?>
						</label>
                     </div>
                  </div>
			   
			   <div class="form-group">
                  <label class="col-lg-3 control-label">Gender:</label>
                  <div class="col-lg-8">
                     <label class="control-label">{{ $record->gender}}</label>
                  </div>
               </div>
			   <div class="form-group">
                  <label class="col-lg-3 control-label">Age:</label>
                  <div class="col-lg-8">
                     <label class="control-label">{{ $record->age}}</label>
                  </div>
               </div>
			   
			   <div class="form-group">
                  <label class="col-lg-3 control-label">User Type:</label>
                  <div class="col-lg-8">
                     <?php
				  if($record->user_type=="VU")
				  {
					 ?>
                     <label class="control-label">
					 <?php echo "Vendor"; ?>
					 </label>
				  <?php } if($record->user_type=="CU"){?>
					<label class="control-label">
					 <?php echo "Customer"; ?>
					 </label>
				  <?php } 
				  if($record->user_type=="SA"){?>
					<label class="control-label">
					 <?php echo "Moderator"; ?>
					 </label>
				  <?php } ?>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-lg-3 control-label">Registered On:</label>
                  <div class="col-lg-8">
                     <label class="control-label">{{ $record->created_at->format('Y-m-d')}}</label>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-lg-3 control-label">Status:</label>
                  <div class="col-lg-8">
                     <label class="control-label">{{ $record->status}}</label>
                  </div>
               </div>
            </form>
         </div>
      </div>
<!-- </div> -->
</section>
</div>
@endsection