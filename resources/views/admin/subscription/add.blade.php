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
        <div class="container">
  <nav aria-label="breadcrumb mt50">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('business.list') }}">Busines Management
	          </a></li>
      <li class="breadcrumb-item"><a href="#">View</a></li>
      <li class="breadcrumb-item active" aria-current="page">Jhon Doe</li>
    </ol>
  </nav>
    
  <div class="row">      
    <div class="col-md-4 col-md-offset-9">    
    <a class="btn btn-default" value="Back" href="{{ route('business.list') }}">Back</a>  
    <input type="reset" class="btn btn-default" value="Active / Deactive">    
    <input type="reset" class="btn btn-default" value="Delete">
</div>
</div>
  	<hr>
	<div class="row">
    <div class="col-xs-12 mb50">
    <a class="pull-right" href="{{ route($routePrefix.'.add') }}"> <i class="fa fa-pencil-square"></i></a>
  </div>
      <!-- right column -->
      <div class="col-md-3 col-sm-12 col-xs-12 right">
        <div class="row pab">
          <div class="column1">
            <img src="{{asset('uploads/images_admin')}}/img_nature.jpg" style="width:100%" onclick="openModal();currentSlide(1)" class="hover-shadow cursor">
          </div>
          <div class="column1">
            <img src="{{asset('uploads/images_admin')}}/img_snow.jpg" style="width:100%" onclick="openModal();currentSlide(2)" class="hover-shadow cursor">
          </div>
          <div class="column1">
            <img src="{{asset('uploads/images_admin')}}/img_mountains.jpg" style="width:100%" onclick="openModal();currentSlide(3)" class="hover-shadow cursor">
          </div>
          <div class="column1">
            <img src="{{asset('uploads/images_admin')}}/img_lights.jpg" style="width:100%" onclick="openModal();currentSlide(4)" class="hover-shadow cursor">
          </div>
        </div>
        <input type="file" id="filecam" class="imgcam">
       
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
        
       
        
        <div class="addm"><input type="file" id="file1" class="addmore ptc" ><i class="fa fa-plus fa-6" ></i>&nbsp;&nbsp; <span>Add More</span></div>
      </div>
      
     
     

        
      <!-- left edit form column -->
      <div class="col-md-9 col-sm-12 col-xs-12 personal-info left">
       
        <form class="form-horizontal" role="form">
          <div class="form-group">
            <label class="col-lg-3 control-label">Business Name:</label>
            <div class="col-lg-8">
              <input class="form-control" type="text" value="Jane">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Email Address:</label>
            <div class="col-lg-8">
              <input class="form-control" type="email" value="abc@gmail.com">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Phone Number:</label>
            <div class="col-lg-8">
              <input class="form-control" type="number" value="123456789">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Category:</label>
            <div class="col-lg-8">
              <div class="dropdown">                
                <select class="custom-select btn btn-secondary" >
                  <option selected>Food</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Subcategory:</label>
            <div class="col-lg-8">
              <div class="dropdown">                
                <select class=" btn btn-secondary" >
                  <option selected>Indian</option>
                  <option value="1">Mexican</option>
                  <option value="2">Italian</option>
                  <option value="3">Three</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Website Link:</label>
            <div class="col-lg-8">
              <input class="form-control" type="text" value="abc@gmail.com">
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
          <div class="form-group">
            <label class="col-lg-3 control-label">Description:</label>
            <div class="col-lg-8">
              <textarea class="form-control" rows="5" id="comment" style="max-width:100%;"></textarea>
            </div>
          </div>
          
        </form>
      </div>
      
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
          <input type="button" class="btn btn-primary" value="Save">
        </div>
     

  </div>
</div>
</section>
</div>
@endsection