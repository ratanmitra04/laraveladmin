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
      <li class="breadcrumb-item"><a href="{{ route('category.list') }}">Category Management</a></li>
       <li class="breadcrumb-item"><a class="link" href="{{ URL::Route($routePrefix.'.view',$record->id) }}" title="View">View</a></li>
               <li class="breadcrumb-item active" aria-current="page">{{ $record->category_name }}</li>

      <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
                  <li class="breadcrumb-item"><a type="reset" href="{{ route($routePrefix.'.edit', $record->id) }}" class="btn btn-default" value="Edit"> Edit</a></li>
				  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" onClick="destroyData1('{{ URL::Route($routePrefix.'.delete', $record->id) }}')" value="Delete">Delete</a></li>
                  <!--<li class="breadcrumb-item"><a type="reset" class="btn btn-default" value="Delete">Delete</a></li>-->
               </ul>
    </ol>
  </nav>

	<div class="row">
  
      
      <!-- left edit form column -->
      <div class="col-md-9 col-sm-12 col-xs-12 personal-info ">
        <form class="form-horizontal" role="form">
          <div class="form-group">
            <label class="col-lg-3 control-label">Category Name:</label>
            <div class="col-lg-8">
              <label class="control-label">{{ $record->category_name }}</label>
            </div>
          </div>
		  
		  <div class="form-group">
            <label class="col-lg-3 control-label">Category Icon:</label>
            <div class="col-lg-8">
			<?php
			if($record->cat_icon!='')
			{
			?>
              <label class="control-label"><img src="{{asset('uploads/category_images')}}/{{ $record->cat_icon }}" class="cat_img"></label>
			<?php }else{ ?>
			 <label class="control-label"><?php echo "No icon found."; ?></label>
			<?php } ?>
            </div>
          </div>
		  
          <?php
		  //echo "<pre>";print_r($subcategory);exit;
		  ?>
          <div class="form-group">
            <label class="col-lg-3 control-label">Sub Categories:</label>
            <div class="col-lg-8">
              <label class="control-label">
			  <?php
			  $num_count = 0;
			  for($i=0; $i<COUNT($subcategory); $i++)
			  {
				  $num_count = $num_count + 1;
				  echo $subcategory[$i]->subcategory_name;
				  if ($num_count < COUNT($subcategory)) 
				  {
					  echo ",";
				  }
			  ?>
			  <?php } ?>
			  </label>
            </div>
          </div>
         
          
        </form>
      </div>
            
     

  </div>
<!-- </div> -->
    </section>
</div>
<script>
function destroyData1(destroyURL)
{  
    bootbox.confirm({
        message: "<h4>Are you sure want to delete this record?</h4>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check-circle"></i> Confirm',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times-circle"></i> Cancel',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if(result)
            {
                $.ajax({
                    url: destroyURL,
                    type: "POST",
                    success:function(data) {
						//location.reload();
						//console.log(data);
						//alert(data);
						if(data.status==200)
						{
							if(data.is_delete==1){
								window.location.href='http://demoyourprojects.website/projects/directoryapp/dev/da-admin/category';
							}
							else
							{
								alert('This category already used with a business. You can not delete it.');
							}
						}
						else
						{
							alert('Something went wrong.');
						}
						
                    }
                });
            }
        }
    });            
}
</script>
@endsection