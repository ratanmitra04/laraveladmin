@extends('admin.layouts.default')
@section('title', $title)
@section('content')
	<div class="content-wrapper">
    <!-- Content Header Section Start -->
    <section class="content-header">
        <h1>Admin Update Profile</h1>	
    </section>
    <!-- Content Header Section End -->	
		<section class="content">
    	<!-- Message Start -->
    	@include('admin.includes.messages')
    	<!-- Message End -->    		
	    	<div class="row">
            <div class="col-lg-12 col-xs-12">
                <a class="pull-right cstm-hyperlink-weight" href="{{ URL::Route('admin_change_password') }}">
                <i class="fa fa-key"></i> Change Password</a>
            </div>          
		        <div class="col-xs-12">
		          	<div class="box box-primary">
            			{{ Form::open(array('route'=>array('admin_update_profile'), 'class'=>'form-validate', 'name'=>'', 'autocomplete'=>'off', 'files'=>'true')) }}
            				<div class="box-footer">  
                        		<div class="pull-right">
                        		</div>
                      		</div>
              				<div class="box-body">
              					<div class="form-group">
                  					{!! Form::label('First Name') !!}
                  					{{ Form::text('first_name', $record->first_name, array('required','class' => 'form-control','id' => 'first_name','placeholder' => 'Enter First Name')) }}
                				</div>
                				<div class="form-group">
                  					{!! Form::label('Last Name') !!}
                  					{{ Form::text('last_name', $record->last_name, array('required','class' => 'form-control','id' => 'last_name','placeholder' => 'Enter Last Name')) }}
                				</div>
                				<div class="form-group">
                  					{!! Form::label('Email') !!}
                  					{{ Form::text('email', $record->email, array('required','class' => 'form-control','id' => 'email','placeholder' => 'Enter Email')) }}
                				</div>               
                				<div class="form-group">
                  					{!! Form::label('User Image') !!}
                  					{{ Form::file('user_logo', ['class' => 'form-control']) }}
                				</div>               
              				</div>
              				<div class="box-footer">  
                        		<div>
									<button type="submit" class="btn btn-primary">Submit</button>
	                          		<a class="btn btn-default" href="{{ URL::Route('dashboard') }}">Cancel</a>
								</div>
                      		</div>
            			{!! Form::close() !!}
          			</div>
		        </div>
	      	</div>
      	</section>
    </div>
@endsection
