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
         <li>Edit</li>
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
      <!-- <div class="container "> -->
         <nav aria-label="breadcrumb mt50">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="{{ route('business.list') }}">Busines Management
                  </a>
               </li>
               <li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',$record->id) }}" title="View">View</a></li>
               <li class="breadcrumb-item active" aria-current="page">{{$record->business_name}}</li>
               <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default change-status" value="Active / Inactive" data-id="{{ $record->id }}" data-model="Business"><?php echo ($record->status =='Active')? 'Active' : 'Inactive' ?> </a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" onClick="destroyData('{{ URL::Route($routePrefix.'.delete', $record->id) }}')" value="Delete">Delete</a></li>
                  <!--<li class="breadcrumb-item"><a class="btn btn-default" value="edit">Edit</a></li>-->
               </ul>
            </ol>
         </nav>
         <div class="row">
		 <?php
		 //echo "<pre>"; print_r($subcategory);
		 ?>
            <!-- right column -->

            <div class="col-sm-6 col-xs-12 right">

            <div id="myModal" class="modal1">
                  <span class="close cursor" onclick="closeModal()">&times;</span>
                  <div class="modal-content">
				  @foreach($businessimages as $image)
                     <div class="mySlides">
                        <div class="numbertext">{{$loop->index+1}} / {{$businessimages->count()}}</div>
                        <img src="{{asset('uploads/business_images')}}/{{$image->image}}" style="width:100%">
                     </div>
				  @endforeach
                      <?php
					 foreach($businessimages as $image)
					 if(COUNT($businessimages)>1){
					 ?>
                     <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                     <a class="next" onclick="plusSlides(1)">&#10095;</a>
					 <?php } ?>
                     <!-- <div class="caption-container">
                        <p id="caption"></p>
                     </div> -->
					@foreach($businessimages as $image)
                     <div class="column">
                        <img class="demo cursor" src="{{asset('uploads/business_images')}}/{{$image->image}}" style="width:100%" onclick="currentSlide({{$loop->index+1}} / {{$businessimages->count()}})" alt="Nature and sunrise">
                     </div>
				   @endforeach	
                  </div>
               </div>
               <div class="row pab">
			   @foreach($businessimages as $image)
                  <div class="column1">
                     <img src="{{asset('uploads/business_images')}}/{{$image->image}}" style="width:100%" onclick="openModal();currentSlide({{$loop->index+1}})" class="hover-shadow cursor">
					 <a id="{{$image->id}}" class="remove" data-id="{{$id}}"> <i class="fa fa-trash text-red"></i> </a>
                  </div>
			  @endforeach
               </div>
               <div class="addm">
                   <!-- <input type="file" id="file1" class="addmore ptc" ><i class="fa fa-plus fa-6" ></i>&nbsp;&nbsp; <span>Add More</span> -->
                   <label for="file1" class="custom-file-upload">
                        <i class="fa fa-plus fa-6"></i> Add More
                    </label>
                    <input id="file1" name='upload_cont_img' type="file" style="display:none;">

                </div>
                
               <div id="file-upload-filename"></div>
               <!-- <input type="file" id="filecam" class="imgcam"> -->
               
               
			   <input type="hidden" name="business_id" id="business_id" value="{{$id}}">
            </div>
            <!-- left edit form column -->
            <div class="col-sm-6 col-xs-12 personal-info left">
			<?php
			//dd($area);
			?>
			{{ Form::open(array('route'=>array($routePrefix.'.update',$id), 'class'=>'form-horizontal', 'name'=>'', 'autocomplete'=>'off', 'enctype'=>'multipart/form-data')) }}
                    {{ Form::hidden('user_type', GlobalVars::TEACHER_USER_TYPE, array('id' => 'user_type')) }}
                   
               <!--<form class="form-horizontal" role="form">-->
                  <div class="form-group">
                    <label class="col-lg-3 control-label">{!! Form::label('Business Name*:') !!}</label>
                       <div class="col-lg-8">{{ Form::text('business_name', $record->business_name, array('required','class' => 'form-control','id' => 'business_name','placeholder' => 'Enter Business Name')) }}</div>
                  </div>
				  
				  <div class="form-group">
					  <label class="col-lg-3 control-label">{!! Form::label('Category*:') !!}</label>
						 <div class="col-lg-8">
							 <select name="business_category" id="business_category" required class="form-control">
								<option value="">--- Select Category ---</option>
								@foreach ($category as $value)
									<option value="{{ $value->id }}"{{ ( $record->business_category == $value->id ) ? ' selected' : '' }}>{{ $value->category_name }}</option>
								@endforeach
								<option value="others">Others</option>
							</select>
						</div>
                  </div>
				  
				  <div  class="hide_others">
					  <div class="form-group">
						<label class="col-lg-3 control-label">{!! Form::label('Add Category:') !!}</label>
						   <div class="col-lg-8"><input class="form-control" type="text" name="add_cat" value="" placeholder="Add Category"></div>
					  </div>
					  
					  <div class="form-group">
						<label class="col-lg-3 control-label">{!! Form::label('Add Sub Category:') !!}</label>
						   <div class="col-lg-8"><input class="form-control" type="text" name="add_sub_cat" value=""placeholder="Add Sub Category"></div>
					  </div>
				  </div>
				  
				  <div class="form-group hide_sub_cat">
                      <label class="col-lg-3 control-label">{!! Form::label('Sub Category*:') !!}</label>
					  <div class="col-lg-8">
							 <select name="business_subcategory" id="business_subcategory" class="form-control">
								<option value="">--- Select Sub Category ---</option>
								@foreach ($subcategory as $value)
									<option value="{{ $value->id }}"{{ ( $record->business_subcategory == $value->id ) ? ' selected' : '' }}>{{ $value->subcategory_name }}</option>
								@endforeach
							</select>
						</div>
                  </div>
				 @php
					   $tagArr=json_decode($record->search_tag);
				@endphp	   
				  <div class="form-group">
                     <label class="col-lg-3 control-label">{!! Form::label('Search Tag:') !!}</label>
                       <div class="col-lg-8"><select class="form-control js-example-tokenizer" name="search_tag[]" multiple="multiple" style="width:100%;">
						@php
						for($i=0; $i < count($tags); $i++){
							
							if(is_array($tagArr) && in_array($tags[$i]->tag_name,$tagArr)){	
								$selected='selected';
							}else{
								$selected="";
							}
						@endphp
							
						<option <?php echo $selected; ?> value="{{$tags[$i]->tag_name}}">{{$tags[$i]->tag_name}}</option>
					@php
						}
						@endphp
					</select></div>
                  </div>

				  
				  <div class="form-group">
                     <label class="col-lg-3 control-label">{!! Form::label('Mobile Number*:') !!}</label>
                       <div class="col-lg-8">{{ Form::text('business_phone', $record->business_phone, array('required','class' => 'form-control','id' => 'phone','placeholder' => 'Enter your mobile number')) }}</div>
                  </div>
				  
                  <div class="form-group">
                    <label class="col-lg-3 control-label">{!! Form::label('Email Address*:') !!}</label>
                        <div class="col-lg-8">{{ Form::text('business_email', $record->business_email, array('required','class' => 'form-control','id' => 'email','placeholder' => 'Enter email address')) }}</div>
                  </div>
				  
				  <div class="form-group">
                    <label class="col-lg-3 control-label">{!! Form::label('Website Link:') !!}</label>
                       <div class="col-lg-8">{{ Form::text('business_website', $record->business_website, array('class' => 'form-control','id' => 'phone','placeholder' => 'Enter Website Link')) }}</div>
                  </div>
				  
				  <div class="form-group">
                    <label class="col-lg-3 control-label">{!! Form::label('Facebook:') !!}</label>
                       <div class="col-lg-8">{{ Form::text('facebook_link', $record->facebook_link, array('class' => 'form-control','id' => 'facebook','placeholder' => 'Enter Website Link')) }}</div>
                  </div>
				  
				  <div class="form-group">
                    <label class="col-lg-3 control-label">{!! Form::label('Twitter:') !!}</label>
                       <div class="col-lg-8">{{ Form::text('twitter_link', $record->twitter_link, array('class' => 'form-control','id' => 'twitter','placeholder' => 'Enter Website Link')) }}</div>
                  </div>
				  
				  <div class="form-group">
                    <label class="col-lg-3 control-label">{!! Form::label('Instagram:') !!}</label>
                       <div class="col-lg-8">{{ Form::text('instagram_link', $record->instagram_link, array('class' => 'form-control','id' => 'instagram','placeholder' => 'Enter Website Link')) }}</div>
                  </div>
				  
				  <div class="form-group">
					<label class="col-lg-3 control-label">{!! Form::label('City*:') !!}</label>
						 <div class="col-lg-8">
							 <select name="city" id="city_id" class="form-control">
								<option value="">--- Select City ---</option>
								@foreach ($city as $key => $value)
									<option value="{{ $value->id }}"{{ ( $record->city == $value->id ) ? ' selected' : '' }}>{{ $value->city_name }}</option>
								@endforeach
							</select>
						</div>
                  </div>
				  
				  <div class="form-group">
                     <label class="col-lg-3 control-label">{!! Form::label('Business Type*:') !!}</label>
                         <div class="col-lg-8">{!! Form::select('business_type', ['' => 'Choose business type','1' => 'Online only','2' => 'On-site','3' => 'Service'], $record->business_type, array('required', 'id' => 'business_type', 'class'=>'form-control')) !!}</div>
                  </div>
				  
				  <div class="form-group radius-box">
					<label for="radius" class="col-sm-3 col-form-label">Radius (Miles)*:</label>
					   <div class="col-sm-9">
						   <div class="range-control">
					   <input type="range" name="radius"  min="1" max="20" step="1" value="{{$record->radius}}"  data-thumbwidth="0" onchange="updateTextInput(this.value);">
					   <!--<output name="rangeVal">{{$record->radius}}</output>-->
					  <div class="range-filed text-left mt-2">
						   
						   Miles: <input type="text" name="radius" value="<?php if($record->radius=='0'){
						   echo 1;} else {echo $record->radius;} ?>" disabled id="textInput"  style="border:none; background:none;">
					  </div>
					   <script>
						   function updateTextInput(val) {
							 document.getElementById('textInput').value=val;
						   }
					   </script>
					 
						   </div>
					   </div>
                  </div>

				  
				   <div class="form-group">
                     <label class="col-lg-3 control-label">{!! Form::label('Address*:') !!}</label>
                       <div class="col-lg-8">{{ Form::textarea('address', $record->address, array('required','class' => 'form-control','id' => 'address','placeholder' => 'Enter Address')) }}</div>
                  </div>
				  
				  <div class="form-group">
				  <label class="col-lg-3"></label>
				  <div class="col-lg-8">
          <label>{!! Form::label('Do you want your address to be made visible ?') !!}</label>
				  <input class="right" type="checkbox" name="show_public" id="show_public" {{$record->show_public == 1 ? ' checked' : ''}}></div>
				  </div>
				  
				  <div class="form-group">
                     <label class="col-lg-3 control-label">{!! Form::label('Post Code*:') !!}</label>
                       <div class="col-lg-8">{{ Form::text('post_code', $record->post_code, array('required','class' => 'form-control','id' => 'post_code','placeholder' => 'Enter Post Code')) }}</div>
                  </div>
                 
                  <div class="form-group">
                     <label class="col-lg-3 control-label">{!! Form::label('Description:') !!}</label>
                       <div class="col-lg-8">{{ Form::textarea('description', $record->description, array('class' => 'form-control','id' => 'description','placeholder' => 'Enter Description')) }}</div>
                  </div>
				  
				  <div class="form-group">
				  <label class="col-lg-3"></label>
				  <div class="col-lg-8">
          <label>{!! Form::label('Would you like to be listed as a featured business ?') !!}</label>
				  <input class="right"  type="checkbox" name="featured" id="featured" {{$record->featured == 1 ? ' checked' : ''}}></div>
				  </div>
				  
				  <div class="form-group">
                     <label class="col-lg-3 control-label">{!! Form::label('Status:') !!}</label>
                         <div class="col-lg-8">{!! Form::select('status', ['' => 'Select Status','Active' => 'Active','Inactive' => 'Inactive'], $record->status, array('required', 'id' => 'status', 'class'=>'form-control')) !!}</div>
                  </div>
				  
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a class="btn btn-default" href="{{ \Helpers::getCancelbuttonUrl($routePrefix,\Request::get('from')) }}">Cancel</a>
                    </div>
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

<script>
$(document).ready(function(){
  $(".hide_others").hide();
    $("#business_category").change(function(){
		var selectedText = $(this).find("option:selected").text();
		  if ( selectedText == 'Others')
		  {
			$(".hide_others").show();
			$(".hide_sub_cat").hide();
		  }
		  else
		  {
			$(".hide_others").hide();
			$(".hide_sub_cat").show();
		  }
    });
});
</script>

<script type="text/javascript">
$(document).ready(function() {
	var selValue=$('#business_type').find('option:selected').val();
	if(selValue== '3')
	{
		$(".radius-box").show();
	}
	else
	{
		$(".radius-box").hide();
	}
	
	 $('#business_type').on('change', function() {
		  if ( this.value == '3')
		  {
			$(".radius-box").show();
		  }
		  else
		  {
			$(".radius-box").hide();
		  }
	});
});
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="business_category"]').on('change', function() {
            var cat_id = $(this).val();
			//alert(url);
            if(cat_id) {
                $.ajax({
                    url: 'http://demoyourprojects.website/projects/directoryapp/dev/da-admin/business/getSubCategory/'+cat_id,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        console.log(data.record);
                        $('select[name="business_subcategory"]').empty();
                        $.each(data.record, function(key, value) {
                            $('select[name="business_subcategory"]').append('<option value="'+ value.id +'">'+ value.subcategory_name +'</option>');
                        });

                    }
                });
            }else{
                $('select[name="business_subcategory"]').empty();
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="city"]').on('change', function() {
            var city_id = $(this).val();
			//alert(url);
            if(city_id) {
                $.ajax({
                    url: 'http://demoyourprojects.website/projects/directoryapp/dev/da-admin/business/getAreas/'+city_id,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        console.log(data.record);
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#file1').on('change', function(e) {
			<!---- Validation -->
			var fileInput = document.getElementById('file1');
			var filePath = fileInput.value;
			var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
			if(!allowedExtensions.exec(filePath)){
				alert('Please upload file having extensions .jpeg/.jpg/.png only.');
				fileInput.value = '';
				return false;
			}
			<!---- Validation -->
			var file_data = $("#file1").prop("files")[0]; 
			
			<!---- 23-02-2021 -->
			$(this).addClass("uplimage");
			var i = $(this).prev('label').clone();
			var file = $('#file1')[0].files[0].name;
			$(this).prev('label').text(file);
			<!---- 23-02-2021 -->
			
			/*var fileExtension = ['jpeg', 'jpg', 'png'];
			if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) 
			{
				alert("Only '.jpeg','.jpg', '.png' formats are allowed.");
				return false;
			}*/
			console.log(file_data);//return ;
			var form_data = new FormData();
			form_data.append("fileinfo", file_data);
			console.log(form_data);
			$.ajax({
                    url: 'http://demoyourprojects.website/projects/directoryapp/dev/da-admin/business/imageUpload/'+$("#business_id").val(),
                    type: "POST",
                    dataType: "text",
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,  
                    success:function(data) {
                       // console.log(data.record);
					   if(data=='0')
					   {
						   alert('Sorry! You can not upload more image.');
					   }
                    }
                });
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.remove').on('click', function(e) {
			
		if (!confirm("Do you want to remove?")){
		  return false;
		}
		else
		{
			var remove_id = this.id; 
			var businessid = $(this).attr("data-id");
			//alert(remove_id); 
			$.ajax({
                    url: "http://demoyourprojects.website/projects/directoryapp/dev/da-admin/business/imageDelete/"+remove_id+"/"+businessid,
                    type: "POST",
                    success:function(data) {
                       console.log(data);
					   //return false;
					   if(data=='0')
					   {
						   alert('Sorry! You can not delete any more.');
						   return false;
					   }
					   else
					   {
							location.reload(); return false;
					   }
                    }
                });
		}		
        });
    });
</script>	


<script>
/*$('#file1').change(function() {
    $(this).addClass("uplimage");
    var i = $(this).prev('label').clone();
    var file = $('#file1')[0].files[0].name;
    $(this).prev('label').text(file);
  });*/
</script>

@endsection