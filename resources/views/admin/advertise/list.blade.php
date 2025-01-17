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
                <a class="link pull-right mr25"> <i class="fa fa-download"></i> Export</a> &nbsp &nbsp &nbsp
            </div>
			
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Listing</h3>
                        <div class="box-tools">

                        {{ Form::open(array('route'=>array($routePrefix.'.list'), 'method'=>'get', 'class'=>'form-validate', 'name'=>'', 'autocomplete'=>'off')) }}
                        <div class="input-group input-group-sm" style="width: 350px;">
                                <input type="text" name="keyword" value="{{$keyword}}" class="form-control pull-right" placeholder="Search by..">
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
                                <th>Advertisement Banner</th>
                                <th>Created By</th>
                                <th>Subscription</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                            <tbody>
                            <tr>
								<td>1</td>
                                <td><img src="{{asset('uploads/images_admin')}}/img_snow.jpg" style="width:auto;height:50px;"></td>
                                <td>John Mathue</td>
                                <td>Monthly</td>
                                <td>21days</td>
                                <td>Pending</td>
                                <td>
                                   <!-- <a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="Edit"><i class="fa fa-edit"></i></a>-->
                                <a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp &nbsp &nbsp &nbsp<a><i class="fa fa-trash" aria-hidden="true"></i></a>
                                
                            </tr>
                            <tr>
								<td>2</td>
                                <td><img src="{{asset('uploads/images_admin')}}/img_mountains.jpg" style="width:auto;height:50px;"></td>
                                <td>John Mathue</td>
                                <td>Monthly</td>
                                <td>21days</td>
                                <td>Rejected</td>
                                <td>
                                   
                                <a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp &nbsp &nbsp &nbsp<a><i class="fa fa-trash" aria-hidden="true"></i></a>
                        
								</td>
                            </tr><tr>
								<td>3</td>
                                <td><img src="{{asset('uploads/images_admin')}}/img_mountains.jpg" style="width:auto;height:50px;"></td>
                                <td>John Mathue</td>
                                <td>Monthly</td>
                                <td>21days</td>
                                <td>Approved & Payment Confirmed</td>
                                <td>
                                   <!-- <a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="Edit"><i class="fa fa-edit"></i></a>-->
                                <a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp &nbsp &nbsp &nbsp<a><i class="fa fa-trash" aria-hidden="true"></i></a>
								</td>
                            </tr>
                            </tbody>
						</table>  
						<div class="box-footer pull-right"></div>                      
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection