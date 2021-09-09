@extends('admin.layouts.default')
@section('title', $title)
@section('content')
	<div class="content-wrapper">
    <!-- Content Header Section Start -->
    <section class="content-header">
        <h1>Admin Change Password</h1>	
    </section>
    <!-- Content Header Section End -->	
		<section class="content">
    	<!-- Message Start -->
    	@include('admin.includes.messages')
    	<!-- Message End -->    		
	    	<div class="row">
		        <div class="col-xs-12">
		          	<div class="box box-primary">
            			{{ Form::open(array('route'=>array('admin_change_password'), 'class'=>'form-validate', 'name'=>'', 'autocomplete'=>'off')) }}
            				<div class="box-footer">  
                        		<div class="pull-right">
                        		</div>
                      		</div>
              				<div class="box-body">
              					<div class="form-group">
                  					{!! Form::label('Current Password') !!}
                  					{{ Form::input('password', 'old_password', '', array('required','class' => 'form-control','placeholder' => 'Enter Current Password')) }}
                				</div>
                				<div class="form-group">
                  					{!! Form::label('New Password') !!}
                  					{{ Form::input('password', 'new_password', '', array('required','class' => 'form-control','placeholder' => 'New Password')) }}
                				</div>
                				<div class="form-group">
                  					{!! Form::label('Confirm New Password') !!}
                  					{{ Form::input('password', 'confirm_password', '', array('required','class' => 'form-control','placeholder' => 'Confirm New Password')) }}
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
