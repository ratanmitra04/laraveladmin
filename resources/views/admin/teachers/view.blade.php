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
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                
            </div>
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Listing</h3>
                        <div class="box-tools">

                        {{ Form::open(array('route'=>array($routePrefix.'.list'), 'method'=>'get', 'class'=>'form-validate', 'name'=>'', 'autocomplete'=>'off')) }}
                        <div class="input-group input-group-sm" style="width: 350px;">
                                <input type="text" name="keyword" value="" class="form-control pull-right" placeholder="Teacher Name / Email">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default">
                                        Search<!-- <i class="fa fa-search"></i> -->
                                    </button>
                                    <a class="btn btn-default" href="{{ route($routePrefix.'.list') }}">Reset</a>
                                </div>
                            </div>

                        {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                                              
                    </div>
                </div>
            </div>
        </div>


        <div class="container">
  <nav aria-label="breadcrumb mt50">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('teachers.list') }}">User Management</a></li>
      <li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="View">View</a></li>
      <li class="breadcrumb-item active" aria-current="page">Jhon Doe</li>
    </ol>
  </nav>
    
  <div class="row">      
    <div class="col-md-3 col-md-offset-8">    
    <a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a>     
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
        <div class="text-center">
          <img src="{{asset('uploads/adminuser_logos')}}/{{ Auth::guard('admin')->user()->user_logo }}" class="avatar img-circle prelative" alt="avatar">
        </div>
      </div>
      
      <!-- left edit form column -->
      <div class="col-md-9 col-sm-12 col-xs-12 personal-info left">
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
</div>
    </section>
</div>
@endsection