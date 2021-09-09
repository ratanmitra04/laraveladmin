@extends('admin.layouts.default')
@section('title', $management.' '.$pageType)
@section('content')
<div class="content-wrapper">
    <!-- Content Header Section Start -->
    <section class="content-header">
        <h1> {{ $management.' '.$pageType }}</h1>
        <!-- Breadcrumb Start -->
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li>{{ $pageType }}</li>                                                            
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
                    {{ Form::open(array('route'=>array($routePrefix.'.update',$id), 'class'=>'form-validate', 'name'=>'', 'autocomplete'=>'off', 'enctype'=>'multipart/form-data')) }}
                    <div class="box-footer">
                        <div class="pull-right">				  
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                           <div class="col-md-12">
                            <legend>General Settings:</legend>
                           </div>                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Site Sender Email') !!}
                                    {{ Form::text('from_email', $record->from_email, array('required','data-rule-email' => 'true','class' => 'form-control','id' => 'from_email','placeholder' => 'From Email Name')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Site Sender Email Name') !!}
                                    {{ Form::text('from_email_name', $record->from_email_name, array('class' => 'form-control','id' => 'from_email_name','placeholder' => 'From Email Name')) }}
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Email To receive Contact') !!}
                                    {{ Form::text('contact_email', $record->contact_email, array('required','data-rule-email' => 'true','class' => 'form-control','id' => 'contact_email','placeholder' => 'Email To receive Contact')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Email To receive Career') !!}
                                    {{ Form::text('career_email', $record->career_email, array('required','data-rule-email' => 'true','class' => 'form-control','id' => 'career_email','placeholder' => 'Email To receive Career')) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Site Address') !!}
                                    {{ Form::text('site_address', $record->site_address, array('required','class' => 'form-control','id' => 'site_address','placeholder' => 'Site Address')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Site Contact Number') !!}
                                    {{ Form::text('site_contact_no', $record->site_contact_no, array('required','class' => 'form-control','id' => 'career_email','placeholder' => 'Site Contact Number')) }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Site Fax Number') !!}
                                    {{ Form::text('site_fax_no', $record->site_fax_no, array('class' => 'form-control','id' => 'site_fax_no','placeholder' => 'Site Fax Number')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Facebook URL') !!}
                                    {{ Form::text('facebook_url', $record->facebook_url, array('class' => 'form-control','data-rule-url' => 'true','id' => 'facebook_url','placeholder' => 'Facebook URL')) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Twitter URL') !!}
                                    {{ Form::text('twitter_url', $record->twitter_url, array('class' => 'form-control','data-rule-url' => 'true','id' => 'twitter_url','placeholder' => 'Twitter URL')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Instagram URL') !!}
                                    {{ Form::text('instagram_url', $record->instagram_url, array('class' => 'form-control','data-rule-url' => 'true','id' => 'instagram_url','placeholder' => 'Instagram URL')) }}
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('LinkedIn URL') !!}
                                    {{ Form::text('linkedin_url', $record->linkedin_url, array('class' => 'form-control','data-rule-url' => 'true','id' => 'linkedin_url','placeholder' => 'LinkedIn URL')) }}
                                </div>
                            </div>
                            {{ Form::hidden('footer_logo_text', $record->footer_logo_text) }}
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Footer Logo Text') !!}
                                    {{ Form::textarea('footer_logo_text', $record->footer_logo_text, array('class' => 'form-control','id' => 'footer_logo_text','placeholder' => 'Footer Logo Text')) }}
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="box-footer">
                        <div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a class="btn btn-default" href="{{ \Helpers::getCancelbuttonUrl(null,'dashboard') }}">Cancel</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection