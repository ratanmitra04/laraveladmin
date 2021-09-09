@extends('admin.layouts.default')
@section('title', $management.' '.$pageType)
@section('content')
<div class="content-wrapper">
    <!-- Content Header Section Start -->
    <section class="content-header">
        <h1> {{ $management.' '.$pageType }} Meta</h1>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('Page Name') !!}
                                    {{ Form::text('page_name', $record->page_name, array('class' => 'form-control','id' => 'page_name','placeholder' => 'Enter Page Name','disabled'=>'disabled')) }}
                                </div>
                            </div>                                                        
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Meta Title') !!}
                                    {{ Form::text('meta_title', $record->meta_title, array('class' => 'form-control','id' => 'meta_title','placeholder' => 'Enter Meta Title')) }}
                                </div>
                            </div>                           
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Meta Description') !!}
                                    {{ Form::textarea('meta_description', $record->meta_description, array('class' => 'form-control','id' => 'meta_description','placeholder' => 'Enter Meta Description','rows' => 3, 'cols' => 80)) }}
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('Meta Keywords') !!}
                                    {{ Form::textarea('meta_keywords', $record->meta_keywords, array('class' => 'form-control','id' => 'meta_keywords','placeholder' => 'Enter Meta Keywords','rows' => 3, 'cols' => 80)) }}
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
