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
               <li class="breadcrumb-item"><a class="link" href="{{ route('cities.list') }}">City Management</a></li>
               <!--<li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="View">View</a></li>-->
               <li class="breadcrumb-item active" aria-current="page">Add</li>
               <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  <!--<li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Active / Deactive"> Active / Deactive</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Delete">Delete</a></li>
                  <li class="breadcrumb-item"><a class="btn btn-default" value="edit">Edit</a></li>-->
               </ul>
            </ol>
         </nav>
         <div class="row">
            
            <!-- left edit form column -->
            
               <!--<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">-->
               {{ Form::open(array('route'=>array($routePrefix.'.store'), 'name'=>'', 'class'=>'form-horizontal', 'autocomplete'=>'off', 'enctype'=>'multipart/form-data')) }} 
				
				<div class=" col-sm-6 col-xs-12 personal-info ">		
				
				  <div class="form-group">
                     <label class="col-lg-3 control-label">City Name:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="text" name="city_name" required value="" placeholder="City Name">
                     </div>
                  </div>
				 
				  <div class="col-md-12 col-sm-12 col-xs-12 text-center">
					<input type="submit" name="submit" class="btn btn-primary" value="Submit">
				</div>
               <!--</form>-->
			   {!! Form::close() !!}
				
            </div>
            <!--<div class="col-md-12 col-sm-12 col-xs-12 text-center">
               <input type="button" class="btn btn-primary" value="Save">
            </div>-->
         </div>
      <!-- </div> -->
   </section>
</div>
@endsection