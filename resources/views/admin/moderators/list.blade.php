@extends('admin.layouts.default')
@section('title', $management.' '.$pageType)
@section('content')
<div class="content-wrapper">
    <!-- Content Header Section Start -->
    <section class="content-header">
        <h1> {{ $management.' '.$pageType }}</h1>
        <!-- Breadcrumb Start -->
        <ol class="breadcrumb">
            @foreach($breadcrumb['LISTPAGE'] as $eachBreadcrumb)
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
    <!-- Content Section Start -->
    <section class="content">
        <!-- Message Start -->
        @include('admin.includes.messages')
        <!-- Message End -->    		
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <a class="pull-right cstm-hyperlink-weight" href="{{ route($routePrefix.'.add') }}">
                <i class="fa fa-plus-square"></i> Add New</a>

                <a href="{{ route($routePrefix.'.export') }}" class="link pull-right mr25"> <i class="fa fa-download"></i> Export</a>

            </div>
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Listing</h3>
                        <div class="box-tools">

                        {{ Form::open(array('route'=>array($routePrefix.'.list'), 'method'=>'get', 'class'=>'form-validate', 'name'=>'', 'autocomplete'=>'off')) }}
                        <div class="input-group input-group-sm" style="width: 350px;">
                                <input type="text" name="keyword" value="{{$keyword}}" class="form-control pull-right" placeholder="Search by name,email address">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default">
                                        Search<!-- <i class="fa fa-search"></i> -->
                                    </button>
                                    <a class="btn btn-default" href="{{ route($routePrefix.'.list') }}">Reset</a>
                                </div>
                            </div>

                        {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>#</th>
								<th>Full Name</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                            <tbody>
                            @if($records->count()>0)	
								@php
								   if($pagenum==1){
									   $add=1;
								   }else{
									   $add=(($pagenum-1)*GlobalVars::ADMIN_RECORDS_PER_PAGE)+1;
								   }
							    @endphp
                                @foreach($records as $key=>$record)
                                <tr>
                                    <td>{{ $key+$add }}</td>
                                    <td>{{ $record->first_name }} {{ $record->last_name }}</td>
                                    <td>{{ $record->email}}</td>
                                    <td>{{ $record->phone}}</td>
                                    <td>
									<?php
									if($record->status=='Active')
									{
									?>
									<span class="activetab">Active</span>
									<?php }else{ ?>
									<span class="deactivetab">Deactivate</span>
									<?php } ?>
									</td>
                                    <td>
									
									<a class="link" href="{{ URL::Route($routePrefix.'.activity', $record->id) }}" title="Activity Logs">Activity Logs</a> |
									
                                    <a class="link" href="{{ URL::Route($routePrefix.'.view', $record->id) }}" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a> | 								 
                                    <a class="link" href="{{ URL::Route($routePrefix.'.edit', $record->id) }}" title="Edit"><i class="fa fa-edit"></i></a> | 								 
                                    <a class="link del" onClick="destroyData('{{ URL::Route($routePrefix.'.delete', $record->id) }}')" href="javascript:void(0)"  title="Delete"> <i class="fa fa-trash text-red"></i> </a>
                                    
									</td>
                                    
                                   
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td align="left">
                                        <p>No records found</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
						</table>  
						<div class="box-footer pull-right">{{ $records->appends(request()->input())->render() }}</div>                      
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection