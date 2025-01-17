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
               <li class="breadcrumb-item"> <a href="{{ route('business.list') }}">Busines Management
                  </a>
               </li>
               <li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',$record->id) }}" title="View">View</a></li>
               <li class="breadcrumb-item active" aria-current="page">{{$record->business_name}}</li>
               <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default change-status" value="Active / Inactive" data-id="{{ $record->id }}" data-model="Business"><?php echo ($record->status =='Active')? 'Active' : 'Inactive' ?> </a></li>
                  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" onClick="destroyData('{{ URL::Route($routePrefix.'.delete', $record->id) }}')" value="Delete">Delete</a></li>
				   <?php
					if($record->approved_by_admin==0 || $record->approved_by_admin==1)
					{
				   ?>
                  <li class="breadcrumb-item"><a class="btn btn-default" href="{{ URL::Route($routePrefix.'.edit', $record->id) }}" value="edit">Edit</a></li>
				  <?php } ?>
               </ul>
            </ol>
         </nav>
         <div class="row">
            <!-- right column -->
			<?php
			//dd($businessimages);
			?>
            <div class=" col-sm-6 col-xs-12 right">
            <div id="myModal" class="modal1">
                  <span class="close cursor" onclick="closeModal()">&times;</span>
                  <div class="modal-content">
				  @foreach($businessimages as $image)
                     <div class="mySlides">
                        <div class="numbertext">{{$loop->index+1}} / {{$businessimages->count()}}</div>
                        <img src="{{asset('uploads/business_images')}}/{{$image->image}}" style="width:100%">
                     </div>
				 @endforeach	 
                     <!--<div class="mySlides">
                        <div class="numbertext">2 / 4</div>
                        <img src="{{asset('uploads/images_admin')}}/img_snow_wide.jpg" style="width:100%">
                     </div>
                     <div class="mySlides">
                        <div class="numbertext">3 / 4</div>
                        <img src="{{asset('uploads/images_admin')}}/img_mountains_wide.jpg" style="width:100%">
                     </div>
                     <div class="mySlides">
                        <div class="numbertext">4 / 4</div>
                        <img src="{{asset('uploads/images_admin')}}/img_lights_wide.jpg" style="width:100%">
                     </div>-->
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
                        <img class="demo cursor" src="{{asset('uploads/business_images')}}/{{$image->image}}" style="width:100%" onclick="currentSlide({{$loop->index+1}})" alt="Nature and sunrise">
                     </div>
					@endforeach 
                     <!--<div class="column">
                        <img class="demo cursor" src="{{asset('uploads/images_admin')}}/img_snow_wide.jpg" style="width:100%" onclick="currentSlide(2)" alt="Snow">
                     </div>
                     <div class="column">
                        <img class="demo cursor" src="{{asset('uploads/images_admin')}}/img_mountains_wide.jpg" style="width:100%" onclick="currentSlide(3)" alt="Mountains and fjords">
                     </div>
                     <div class="column">
                        <img class="demo cursor" src="{{asset('uploads/images_admin')}}/img_lights_wide.jpg" style="width:100%" onclick="currentSlide(4)" alt="Northern Lights">
                     </div>-->
                  </div>
               </div>
               <div class="row pab">
			   @foreach($businessimages as $image)
                  <div class="column1">
                     <img src="{{asset('uploads/business_images')}}/{{$image->image}}" style="width:100%" onclick="openModal();currentSlide({{$loop->index+1}})" class="hover-shadow cursor">
                  </div>
				@endforeach
                  <!--<div class="column1">
                     <img src="{{asset('uploads/images_admin')}}//img_snow.jpg" style="width:100%" onclick="openModal();currentSlide(2)" class="hover-shadow cursor">
                  </div>
                  <div class="column1">
                     <img src="{{asset('uploads/images_admin')}}//img_mountains.jpg" style="width:100%" onclick="openModal();currentSlide(3)" class="hover-shadow cursor">
                  </div>
                  <div class="column1">
                     <img src="{{asset('uploads/images_admin')}}//img_lights.jpg" style="width:100%" onclick="openModal();currentSlide(4)" class="hover-shadow cursor">
                  </div>-->
               </div>
               
            </div>
            <!-- left edit form column -->
            <div class="col-sm-6 col-xs-12 personal-info left">
               <form class="form-horizontal" role="form">
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Business Name:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $record->business_name }}</label>
                     </div>
                  </div>
				   <div class="form-group">
                     <label class="col-lg-3 control-label">Category:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $catname->category_name }}</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Subcategory:</label>
                     <div class="col-lg-8">
                        <label class="control-label">
						<?php
						//echo "<pre>"; print_r($subcatname);
						//exit;
						if($subcatname)
						{
							echo $subcatname->subcategory_name;
						}
						else
						{
							echo "N.A";
						}
						?>
						</label>
                     </div>
                  </div>
				   @php
					   $tagArr=json_decode($record->search_tag);
				  @endphp	 
				   <div class="form-group">
                     <label class="col-lg-3 control-label">Tag:</label>
                     <div class="col-lg-8">
                        <label class="control-label">
						<?php
						if(is_array($tagArr)){
							echo implode(', ', $tagArr);
						}
						?>	
						</label>
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Mobile Number:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $record->business_phone }}</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Email Address:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $record->business_email }}</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">City:</label>
                     <div class="col-lg-8">
                        <label class="control-label">
						<?php
						if($cityname)
						{
							echo $cityname->city_name;
						}
						else
						{
							echo "N/A";
						}
						?>
						</label>
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Business Type:</label>
                     <div class="col-lg-8">
                        <label class="control-label">
						<?php
						if($record->business_type==1){echo "Online Only";}
						else if($record->business_type==2){echo "On Site";}
						else {echo "Service";}
						?>
						</label>
                     </div>
                  </div>
				  <?php
					if($record->business_type==3){
				  ?>
				   <div class="form-group">
                     <label class="col-lg-3 control-label">Radius:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $record->radius }}</label>
                     </div>
                  </div>
				<?php } ?>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Address:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $record->address }}</label>
                     </div>
                  </div>
				  
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Post Code:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $record->post_code }}</label>
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Address Show To Public:</label>
                     <div class="col-lg-8">
                        <label class="control-label">
						<?php
						if($record->show_public==0){echo "No";}
						else{echo "Yes";}
						?>
						</label>
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Featured Business:</label>
                     <div class="col-lg-8">
                        <label class="control-label">
						<?php
						if($record->featured==0){echo "No";}
						else{echo "Yes";}
						?>
						</label>
                     </div>
                  </div>
                 
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Website Link:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $record->business_website }}</label>
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Facebook Link:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $record->facebook_link }}</label>
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Twitter Link:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $record->twitter_link }}</label>
                     </div>
                  </div>
				  <div class="form-group">
                     <label class="col-lg-3 control-label">Instagram Link:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $record->instagram_link }}</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Registered On:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $record->created_at->format('d.m.Y') }}</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Status:</label>
                     <div class="col-lg-8">
                        <label class="control-label">{{ $record->status }}</label>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-lg-3 control-label">Description:</label>
                     <div class="col-lg-8">
                        <p>{{ $record->description }}</p>
                     </div>
                  </div>
               </form>
            </div>
            <div class="col-md-3 col-md-offset-9">    
               <?php
					if($record->approved_by_admin == 0)
					{
				?>
               <input type="reset" class="btn btn-default approved" value="Approve" id="{{$record->id}}">    
               <input type="reset" class="btn btn-default rejected" value="Reject" id="{{$record->id}}"> 
					<?php } ?>
            </div>
         </div>
      <!-- </div> -->
   </section>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.rejected').on('click', function(e) {
		var rejected_id = this.id; 
		//alert(rejected_id); return false;
		if (!confirm("Do you want to reject?")){
		  return false;
		}
		else{
			$.ajax({
                    url: 'http://demoyourprojects.website/projects/directoryapp/dev/da-admin/business/businessRejected/'+rejected_id,
                    type: "POST",
                    success:function(data) {
						location.reload();
                       console.log(data); return false;
                    }
                });
			}		
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.approved').on('click', function(e) {
		var approved_id = this.id; 
		//alert(approved_id); return false;
		if (!confirm("Do you want to approve?")){
		  return false;
		}
		else{
			$.ajax({
                    url: 'http://demoyourprojects.website/projects/directoryapp/dev/da-admin/business/businessApproved/'+approved_id,
                    type: "POST",
                    success:function(data) {
						location.reload();
                       console.log(data); return false;
                    }
                });
			}		
        });
    });
</script>
@endsection