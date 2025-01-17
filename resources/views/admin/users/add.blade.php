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
      <!-- <div class="container"> -->
         <nav aria-label="breadcrumb mt50">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a class="link" href="{{ route('users.list') }}">User Management</a></li>
               <!--<li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',1) }}" title="View">View</a></li>-->
               <li class="breadcrumb-item active" aria-current="page">Add</li>
               <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  <!--<li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Active / Deactive"> Active / Deactive</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Delete">Delete</a></li>
                  <li class="breadcrumb-item"><a class="btn btn-default" value="edit">Edit</a></li>-->
               </ul>
            </ol>
         </nav>
         <div class="row">
            
            <!-- left edit form column -->
            
               <!--<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">-->
               {{ Form::open(array('route'=>array($routePrefix.'.store'), 'name'=>'', 'class'=>'form-horizontal', 'autocomplete'=>'off', 'enctype'=>'multipart/form-data')) }} 
		<!-- right column -->
            <div class="col-xs-12 col-sm-6 text-center right">
               <div class="text-center">
                  <img src="{{asset('admin/images/no_img.jpg')}}" class="avatar img-circle prelative" alt="avatar">
                  <input type="file" id="file" name="user_logo" class="imgcam">
               </div>
            </div>			
<div class=" col-sm-6 col-xs-12 personal-info ">			
				  <div class="form-group">
                     <label class="col-lg-3 control-label">First Name*:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="text" name="first_name" maxlength="50" required value="">
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Last Name*:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="text" name="last_name" maxlength="50" required value="">
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Password*:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="password" name="password" maxlength="30" required value="">
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Email Address*:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="text" name="email" maxlength="150" autocomplete="off" required value="">
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Mobile Number:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="text" maxlength="20" name="phone" value="">
                     </div>
                  </div>
				  <div class="form-group">
                      <label class="col-lg-3 control-label">{!! Form::label('City:') !!}</label>
						 <div class="col-lg-8">
						 <select name="city" id="city_id" class="form-control">
							<option value="">--- Select City ---</option>
							@foreach ($city as $key => $value)
								<option value="{{ $value->id }}">{{ $value->city_name }}</option>
							@endforeach
						</select>
						</div>
                  </div>
				  <?php //echo "<pre>"; print_r($area); ?>
				  
				   <div class="form-group">
                     <label class="col-lg-3 control-label">{!! Form::label('Recommended Category:') !!}</label>
                       <div class="col-lg-8"><select class="form-control js-example-tokenizer" name="recommended_cat[]" multiple="multiple" style="width:100%;">
						<option value="">--- Select Category ---</option>
							@foreach ($category as $key => $value)
								<option value="{{ $value->id }}">{{ $value->category_name }}</option>
							@endforeach
					</select></div>
                  </div>
				  
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Gender:</label>
                     <div class="col-lg-8">
						<input type="radio" id="male" name="gender" value="Male">
						  <label for="Male">Male</label><br>
						  <input type="radio" id="female" name="gender" value="Female">
						  <label for="Female">Female</label>
                     </div>
                  </div>
				   <div class="form-group">
                     <label class="col-lg-3 control-label">Age:</label>
                     <div class="col-lg-8">
                        <input class="form-control" type="text" name="age" maxlength="3" value="">
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">User Type*:</label>
                     <div class="col-lg-8">
                        <div class="dropdown">
                           <select class="custom-select btn btn-primary" name="user_type" id="" required>
                              <option value="">Choose...</option>
                              <option value="VU">Vendor</option>
                              <option value="CU">Customer</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <!--<div class="form-group">
                     <label class="col-lg-3 control-label">Registered On:</label>
                     <div class="col-lg-8">
                        <input class="form-control"  type="date" name="regdate" value="">
                     </div>
                  </div>-->
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Status*:</label>
                     <div class="col-lg-8">
                        <div class="dropdown">
                           <select class="custom-select btn btn-primary" name="status" id="inputGroupSelect01" required>
                              <option value="">Choose...</option>
                              <option value="1">Active</option>
                              <option value="2">Deactive</option>
                           </select>
                        </div>
                     </div>
                  </div>
				  <div class="col-md-12 col-sm-12 col-xs-12 text-center">
					<input type="submit" name="submit" class="btn btn-primary" value="Submit">
				</div>
               <!--</form>-->
			   {!! Form::close() !!}
				
            </div>
            <!--<div class="col-md-12 col-sm-12 col-xs-12 text-center">
               <input type="button" class="btn btn-primary" value="Save">
            </div>-->
         </div>
      <!-- </div> -->
   </section>
</div>
<!--add plugin for multi select-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<!--end-->

<script type="text/javascript">
 var select2 = $(".js-example-tokenizer").select2({
   tags: true,
   tokenSeparators: [',', ' '],
});
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="city"]').on('change', function() {
            var city_id = $(this).val();
			//alert(city_id);
            if(city_id) {
                $.ajax({
                    url: 'http://demoyourprojects.website/projects/directoryapp/dev/da-admin/users/getAreas/'+city_id,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        console.log(data.record); //return false;
                        $('select[name="area"]').empty();
                        $.each(data.record, function(key, value) {
                            $('select[name="area"]').append('<option value="'+ value.id +'">'+ value.location_name +'</option>');
                        });

                    }
                });
            }else{
                $('select[name="area"]').empty();
            }
        });
    });
</script>
@endsection