@extends('admin.layouts.default')
@section('title', $management.' '.$pageType)
@section('content')
<div class="content-wrapper">
    <!-- Content Header Section Start -->
    <section class="content-header">
        <h1> {{ $management.' '.$pageType }}</h1>
        <!-- Breadcrumb Start -->
        <ol class="breadcrumb">
            @foreach($breadcrumb['EDITPAGE'] as $eachBreadcrumb)
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
		<nav aria-label="breadcrumb mt50">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a class="link" href="{{ route('category.list') }}">Category Management</a></li>
               <li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',$record->id) }}" title="View">View</a></li>
               <li class="breadcrumb-item active" aria-current="page">{{ $record->category_name }}</li>
               <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  <!--<li class="breadcrumb-item"><a class="btn btn-default" value="edit">Edit</a></li>-->
               </ul>
            </ol>
         </nav>
		 
			<div class="row">
            <!-- right column -->
			{{ Form::open(array('route'=>array($routePrefix.'.update',$id), 'class'=>'form-horizontal', 'name'=>'', 'autocomplete'=>'off', 'enctype'=>'multipart/form-data')) }}
                    {{ Form::hidden('user_type', GlobalVars::TEACHER_USER_TYPE, array('id' => 'user_type')) }}
            		
        
            <div class="col-md-9 col-sm-12 col-xs-12 personal-info left mt50">
    
				<div class="col-md-12">
					<div class="form-group">
						<label class="col-lg-3 control-label">{!! Form::label('Category Name') !!}</label>
						<div class="col-lg-8">{{ Form::text('cat_name', $record->category_name, array('required','class' => 'form-control','id' => 'cat_name','placeholder' => 'Category Name')) }}</div>
					</div>
				</div>
				
				<div class="col-md-12 mb30">
				  <div class="form-group">
					<label class="col-lg-3 control-label">{!! Form::label('Category Icon') !!}</label>
					<div class="col-lg-8 cat">
					 <img src="{{asset('uploads/category_images')}}/{{ $record->cat_icon }}" class="cat_img">
					  <input type="file" id="file" name="cat_icon" class="imgcam">
				   </div>
				   </div>
				</div> 
                        	
				<div class="col-md-12">
					<div class="form-group">
						<label class="col-lg-3 control-label">{!! Form::label('Sub Categories') !!}</label>
						<div class="col-lg-8">
							<div class="field_wrapper">
								
								<?php
								for($i=0; $i<COUNT($subcategory); $i++)
								{ ?>
							<div>
							<?php
							//class="remove_button"
									if($i==0)
									{
								?>
									
									<input type="text" class="form-control ab" name="field_name[]" value="<?php echo $subcategory[$i]->subcategory_name;?>"/>
									<input type="hidden" name="field_id[]" value="<?php echo $subcategory[$i]->id;?>"/>
									<a href="javascript:void(0);" class="add_button" title="Add field"><i class="fa fa-plus-circle f28" aria-hidden="true"></i></a>
								<?php }else{ ?>	
								<input type="text" class="form-control ab" name="field_name[]" value="<?php echo $subcategory[$i]->subcategory_name;?>"/>
								<input type="hidden" name="field_id[]" value="<?php echo $subcategory[$i]->id;?>"/>
									<a href="javascript:void(0);" class="remove_button" data-id="<?php echo $subcategory[$i]->id;?>" title="Remove field"><i class="fa fa-times-circle f28" aria-hidden="true"></i></a>
									
								<?php } ?></div><?php } ?>		
								
							</div>
						</div>
					</div>
				</div>
							
				<div class="col-md-12 col-sm-12 col-xs-12 text-center">
						<button type="submit" class="btn btn-primary">Submit</button>
						<a class="btn btn-default" href="{{ \Helpers::getCancelbuttonUrl($routePrefix,\Request::get('from')) }}">Cancel</a>
				</div>
				
                  {!! Form::close() !!}
           </div>
           
		   </div>
        
    </section>
</div>

<script type="text/javascript">
$(document).ready(function(){	
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div><input type="text" class="form-control ab" name="field_name[]" value=""/><input type="hidden" name="field_id[]" value=""/><a href="javascript:void(0);" data-id="" class="remove_button"><i class="fa fa-times-circle f28" aria-hidden="true"></i></a></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
		var removed_id =$(this).data("id");
		//alert(removed_id);
		if (!confirm("Do you want to remove?")){
		  return false;
		}
		else if(removed_id=='')
		{
			location.reload();
			$(this).parent('div').remove(); //Remove field html
			x--; //Decrement field counter
		}
		else{
			$.ajax({
                    url: 'http://demoyourprojects.website/projects/directoryapp/dev/da-admin/category/subcatRemoved/'+removed_id,
                    type: "POST",
                    success:function(data) {
						//location.reload();
						//console.log(data);
						//alert(data);
						if(data==0)
						{
							location.reload();
							$(this).parent('div').remove(); //Remove field html
							x--; //Decrement field counter
							
							//return false;
						}
						if(data=='1')
						{
							alert('This sub category already used with a business. You can not remove it.');
							return false;
						}
						
                    }
                });
			}
        //$(this).parent('div').remove(); //Remove field html
        //x--; //Decrement field counter
    });
	
	
});
</script>


@endsection