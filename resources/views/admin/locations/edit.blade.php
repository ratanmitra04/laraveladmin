@extends('admin.layouts.default')
@section('title', $management.' '.$pageType)
@section('content')
<div class="content-wrapper">
    <!-- Content Header Section Start -->
    <section class="content-header">
        <h1> {{ $management.' '.$pageType }}</h1>
        <!-- Breadcrumb Start -->
        <ol class="breadcrumb">
            @foreach($breadcrumb['EDITPAGE'] as $eachBreadcrumb)
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
		<nav aria-label="breadcrumb mt50">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a class="link" href="{{ route('locations.list') }}">Location Management</a></li>
               <li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="View">View</a></li>
               <li class="breadcrumb-item active" aria-current="page">{{$record->location_name}}</li>
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
			{{ Form::open(array('route'=>array($routePrefix.'.update',$id), 'class'=>'form-horizontal', 'name'=>'', 'autocomplete'=>'off', 'enctype'=>'multipart/form-data')) }}
                    {{ Form::hidden('user_type', GlobalVars::TEACHER_USER_TYPE, array('id' => 'user_type')) }}
            		
        
				<div class="col-sm-6 col-xs-12 personal-info">
                
				<div class="col-md-12">
				
					<div class="form-group">
						<label class="col-lg-3 control-label">{!! Form::label('Location Name') !!}</label>
						<div class="col-lg-8">{{ Form::text('location_name', $record->location_name, array('required','class' => 'form-control','id' => 'location_name','placeholder' => 'Enter Location Name')) }}</div>
					</div>
					
					<div class="form-group">
						 <label class="col-lg-3 control-label">{!! Form::label('City:') !!}</label>
						 <div class="col-lg-8">
						<select name="city" id="city_id" class="form-control">
							<option value="">--- Select City ---</option>
							@foreach ($city as $key => $value)
								<option value="{{ $value->id }}"{{ ( $record->city_id == $value->id ) ? ' selected' : '' }}>{{ $value->city_name }}</option>
							@endforeach
						</select>
						</div>
                  </div>
				  
				</div>

	   
				<div class="col-md-12 col-sm-12 col-xs-12 text-center">
						<button type="submit" class="btn btn-primary">Submit</button>
						<a class="btn btn-default" href="{{ \Helpers::getCancelbuttonUrl($routePrefix,\Request::get('from')) }}">Cancel</a>
				</div>
				{!! Form::close() !!}
              </div>
           
		   </div>
        
    </section>
</div>
@endsection