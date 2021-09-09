@extends('admin.layouts.default')
@section('title', $management.' '.$pageType)
@section('content')
<div class="content-wrapper">
    <!-- Content Header Section Start -->
    <section class="content-header">
        <!--<h1> {{ $management.' '.$pageType }}</h1>-->
		<h1> {{ $actionByDetail->first_name }} {{ $actionByDetail->last_name .' '.$pageType.' '.'log'}}</h1>
        <!-- Breadcrumb Start -->
        <ol class="breadcrumb">
            @foreach($breadcrumb['MODERATORACTIVITY'] as $eachBreadcrumb)
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
                        <!--<h3 class="box-title">Listing</h3>-->
                        <div class="box-tools">
<?php
//echo "<pre>"; print_r($records);exit;
?>
                        <!--{{ Form::open(array('route'=>array($routePrefix.'.list'), 'method'=>'get', 'class'=>'form-validate', 'name'=>'', 'autocomplete'=>'off')) }}
                        <div class="input-group input-group-sm" style="width: 350px;">
                                <input type="text" name="keyword" value="{{$keyword}}" class="form-control pull-right" placeholder="Search by transaction id,user name">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default">
                                        Search<!-- <i class="fa fa-search"></i> -->
                                    <!--</button>
                                    <a class="btn btn-default" href="{{ route($routePrefix.'.list') }}">Reset</a>
                                </div>
                            </div>

                        {!! Form::close() !!}-->
                        </div>
                    </div>
                <div class="row">
                    <div class="col-lg-8">
                  {{ Form::open(array('route'=>array($routePrefix.'.activity', $id), 'method'=>'get', 'class'=>'form-validate', 'name'=>'', 'autocomplete'=>'off')) }}
                        <div class="form-group col-lg-12 mt15">
                            <label class="col-lg-3 control-label">Filter By Date:</label>
                                
                            <div class="col-lg-9 " id="dateline"> <input name="formDate" class="form-control wd30  online" type="date" value=""><span class="online">to</span><input name="toDate"  class="form-control wd30  online" type="date" value=""></div>
							
                            <label class="col-lg-3 control-label">Filter By Section:</label>
								<div class="col-lg-8">
									 <select name="management" id="management" class="form-control">
										<option value="">--- Select Management ---</option>
										@foreach ($managementInfo as $value)
											<option value="{{ $value->id }}"{{($management == $value->id ) ? ' selected' : '' }}>{{ ucfirst($value->name) }} Management</option>
										@endforeach
									</select>
								</div>
								
                         </div>
                    </div>
                    <!--<a class="btn btn-default mt15" href=" ">Submit</a>-->
					<button type="submit" class="btn btn-default">
                             Search
					</button>
					<a class="btn btn-default" href="{{ route($routePrefix.'.activity', $id) }}">Reset</a>
					{!! Form::close() !!}
					
					<span class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></span>
                </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
						<?php
						//echo "<pre>"; print_r($management);
						?>
                            <tr>
                                <th>#</th>
								<th>Time Stamp</th>
                                <th>Section</th>
                                <th>Activity</th>
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
                                <td>{{ $record->created_at}}</td>
                                <td>{{ ucfirst($record->management->name)}} Management</td>
                                <td>
								<?php
								if($record->action_id==1)
								{
									echo "Added for".' '.$record->name_action_for ;
								}
								if($record->action_id==2)
								{
									echo "Updated for".' '.$record->name_action_for;
								}
								if($record->action_id==3)
								{
									echo "Deleted for".' '.$record->name_action_for;
								}
								if($record->action_id==4)
								{
									echo "Approved for".' '.$record->name_action_for;
								}
								if($record->action_id==5)
								{
									echo "Rejected for".' '.$record->name_action_for;
								}
								?>
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