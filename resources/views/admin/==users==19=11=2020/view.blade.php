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
               <li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="View">View</a></li>
               <li class="breadcrumb-item active" aria-current="page">Jhon Doe</li>
               <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Active / Deactive"> Active / Deactive</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Delete">Delete</a></li>
                  <li class="breadcrumb-item"><a class="btn btn-default" href="{{ URL::Route($routePrefix.'.add') }}" value="edit">Edit</a></li>
               </ul>
            </ol>
         </nav>
         <!-- right column -->
         <div class="col-xs-12 col-sm-6 text-center right">
            <div class="text-center">
               <img src="{{asset('uploads/adminuser_logos')}}/{{ Auth::guard('admin')->user()->user_logo }}" class="avatar img-circle prelative" alt="avatar">
            </div>
         </div>
         <!-- left edit form column -->
         <div class="col-sm-6 col-xs-12 personal-info  ">
            <form class="form-horizontal" role="form">
               <div class="form-group">
                  <label class="col-lg-3 control-label">Name:</label>
                  <div class="col-lg-8">
                     <label class="control-label">Jhon Doe</label>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-lg-3 control-label">Email Address:</label>
                  <div class="col-lg-8">
                     <label class="control-label">abc@gmail.com</label>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-lg-3 control-label">Phone Number:</label>
                  <div class="col-lg-8">
                     <label class="control-label">8956451258</label>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-lg-3 control-label">Registered On:</label>
                  <div class="col-lg-8">
                     <label class="control-label">15.05.2020</label>
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-lg-3 control-label">Status:</label>
                  <div class="col-lg-8">
                     <label class="control-label">Active</label>
                  </div>
               </div>
            </form>
         </div>
      </div>
<!-- </div> -->
</section>
</div>
@endsection