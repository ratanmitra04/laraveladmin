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
               <li class="breadcrumb-item"><a class="link" href="{{ route('teachers.list') }}">User Management</a></li>
               <li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="View">View</a></li>
               <li class="breadcrumb-item active" aria-current="page">Jhon Doe</li>
               <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Active / Deactive"> Active / Deactive</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Delete">Delete</a></li>
                  <li class="breadcrumb-item"><a class="btn btn-default" value="edit">Edit</a></li>
               </ul>
            </ol>
         </nav>
         <div class="row">
            <!-- right column -->
            <div class="col-xs-12 col-sm-6 text-center right">
               <div class="text-center">
                  <img src="{{asset('uploads/adminuser_logos')}}/{{ Auth::guard('admin')->user()->user_logo }}" class="avatar img-circle prelative" alt="avatar">
                  <input type="file" id="file" class="imgcam">
               </div>
            </div>
            <!-- left edit form column -->
            <div class=" col-sm-6 col-xs-12 personal-info ">
               <form class="form-horizontal" role="form">
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Name:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="text" value="Jane">
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Email Address:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="text" value="abc@gmail.com">
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Phone Number:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="number" value="123456789">
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Registered On:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="date" value="12.02.2020">
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Status:</label>
                     <div class="col-lg-8">
                        <div class="dropdown">
                           <select class="custom-select btn btn-primary" id="inputGroupSelect01">
                              <option selected>Choose...</option>
                              <option value="1">Active</option>
                              <option value="2">Deactive</option>
                           </select>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
               <input type="button" class="btn btn-primary" value="Save">
            </div>
         </div>
      <!-- </div> -->
   </section>
</div>
@endsection