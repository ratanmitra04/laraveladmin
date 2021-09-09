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
               <li class="breadcrumb-item"> <a href="{{ route('business.list') }}">Busines Management
                  </a>
               </li>
               <li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="View">View</a></li>
               <li class="breadcrumb-item active" aria-current="page">Jhon Doe</li>
               <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Active / Deactive"> Active / Deactive</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Delete">Delete</a></li>
                  <li class="breadcrumb-item"><a class="btn btn-default" href="{{ route($routePrefix.'.add') }}" value="edit">Edit</a></li>
               </ul>
            </ol>
         </nav>
         <div class="row">
            <!-- right column -->
            <div class=" col-sm-6 col-xs-12 right">
               <div class="row pab">
                  <div class="column1">
                     <img src="{{asset('uploads/images_admin')}}/img_nature.jpg" style="width:100%" onclick="openModal();currentSlide(1)" class="hover-shadow cursor">
                  </div>
                  <div class="column1">
                     <img src="{{asset('uploads/images_admin')}}//img_snow.jpg" style="width:100%" onclick="openModal();currentSlide(2)" class="hover-shadow cursor">
                  </div>
                  <div class="column1">
                     <img src="{{asset('uploads/images_admin')}}//img_mountains.jpg" style="width:100%" onclick="openModal();currentSlide(3)" class="hover-shadow cursor">
                  </div>
                  <div class="column1">
                     <img src="{{asset('uploads/images_admin')}}//img_lights.jpg" style="width:100%" onclick="openModal();currentSlide(4)" class="hover-shadow cursor">
                  </div>
               </div>
               <div id="myModal" class="modal1">
                  <span class="close cursor" onclick="closeModal()">&times;</span>
                  <div class="modal-content">
                     <div class="mySlides">
                        <div class="numbertext">1 / 4</div>
                        <img src="{{asset('uploads/images_admin')}}/img_nature_wide.jpg" style="width:100%">
                     </div>
                     <div class="mySlides">
                        <div class="numbertext">2 / 4</div>
                        <img src="{{asset('uploads/images_admin')}}/img_snow_wide.jpg" style="width:100%">
                     </div>
                     <div class="mySlides">
                        <div class="numbertext">3 / 4</div>
                        <img src="{{asset('uploads/images_admin')}}/img_mountains_wide.jpg" style="width:100%">
                     </div>
                     <div class="mySlides">
                        <div class="numbertext">4 / 4</div>
                        <img src="{{asset('uploads/images_admin')}}/img_lights_wide.jpg" style="width:100%">
                     </div>
                     <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                     <a class="next" onclick="plusSlides(1)">&#10095;</a>
                     <div class="caption-container">
                        <p id="caption"></p>
                     </div>
                     <div class="column">
                        <img class="demo cursor" src="{{asset('uploads/images_admin')}}/img_nature_wide.jpg" style="width:100%" onclick="currentSlide(1)" alt="Nature and sunrise">
                     </div>
                     <div class="column">
                        <img class="demo cursor" src="{{asset('uploads/images_admin')}}/img_snow_wide.jpg" style="width:100%" onclick="currentSlide(2)" alt="Snow">
                     </div>
                     <div class="column">
                        <img class="demo cursor" src="{{asset('uploads/images_admin')}}/img_mountains_wide.jpg" style="width:100%" onclick="currentSlide(3)" alt="Mountains and fjords">
                     </div>
                     <div class="column">
                        <img class="demo cursor" src="{{asset('uploads/images_admin')}}/img_lights_wide.jpg" style="width:100%" onclick="currentSlide(4)" alt="Northern Lights">
                     </div>
                  </div>
               </div>
            </div>
            <!-- left edit form column -->
            <div class="col-sm-6 col-xs-12 personal-info left">
               <form class="form-horizontal" role="form">
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Business Name:</label>
                     <div class="col-lg-8">
                        <label class="control-label">Blavity</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Email Address:</label>
                     <div class="col-lg-8">
                        <label class="control-label">abcjohn@gmail.com</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Phone Number:</label>
                     <div class="col-lg-8">
                        <label class="control-label">67575675675</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Category:</label>
                     <div class="col-lg-8">
                        <label class="control-label">Food</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Subcategory:</label>
                     <div class="col-lg-8">
                        <label class="control-label">Italian</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Website Link:</label>
                     <div class="col-lg-8">
                        <label class="control-label">www.blavity.com</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Registered On:</label>
                     <div class="col-lg-8">
                        <label class="control-label">15.06.2020</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Status:</label>
                     <div class="col-lg-8">
                        <label class="control-label">Active</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Description:</label>
                     <div class="col-lg-8">
                        <p>Here are our 12 best Italian recipes that includes Italian veg dishes, Italian pasta recipes, Italian pizza recipes, Italian salad, Italian desserts and Italian bread recipe. Go on and try, you know you can't resist!</p>
                     </div>
                  </div>
               </form>
            </div>
            <div class="col-md-3 col-md-offset-9">    
               <input type="reset" class="btn btn-default" value="Approve">    
               <input type="reset" class="btn btn-default" value="Reject"> 
            </div>
         </div>
      <!-- </div> -->
   </section>
</div>
@endsection