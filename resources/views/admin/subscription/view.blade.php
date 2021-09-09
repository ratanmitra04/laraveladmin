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
        
        <!-- <div class="container "> -->
  <nav aria-label="breadcrumb mt50">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route($routePrefix.'.list') }}">Subscription Management</a></li>
      <li class="breadcrumb-item"><a href="{{ URL::Route($routePrefix.'.view',1) }}">View</a></li>
      <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
</ul>
    </ol>
  </nav>
    
 
	<div class="row ">
  
      
      <!-- left edit form column -->
      <div class="col-sm-12 col-xs-12 personal-info ">
        <form class="form-horizontal" role="form">
          <div class="form-group">
            <label class="col-lg-3 control-label">Transaction ID:</label>
            <div class="col-lg-8">
              <label class="control-label">1111</label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">User Name:</label>
            <div class="col-lg-8">
              <label class="control-label">Jhon Hunt</label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Subscription:</label>
            <div class="col-lg-8">
              <label class="control-label">Basic Package</label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Transaction Date:</label>
            <div class="col-lg-8">
              <label class="control-label">11.02.20</label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Amount:</label>
            <div class="col-lg-8">
              <label class="control-label">$50</label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Billing Cycle:</label>
            <div class="col-lg-8">
              <label class="control-label">11.02.20 to 25.10.20</label>
            </div>
          </div>  
         
          
        </form>
      </div>   
        
      <div class="col-sm-12 col-xs-12">
        <a type="button" class="btn btn-primary pull-right" value="Send Reminder">Send Reminder</a>
      </div>

  </div>
<!-- </div> -->
       
    </section>
</div>
@endsection