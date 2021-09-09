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
            </div>
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Listing</h3>
                        <div class="box-tools">
                            {{ Form::open(array('route'=>array($routePrefix.'.list'), 'method'=>'get', 'class'=>'form-validate', 'name'=>'', 'autocomplete'=>'off')) }}
                            <div class="input-group input-group-sm" style="width: 350px;">
                                <input type="text" name="keyword" value="{{$keyword}}" class="form-control pull-right" placeholder="Page Name">
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
                                <th>Page Name</th>
                                <th>Last Updated</th>
                                <th class="no-sort">Action</th>
                            </tr>
                            <tbody>
                                @if($records->count()>0)	
                                @foreach($records as $record)
                                <tr>
                                    <td>{{ $record->page_name }}</td>                                   
                                    <td>{{ $record->updated_at }}</td>
                                    <td>
										<a class="link" href="{{ URL::Route($routePrefix.'.view', $record->id) }}" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a> | 			 
										<a class="link" href="{{ URL::Route($routePrefix.'.edit', $record->id) }}" title="Edit Meta"><i class="fa fa-edit"></i></a> 
                                        <!--<a class="link" href="{{ URL::Route('cmscontents.list') }}?pageid={{$record->id}}" title="Manage Contents"><i class="fa fa-navicon"></i></a>--> 
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" align="center">
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