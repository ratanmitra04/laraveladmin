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
            <div class="col-sm-12">
                     <h3 class="control-label"><strong>Feature Settings</strong></h3>
                     <div class="col-sm-12">
                     <div class="col-sm-12">
                        <h3 class="control-label">Business Packages</h3>
                        <p class="col-lg-8 col-sm-12">By the options you can enable or disable package features for business user.</p> 
                        <label class="col-lg-4 col-sm-12 switch">
                          <input type="checkbox" checked="">
                          <span class="slider round"></span>
                        </label>
                        </div>
                        <div class="col-sm-12">
                        <h3 class="control-label">Paid Advertisements</h3>
                        <p class="col-lg-8 col-sm-12">By the options you can enable or disable subscription for advertisement uploading.</p> 
                        <label class="col-lg-4 col-sm-12 switch">
                          <input type="checkbox" checked="">
                          <span class="slider round"></span>
                        </label>
                        </div>

                     </div>
                
           
                        <div class="col-sm-12 mt25 ">
                            <a class="btn btn-primary " href="{{ \Helpers::getCancelbuttonUrl(null,'dashboard') }}"><strong>Save Changes</strong></a>
                        </div>
                    
            </div>
        </div>
    </section>
</div>
@endsection