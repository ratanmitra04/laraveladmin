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
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    {{ Form::open(array('route'=>array($routePrefix.'.store'), 'name'=>'', 'class'=>'form-validate', 'autocomplete'=>'off', 'enctype'=>'multipart/form-data')) }}
                    {{ Form::hidden('user_type', GlobalVars::TEACHER_USER_TYPE, array('id' => 'user_type')) }}
                    <div class="box-footer">
                        <div class="pull-right">				  
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('First Name') !!}
                                    {{ Form::text('first_name', null, array('required','class' => 'form-control','id' => 'first_name','placeholder' => 'Enter First Name')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Last Name') !!}
                                    {{ Form::text('last_name', null, array('required','class' => 'form-control','id' => 'last_name','placeholder' => 'Enter Last Name')) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Email') !!}
                                    {{ Form::text('email', null, array('required','class' => 'form-control','id' => 'email','placeholder' => 'Enter Email')) }}
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Phone') !!}
                                    {{ Form::text('phone', null, array('required','class' => 'form-control','id' => 'phone','placeholder' => 'Enter Phone Number')) }}
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Password') !!}
                                    {{ Form::password('password', array('required','class' => 'form-control','id' => 'password','placeholder' => 'Enter Password', 'data-rule-minlength' => 6)) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Confirm Password') !!}
                                    {{ Form::password('confirmpassword', array('required','class' => 'form-control','id' => 'confirmpassword','placeholder' => 'Confirm Password')) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Profile Status') !!}
                                    {!! Form::select('status', ['' => 'Select Status','Active' => 'Active','Inactive' => 'Inactive'], null, array('required', 'id' => 'status', 'class'=>'form-control')) !!}
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="box-footer">
                        <div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a class="btn btn-default" href="{{ \Helpers::getCancelbuttonUrl($routePrefix,\Request::get('from')) }}">Cancel</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection