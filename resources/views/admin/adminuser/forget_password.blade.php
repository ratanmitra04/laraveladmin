@extends('admin.layouts.login' , ['title' => $title])
@section('content')
    <div class="login-box">
		<div class="login-logo">
			<a href="#"><b>Admin</b> Forgot Password</a>
		</div>
		<div class="login-box-body">
			<p class="login-box-msg">Enter Email Id to get reset password link</p>
			@include('admin.includes.messages')
			{!! Form::open(array('id' => 'foretPasswordForm')) !!}
				<div class="form-group has-feedback">
					{{ Form::text('email', null, array('class' => 'form-control','id' => 'email', 'placeholder' => 'Email')) }}
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-6 col-xs-offset-3">
						{{ Form::submit('Send Reset Link', array('class' => 'btn btn-primary btn-block btn-flat')) }}
					</div>
				</div>
			{!! Form::close() !!}
			<a href="{{ URL::Route('admin_login') }}">Back to Login</a><br>
		</div>
  	</div>
@endsection