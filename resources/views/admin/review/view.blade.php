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
      <li class="breadcrumb-item"> <a href="{{ route('review.list') }}">Review Management
	          </a></li>
      <li class="breadcrumb-item"><a href="#">View</a></li>
	  <li class="breadcrumb-item active" aria-current="page">{{ $record->first_name }} {{ $record->last_name }}</li>

      <ul class="righttabs pull-right">
                  <li class="breadcrumb-item"><a class="btn btn-default" value="Back" href="{{ route($routePrefix.'.list') }}">Back</a></li>
				  <li class="breadcrumb-item"><a type="reset" class="btn btn-default" onClick="destroyData('{{ URL::Route($routePrefix.'.delete', $record->id) }}')" value="Delete">Delete</a></li>
               </ul>
    </ol>
  </nav>
	<div class="row">
      <!-- left edit form column -->
      <div class="col-sm-12 col-xs-12 personal-info ">
        <form class="form-horizontal" role="form">          
          <div class="form-group">
            <label class="col-lg-3 control-label">Review By:</label>
            <div class="col-lg-8">
              <label class="control-label">{{ $record->first_name }} {{ $record->last_name }}</label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Review For:</label>
            <div class="col-lg-8">
              <label class="control-label">{{ $record->business_name}}</label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Review Content:</label>
            <div class="col-lg-8">
              <p>{{ $record->comment}}</p>
             
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Rating:</label>
            <div class="col-lg-8">
			<?php
			$star=$record->star_marks;
			for($i=0;$i<$star; $i++)
			{
			?>
              <span class="fa fa-star checked"></span>
			<?php
			}
			?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Review On:</label>
            <div class="col-lg-8">
              <label class="control-label">{{ $record->created_at->format('Y-m-d')}}</label>
            </div>
          </div>         
        </form>      
      </div>
      <div class="col-md-3 col-md-offset-9"> 
	  <?php
		if($record->status == 0)
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
                    url: 'http://demoyourprojects.website/projects/directoryapp/dev/da-admin/review/reviewRejected/'+rejected_id,
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
                    url: 'http://demoyourprojects.website/projects/directoryapp/dev/da-admin/review/reviewApproved/'+approved_id,
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