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
        <div class="container">
  <nav aria-label="breadcrumb mt50">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('business.list') }}">Advertisement Management
	          </a></li>
      <li class="breadcrumb-item"><a href="#">View</a></li>
    </ol>
  </nav>
    
  <div class="row">      
    <div class="col-md-4 col-md-offset-8">    
    <a class="btn btn-default" value="Back" href="{{ route('business.list') }}">Back</a>  
    <input type="reset" class="btn btn-default" value="Delete">
</div>
</div>
  	<hr>
	
     
     

        
      <!-- left edit form column -->
     
      <div class="row">
 
      <!-- right column -->
     
        
      <!-- left edit form column -->
      <div class="col-md-9 col-sm-12 col-xs-12 personal-info ">
       
        <form class="form-horizontal" role="form">
          <h2>Advertisement Details : -</h2>
          <div class="form-group">
            <label class="col-lg-3 control-label">Advertisement Banner:</label>
            <div class="col-lg-8">
              <input class="form-control uploadimg" type="file" id="comment" style="max-width:100%;"></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Advertisement Link:</label>
            <div class="col-lg-8">
              <label class="control-label">www.blavity.com</label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Duration:</label>
            <div class="col-lg-8">
              <label class="control-label">10.05.20 to 12.10.20</label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Subscription:</label>
            <div class="col-lg-8">
              <label class="control-label">abc</label>
            </div>
          </div>   
         
        </form>
      </div>
     <div class="col-md-9 col-sm-12 col-xs-12 personal-info">
      <form class="form-horizontal" role="form">
      <h2>Onboard Details : -</h2>
      <div class="form-group">
        <label class="col-lg-3 control-label">Name:</label>
        <div class="col-lg-8">
          <label class="control-label">Jhon Doe</label>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Email Address:</label>
        <div class="col-lg-8">
          <label class="control-label">abc@gmail.com</label>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Phone Number:</label>
        <div class="col-lg-8">
          <label class="control-label">8956451258</label>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Company Name:</label>
          <div class="col-lg-8">
            <label class="control-label">Abc Corporate</label>
          </div>
        </div>
       <div class="pull-right">    
          <input type="reset" class="btn btn-default" id="btn-confirm" value="Approve">    
          <input type="reset" class="btn btn-default" value="Reject"> 
      </div>
</div>
</form>
  </div>
</div>
       
     

  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
  <div class="modal-dialog modal-sm wd">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Advertisement Approval</h4>
      </div>
      <div class="ct">  Share the below link with the user for the payment,once the payment is done advert will be published.</div>
     <div class="text-center pb25"> <a target="_blank" href="https:/payabc123.com/">https:/payabc123.com</a> </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="modal-btn-si">Cancel</button>
        <button type="button" class="btn btn-primary" id="modal-btn-no">Approve and send payment link</button>
      </div>
    </div>
  </div>
</div>
</section>

</div>
@endsection