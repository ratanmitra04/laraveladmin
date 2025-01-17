@extends('admin.layouts.login' , ['title' => $title])
@section('content')
    <div class="login-box">
		<div class="login-logo">
			<div><img src="{{ asset('admin/images/home_logo.png') }}"/></div>
			<!--<a href="#"><b>{{ GlobalVars::ADMIN_NAME }}</b> Admin</a>-->
			<a href="#"><b>Custom Black Index</b></a>
		</div>
		<div class="login-box-body">
			<p class="login-box-msg">Sign in to start your session</p>
			@include('admin.includes.messages')
			{{ Form::open(array('route'=>array('do_admin_login'), 'name'=>'', 'class'=>'form-validate', 'autocomplete'=>'off')) }}
				<div class="form-group has-feedback">
					{{ Form::text('email', null, array('class' => 'form-control','id' => 'email', 'placeholder' => 'Email')) }}
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					{{ Form::password('password', array('class' => 'form-control','id' => 'password', 'placeholder' => 'Password')) }}
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-8">
						<a href="{{ URL::Route('admin_forgot_password') }}">Forgot password?</a>
					</div>
					<div class="col-xs-4">
						{{ Form::submit('Sign In', array('class' => 'btn btn-primary btn-block btn-flat')) }}
					</div>
				</div>
			{!! Form::close() !!}

		</div>
  	</div>
@endsection