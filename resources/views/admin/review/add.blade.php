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
      <li class="breadcrumb-item"><a href="{{ route($routePrefix.'.list') }}">Review Management
	          </a></li>
      <li class="breadcrumb-item"><a href="#">View</a></li>
      <li class="breadcrumb-item active" aria-current="page">Jhon Doe</li>
    </ol>
  </nav>
    
  <div class="row">      
    <div class="col-md-4 col-md-offset-8">    
    <a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a>  
    <input type="reset" class="btn btn-default" value="Active / Deactive">    
    <input type="reset" class="btn btn-default" value="Delete">
</div>
</div>
  	<hr>        
      <!-- left edit form column -->
      <div class="col-md-9 col-sm-12 col-xs-12 personal-info ">
       
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