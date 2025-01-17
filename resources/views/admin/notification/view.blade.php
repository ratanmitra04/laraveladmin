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
   
       
  <nav aria-label="breadcrumb mt50">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"> <a href="{{ route($routePrefix.'.list') }}">Notification Management
	          </a></li>
      <li class="breadcrumb-item"><a href="#">View</a></li>
      <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  </ul>
    </ol>
  </nav>
    
  

	<div class="row">
 
      <!-- right column -->
     
        
      <!-- left edit form column -->
      <div class="col-md-9 col-sm-12 col-xs-12 personal-info">
       
        <form class="form-horizontal" role="form">
          
          <div class="form-group">
            <label class="col-lg-3 control-label">Title:</label>
            <div class="col-lg-8">
              <label class="control-label">{{ $record->title }}</label>
            </div>
          </div>
         <div class="form-group">
            <label class="col-lg-3 control-label">Description:</label>
            <div class="col-lg-8">
              <p>{{ $record->description }}</p>
             
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Type Mode:</label>
            <div class="col-lg-8">
              <label class="control-label"><?php
									if($record->type=='PushNotification')
									{
										echo "Push Notification";
									}
									else
									{
										echo "E-mail";
									}
									?></label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Send to:</label>
            <div class="col-lg-8">
              <label class="control-label">{{ $record->sendto }}</label>
            </div>
          </div>
      
        </form>
      </div>

</div>
       
    </section>
</div>
@endsection