@extends('admin.layouts.login')
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Admin</b> Reset Password</a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Reset your password</p>
            @include('admin.includes.messages')            
            {{ Form::open(array('route' => array('admin_password_update',$token),'name' => 'resetForm','id' => 'resetForm')) }}
                <div class="form-group has-feedback"> 
                    {{ Form::password('password',array('id'=>'password','class' => 'form-control','placeholder'=>'Enter your password')) }}
                </div>
                <div class="form-group has-feedback"> 
                    {{ Form::password('confirm_password',array('id'=>'confirm_password','class' => 'form-control','placeholder'=>'Confirm your password')) }}         
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection