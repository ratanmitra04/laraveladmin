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
            <!-- <a class="link pull-right mr25"> <i class="fa fa-download"></i> Export</a> &nbsp &nbsp &nbsp-->
            <a href="{{ route($routePrefix.'.add') }}" class="link pull-right mr25" value="">Add New</a>
         </div>
         <div class="col-xs-12">
            <div class="box box-primary">
               <div class="box-header">
                  <h3 class="box-title">Listing</h3>
                  <div class="box-tools">
                     {{ Form::open(array('route'=>array($routePrefix.'.list'), 'method'=>'get', 'class'=>'form-validate', 'name'=>'', 'autocomplete'=>'off')) }}
                     <div class="input-group input-group-sm" style="width: 350px;">
                        <input type="text" name="keyword" value="{{$keyword}}" class="form-control pull-right" placeholder="Search by Name">
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
               <!-- <div class="container"> -->
                  <div class="row">
                     <div class="col-md-4 col-md-offset-8">    
                     </div>
                  </div>
                  <div class="box-body table-responsive no-padding">
                     <table class="table table-hover">
                        <tr>
                           <th>#</th>
                           <th>Category Name</th>
                           <th>Added On</th>
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
                              <td>{{ $record->category_name}}</td>
                              <td><?php if($record->created_at!=''){echo date('Y-m-d',strtotime($record->created_at));}else {echo "N.A";}?></td>
                              <td><a type="button" href="{{ URL::Route($routePrefix.'.view',$record->id) }}" class="btn btn-primary" value="">View Sub Category</a></td>
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
               <!-- </div> -->
            </div>
         </div>
      </div>
   </section>
</div>
@endsection