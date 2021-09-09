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
                  <li class="breadcrumb-item"> <a href="{{ route($routePrefix.'.list') }}">Advertisement Management
                        </a></li>
                  <li class="breadcrumb-item"><a href="#">View</a></li>

                  <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Delete"> Delete</a></li>
                  </ul>
                </ol>
              </nav> 
             
              <div class="row">
            
                  <!-- right column -->
                
                    
                  <!-- left edit form column -->
                  <div class="col-md-9 col-sm-12 col-xs-12 personal-info ">
                  
                    <form class="form-horizontal" role="form">
                      <h2>Advertisement Details : -</h2>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Advertisement Banner:</label>
                        <div class="col-lg-8">                         
                          <img src="{{asset('uploads/images_admin')}}/img_snow.jpg" style="max-width:100%;height:auto;">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Advertisement Link:</label>
                        <div class="col-lg-8">
                          <label class="control-label">www.blavity.com</label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Duration:</label>
                        <div class="col-lg-8">
                          <label class="control-label">10.05.20 to 12.10.20</label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Subscription:</label>
                        <div class="col-lg-8">
                          <label class="control-label">abc</label>
                        </div>
                      </div>   
                    
                    </form>
                  </div>
                <div class="col-md-9 col-sm-12 col-xs-12 personal-info ">
                  <form class="form-horizontal" role="form">
                  <h2>Onboard Details : -</h2>
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
                      <label class="col-lg-3 control-label">Company Name:</label>
                      <div class="col-lg-8">
                        <label class="control-label">Abc Corporate</label>
                      </div>
                    </div>
                  
            </div>
</form>
<div class="col-sm-12 col-xs-12 ">    
                        
                      <input type="reset" class="btn btn-default pull-right" value="Reject">
                      <input type="reset" class="btn btn-default pull-right" id="btn-confirm" value="Approve">   
                  </div>
  <!-- </div> -->
</div>

    </section>
</div>
@endsection