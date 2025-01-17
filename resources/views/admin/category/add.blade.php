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
               <li class="breadcrumb-item"><a class="link" href="{{ route('category.list') }}">Category Management</a></li>
               <li class="breadcrumb-item"><a href="{{ route($routePrefix.'.add') }}">Add New</a></li>
               <!--<li class="breadcrumb-item active" aria-current="page">Jhon Doe</li>-->
               <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                 
                  <!--<li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Delete">Delete</a></li>-->
                  <!--<li class="breadcrumb-item"><a class="btn btn-default" value="edit">Edit</a></li>-->
               </ul>
            </ol>
         </nav>
    

	<div class="row">
  
      
      <!-- left edit form column -->
      <div class="col-md-9 col-sm-12 col-xs-12 personal-info left mt50">
        <!--<form class="form-horizontal" role="form">-->
		{{ Form::open(array('route'=>array($routePrefix.'.store'), 'name'=>'', 'class'=>'form-horizontal', 'autocomplete'=>'off', 'enctype'=>'multipart/form-data')) }} 
          <div class="form-group row">
          	<div class="col-md-12 col-sm-12 col-xs-12">
            <label class="col-lg-3 control-label">Category Name:</label>
            <div class="col-lg-8">
            <input class="form-control" type="text" name="cat_name" value="" required>
            </div>
        </div>
          </div>
		  
		  <div class="form-group row">
		  	<div class="col-md-12 col-sm-12 col-xs-12">
            <label class="col-lg-3 control-label">Category Icon:</label>
            <div class="col-lg-8">
				<input class="inputfile" type="file" name="cat_icon">
            </div>
        </div>
          </div>
          
          <div class="form-group row">
          	<div class="col-md-12 col-sm-12 col-xs-12">
            <label class="col-lg-3 control-label">Sub Categories:</label>
				<div class="col-lg-8">
						<div>
							<input class="form-control ab" type="text" name="field_name[]" value=""/>
							<a href="javascript:void(0);" class="add_button" title="Add field"><i class="fa fa-plus-circle f28" aria-hidden="true"></i></a>
						</div>										
				</div>						
          </div>
      </div>
         
                 <!--<div class="form-group">
                  <label class="col-lg-3 control-label"></label>
                  <div class="col-lg-8">
                    <a class="link ptc"><i class="fa fa-plus-circle f28" aria-hidden="true"></i></a>
                  </div>
                </div>-->
        <!--</form>-->
		<div class="col-md-12 col-sm-12 col-xs-12 text-center">
			<input type="submit" name="submit" class="btn btn-primary" value="Save">
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

<script type="text/javascript">
$(document).ready(function(){
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div><input class="form-control ab" type="text" name="field_name[]" value=""/><a href="javascript:void(0);" class="remove_button"><i class="fa fa-times-circle f28" aria-hidden="true"></i></a></div>'; //New input field html 
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
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});
</script>

@endsection