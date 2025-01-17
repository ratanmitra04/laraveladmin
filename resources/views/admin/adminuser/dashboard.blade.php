@extends('admin.layouts.default')
@section('title', 'Dashboard')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Dashboard
            <small>Control Panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>
<?php
//echo "<pre>"; print_r($customerCount); echo "</pre>"; exit;
?>
        <!-- Main content -->
        <section class="content">
          <!-- Main row -->
          <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ isset($customers) ? $customers : '' }}</h3>

              <p>Total Customers</p>
            </div>
            <div class="icon">
              <i class="ion ion-briefcase"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{$business_count}}<!-- <sup style="font-size: 20px">%</sup> --></h3>

              <p>Total Businesses</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-contacts"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
  

        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{$clientele_count}}</h3>

              <p>App Installation</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-people"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>


		<!--- 02-10-2020 by Debnidhi --->
		 <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{$clientele_count}}</h3>

              <p>Top Rated Businesses</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-people"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
		
		 <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
              <h3>{{$clientele_count}}</h3>

              <p>Ongoing Advertisements</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-people"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
		
		 <div class="col-lg-4 col-xs-6">

                    <!-- small box -->
                    <div class="small-box bg-gray">
                      <div class="inner">
                        <h3>{{$featuredbusiness_count}}</h3>
                        <p>Featured Business</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-android-people"></i>
                      </div>
                      <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
                    </div>
                  </div>


            <div class="col-sm-12">
                <ul class="nav nav-pills nav-jt">
                      <li class="active"><a data-toggle="tab" href="#tab1" id="ctab1">Customers</a></li>
                      <li><a data-toggle="tab" id= "bs-tab2"  href="#tab2" >Business</a></li>
                  </ul>


                 
                  <div class="tab-content mtup">
            <div id="tab1" class="tab-pane fade active in">
            	 <div class="col-sm-12  ">                   	
                  <div class="col-sm-3 anc abc">                 
                        </div>
                        <div class="col-sm-9 padding0">

                        <label class="col-sm-2 control-label anc abc"></label>                                
                            <div class="col-sm-6 anc abc" id=""> 
                        </div>
                         <select name="city" id="city_id" class="widtharea">
							<option value="">--Select City--</option>
							@foreach ($city as $key => $value)
								<option value="{{ $value['id']}}">{{ $value['city_name'] }}</option>
							@endforeach
						</select>
                            <input type="button" id="filterGrapg" class="btn btn-primary go" value="Go">
                          </div>                            
                    </div>


                <div class="col-sm-12" id="customer_graph" style=" width: 100%;margin-top: 20px;"></div>
            </div>
            <div id="tab2" class="tab-pane">
			 <div class="col-sm-12  ">                   	
                  <div class="col-sm-3 anc ">
                  <!--<label class="control-label">Weekly</label>
                        <label class="switch">
                          <input type="checkbox" checked>
                          <span class="slider round"></span>
                        </label><label class="control-label">Monthly</label>-->
                        </div>
                        <div class="col-sm-9 padding0">

                        <label class="col-sm-2 control-label anc ">Filter By Date:</label>                                
                            <div class="col-sm-6 anc " id="dashdate"> <input id="fromdate" class="form-control wd30  online" type="date" value="12.02.2020"><span class="onlineto">to</span><input id="todate" class="form-control wd30  online" type="date" value="12.02.2020"> 
                        </div>
                         <select name="cityBusiness" id="city_id_business" class="widtharea">
							<option value="">--Select City--</option>
							@foreach ($city as $key => $value)
								<option value="{{ $value['id']}}">{{ $value['city_name'] }}</option>
							@endforeach
						</select>
                           <input type="button" id="filterGrapgbusiness" class="btn btn-primary go" value="Go">
                          </div>                            
                    </div>


                <div class="col-sm-12" id="business_graph" style=" width: 100%;margin-top:4px;"></div>
            </div>
        </div>
           </div>
      </div>
        </section>    
    </div>
<style>
 .def{
 	opacity: 1;
 }
.abc{
 	opacity: 0;
 }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $('#filterGrapgbusiness').on('click', function() {
            var city_id = $('#city_id_business').val();
            var fromdate = $('#fromdate').val();
            var todate = $('#todate').val();
			//alert(city_id);
            
                $.ajax({
                    url: 'http://demoyourprojects.website/projects/directoryapp/dev/da-admin/getCityWiseBusiness?city_id='+city_id+'&fromdate='+fromdate+'&todate='+todate,
                    type: "GET",
					dataType:"JSON",
                    success:function(response) {
						if(response.rec.length==0){
							$("#business_graph").html('No record found.');
						}else{
							var graphRec = [
							["Element", "Customer", { role: "style" } ]
						  ];
						  for(var i=0;i<response.rec.length;i++){
							  graphRec.push(response.rec[i]);
						  }
						google.charts.load("current", {packages:['corechart']});
						google.charts.setOnLoadCallback(drawChart);
						function drawChart() {
						  var data = google.visualization.arrayToDataTable(graphRec);

						  var view = new google.visualization.DataView(data);
						  view.setColumns([0, 1,
										   { calc: "stringify",
											 sourceColumn: 1,
											 type: "string",
											 role: "annotation" },
										   2]);

						  var options = {
							title: "Top Clicked Business",
							width: 1100,
							height: 400,
							bar: {groupWidth: "45%"},
							legend: { position: "none" },
						  };
						  var chart = new google.visualization.ColumnChart(document.getElementById("business_graph"));
						  chart.draw(view, options);
					  }
						
						}
						
                    }
                });
           
        });
    });
</script>	


<script type="text/javascript">
    $(document).ready(function() {
        $('#filterGrapg').on('click', function() {
            var city_id = $('#city_id').val();
			//alert(city_id);
            if(city_id) {
                $.ajax({
                    url: 'http://demoyourprojects.website/projects/directoryapp/dev/da-admin/getCityWiseCustomers/'+city_id,
                    type: "GET",
					dataType:"JSON",
                    success:function(response) {
						if(response.totalCustomer==0){
							$("#customer_graph").html('No record found.');
						}else{
						  var graphRec = [
							["Element", "Customer", { role: "style" } ]
						  ];
						  for(var i=0;i<response.rec.length;i++){
							  graphRec.push(response.rec[i]);
						  }
						  
						google.charts.load("current", {packages:['corechart']});
						google.charts.setOnLoadCallback(drawChart);
						function drawChart() {
						  var data = google.visualization.arrayToDataTable(graphRec);

						  var view = new google.visualization.DataView(data);
						  view.setColumns([0, 1,
										   { calc: "stringify",
											 sourceColumn: 1,
											 type: "string",
											 role: "annotation" },
										   2]);

						  var options = {
							title: "Customer count for last 7 days",
							width: 1100,
							height: 400,
							bar: {groupWidth: "85%"},
							legend: { position: "none" },
						  };
						  var chart = new google.visualization.ColumnChart(document.getElementById("customer_graph"));
						  chart.draw(view, options);
					  }
						}
                    }
                });
            }
			else
			{
				console.log('else');
				location.reload();
			}
        });
    });
</script>	
<?php
$colorCode=['#00ffff','#8000ff','#ffff00','#e5e4e2','#ff8000','#ff00ff','#b87333','silver','#bf00ff','#40ff00'];
$jsonDAta='';
$noOfCustomers='';
$currentDate=DATE('Y-m-d');
for($i=1;$i<=7; $i++)
{
	$prevDate=date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
	if($i>1)
	{
		$jsonDAta=$jsonDAta.',';
	}
	foreach($customerCount as $value)
	{
		$noOfCustomers=0;
		if($value->pp == $currentDate)
		{
			$noOfCustomers=$value->total;
			break;
		}
	}
	$jsonDAta.='["'.date('d/m/Y',strtotime($currentDate)).'",'.$noOfCustomers.',"'.$colorCode[$i-1].'"]';
	$currentDate=$prevDate;
}
?>
<!---- Customer Tab Graph ---->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Customer", { role: "style" } ],
		<?php echo $jsonDAta; ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Customer count for last 7 days",
        width: 1100,
        height: 400,
        bar: {groupWidth: "85%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("customer_graph"));
      chart.draw(view, options);
  }
</script>
<!---- Customer Tab Graph ----> 
<!---- Business Tab Graph ---->
<?php
$colorCode=['#b87333','#8000ff','#ffff00','#e5e4e2','#ff8000','#ff00ff','#b87333','silver','#bf00ff','#40ff00','#b87333','#ff4000','#ffbf00','#00bfff','#ff00bf','#4000ff','#e5e4e2','#ff00ff','#8000ff','#e5e4e2'];
$jsonDAtaBusiness=[];
$i=0;
foreach($topClickedBusinesses as $value)
{
	
	$businessName=$value['business_name'];
	if(empty($value['top_clicked']))
	{
		$noOfClick=0;
	}
	else
	{
		$noOfClick=$value['top_clicked'];
	}
	$jsonDAtaBusiness[]='["'.$businessName.'",'.$noOfClick.',"'.$colorCode[$i].'"]';
	$i++;
}
$jsonDAtaBusinessStr = implode(',',$jsonDAtaBusiness);
?>  
<script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Clicked", { role: "style" } ],
        <?php echo $jsonDAtaBusinessStr; ?>
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Top Clicked Business",
        width: 1100,
        height: 400,
        bar: {groupWidth: "45%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("business_graph"));
      chart.draw(view, options);
  }
</script>
<!---- Business Tab Graph ---->  
@endsection