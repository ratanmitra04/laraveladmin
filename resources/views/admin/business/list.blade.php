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

                <a href="{{ route($routePrefix.'.export') }}" class="link pull-right mr25"> <i class="fa fa-download"></i> Export</a>

            </div>
			
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Listing</h3>
                        <div class="box-tools">

                        {{ Form::open(array('route'=>array($routePrefix.'.list'), 'method'=>'get', 'class'=>'form-validate', 'name'=>'', 'autocomplete'=>'off')) }}
                        <div class="input-group input-group-sm" style="width: 350px;">
                                <input type="text" name="keyword" value="{{$keyword}}" class="form-control pull-right" placeholder="Search by business name,email address">
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
                                <th>Business Name</th>
                                <th>Email</th>
                                <th>Phone</th>
								<th>Impression Count</th>
                                <th>Status</th>
								<th>Admin Approval</th>
                                <th class="no-sort">Action</th>
                            </tr>
                            <tbody>
                            @if($records->count()>0)	
                                @foreach($records as $record)
                                <tr>
                                    <td>{{ $record->business_name }}</td>
                                    <td>{{ $record->business_email}}</td>
                                    <td>{{ $record->business_phone}}</td>
									<td>{{ $record->top_clicked}}</td>
                                    
									<?php
									if($record->status=='Active')
									{
									?>
									<td class="approved">Active</td>
									<?php }else{?>
									<td class="pending">Inactive</td>
									<?php } ?>
									
									<?php
									if($record->approved_by_admin==0)
									{
									?>
									<td class="pending">Pending</td>
									<?php }elseif($record->approved_by_admin==1){ ?>
									<td class="approved">Approved</td>
									<?php }else{?>
									<td class="rejected">Rejected</td>
									<?php } ?>
									
                                    <td>
                                    <a class="link" href="{{ URL::Route($routePrefix.'.view', $record->id) }}" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a> | 	
									<?php
									if($record->approved_by_admin!=2)
									{
									?>									
                                    <a class="link" href="{{ URL::Route($routePrefix.'.edit', $record->id) }}" title="Edit"><i class="fa fa-edit"></i></a> | 	
									<?php } ?>	
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