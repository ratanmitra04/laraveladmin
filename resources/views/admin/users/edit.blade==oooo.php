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
               <li class="breadcrumb-item"><a class="link" href="{{ route('users.list') }}">User Management</a></li>
               <li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="View">View</a></li>
               <li class="breadcrumb-item active" aria-current="page">{{ $record->first_name }} {{ $record->last_name }}</li>
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
            <div class="col-xs-12 col-sm-6 text-center right">
               <div class="text-center">
                  <img src="{{asset('uploads/user_images')}}/{{ $record->user_logo }}" class="avatar img-circle prelative" alt="avatar">
                  <input type="file" id="file" name="user_logo" class="imgcam">
               </div>
            </div>   		
        
            <div class="col-sm-6 col-xs-12 personal-info">
                
                    
                        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">{!! Form::label('First Name') !!}</label>
                                    <div class="col-lg-8">{{ Form::text('first_name', $record->first_name, array('required','class' => 'form-control','id' => 'first_name','placeholder' => 'Enter First Name')) }}</div>
                                </div>
                            </div>
                        
						
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">{!! Form::label('Last Name') !!}</label>
                                    <div class="col-lg-8">{{ Form::text('last_name', $record->last_name, array('required','class' => 'form-control','id' => 'last_name','placeholder' => 'Enter Last Name')) }}</div>
                                </div>
                            </div>
                        
                       
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">{!! Form::label('Email Address') !!}</label>
                                    <div class="col-lg-8">{{ Form::text('email', $record->email, array('required','class' => 'form-control','id' => 'email','placeholder' => 'Enter Email')) }}</div>
                                </div>
                            </div>
                        
						
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">{!! Form::label('Phone Number') !!}</label>
                                    <div class="col-lg-8">{{ Form::text('phone', $record->phone, array('required','class' => 'form-control','id' => 'phone','placeholder' => 'Enter Phone Number')) }}</div>
                                </div>
                            </div>
                        
						<div class="col-md-12">
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
							
							<div class="col-md-12">
                                <div class="form-group">
                                     <label class="col-lg-3 control-label">{!! Form::label('Location:') !!}</label>
									 <div class="col-lg-8">
									 <select name="area" id="area_id" class="form-control">
										<option value="">--- Select Location ---</option>
										@foreach ($area as $value)
											<option value="{{ $value->id }}"{{ ( $record->location_id == $value->id ) ? ' selected' : '' }}>{{ $value->location_name }}</option>
										@endforeach
									</select>
									</div>
                                </div>
                            </div>
						
						
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">{!! Form::label('Gender') !!}</label>
                                    <div class="col-lg-8">
									<input type=radio name="gender" value="Male" {{$record->gender == 'Male' ? 'checked' : ''}}>Male</option>
									<input type=radio name="gender" value="Female" {{$record->gender == 'Female' ? 'checked' : ''}}>Female</option>            
									</div>
                                </div>
                            </div>
                        
						
						
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">{!! Form::label('Age') !!}</label>
                                    <div class="col-lg-8">{{ Form::text('age', $record->age, array('class' => 'form-control','id' => 'age','placeholder' => '')) }}</div>
                                </div>
                            </div>
                        
						
						 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">{!! Form::label('User Type') !!}</label>
                                    <div class="col-lg-8">{!! Form::select('user_type', ['' => 'Select User Type','CU' => 'Customer','VU' => 'Vendor'], $record->user_type, array('id' => 'user_type', 'class'=>'form-control')) !!}</div>
                                </div>
                            </div>
                        
                        
                        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">{!! Form::label('Status') !!}</label>
                                    <div class="col-lg-8">{!! Form::select('status', ['' => 'Select Status','Active' => 'Active','Inactive' => 'Inactive'], $record->status, array('required', 'id' => 'status', 'class'=>'form-control')) !!}</div>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="city"]').on('change', function() {
            var city_id = $(this).val();
			//alert(url);
            if(city_id) {
                $.ajax({
                    url: 'http://demoyourprojects.website/projects/directoryapp/dev/da-admin/users/getAreas/'+city_id,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        console.log(data.record);
                        $('select[name="area"]').empty();
                        $.each(data.record, function(key, value) {
                            $('select[name="area"]').append('<option value="'+ value.id +'">'+ value.location_name +'</option>');
                        });

                    }
                });
            }else{
                $('select[name="area"]').empty();
            }
        });
    });
</script>
@endsection