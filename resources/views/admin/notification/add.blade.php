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
      
        <!-- <div class="container"> -->
  <nav aria-label="breadcrumb mt50">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"> <a href="{{ route('review.list') }}">Notification Management
	          </a></li>
      <li class="breadcrumb-item"><a href="{{ URL::Route($routePrefix.'.view',1) }}">View</a></li>

      <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
               </ul>
    </ol>
  </nav>
	<div class="row">
      <!-- left edit form column -->
      <div class="col-sm-12 col-xs-12 personal-info ">
         
		{{ Form::open(array('route'=>array($routePrefix.'.store'), 'name'=>'', 'class'=>'form-horizontal', 'autocomplete'=>'off')) }}   		
        <div class="form-group">
            <label class="col-lg-3 control-label">Title:</label>
            <div class="col-lg-8">
              <input class="form-control" type="text" name="title" required value="">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Description:</label>
            <div class="col-lg-8">
              <textarea class="form-control" name="description" required rows="5" id="comment" style="max-width:100%;"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Type Mode:</label>
            <div class="col-lg-8">
            <label class="radio-inline">
              <input type="radio" name="type" value="PushNotification" checked>Push Notification
            </label>
            <label class="radio-inline">
              <input type="radio" name="type" value="E-mail">E-mail
            </label>
            </div>
          </div>
          <div class="form-group">
          <label class="col-lg-3 control-label">Send to:</label>
            <div class="col-lg-8">
            <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="sendto" value="AllUsers" required> All Users
                </label>
              </div>
              <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="sendto" value="OnlyCustomer"> Only Customer
                </label>
              </div>
              <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="sendto" value="OnlyVendors"> Only Vendors
                </label>
              </div>
			  <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="sendto" value="BasicUsers"> Basic Users
                </label>
              </div>
			   <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="sendto" value="StandardUsers"> Standard Users
                </label>
              </div>
			  <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="sendto" value="PremiumUsers"> Premium Users
                </label>
              </div>
              <div class="form-check ifPushHide">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="sendto" value="other"> Custom Notifications
                </label>
              </div>  
            </div>
            </div>
          <div class="form-group">
            <label class="col-lg-3 control-label"></label>
            <div class="col-lg-8 customId">
              <input class="form-control" type="text" name="email" placeholder="Enter your email address" autocomplete="off" value="">
            </div>
			
		<div class="col-sm-12 col-xs-12">
			<input type="submit" name="submit" class="btn btn-primary pull-right" value="Send">
		</div>
        
		{!! Form::close() !!}		
      </div>     
  </div>

       
    </section>
</div>

<script>
$( document ).ready(function() {
var radioValue = $("input[name='type']:checked").val();
//alert(radioValue);
if(radioValue=='PushNotification'){
	//alert("Your are a - " + radioValue);
	$(".ifPushHide").hide();
}
	
$('input[type=radio][name=type]').change(function() {	
   if($(this).val()=="E-mail")	   
   {
      $(".ifPushHide").show();
   }
   else
   {
       $(".ifPushHide").hide(); 
   }
});
$(".customId").hide(); 
$('input[type=radio][name=sendto]').change(function() {	
   if($(this).val()=="other")	   
   {
      $(".customId").show();
   }
   else
   {
       $(".customId").hide(); 
   }
});
});
</script>
@endsection