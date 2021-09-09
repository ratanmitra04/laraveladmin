<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--<title>{{ GlobalVars::ADMIN_NAME }} Control Panel | @yield('title')</title>-->
  <title>Custom Black Index Control Panel | @yield('title')</title>
  <meta name="_token" content="{!! csrf_token() !!}"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('admin/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('admin/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('admin/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('admin/css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the admin/css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('admin/css/_all-skins.min.css')}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{asset('admin/css/bootstrap-datepicker.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('admin/css/daterangepicker.css')}}">

  <link rel="stylesheet" href="{{asset('admin/css/select2.min.css')}}">
  
  <link rel="stylesheet" href="{{asset('admin/css/style.css')}}">

  <link rel="stylesheet" href="{{asset('admin/css/sreyastyle.css')}}">

  <link rel="stylesheet" href="{{asset('admin/css/bootstrap-select.css')}}">

  <link rel="stylesheet" href="{{asset('admin/css/morris.css')}}">

  <!-- <link rel="stylesheet" href="{{asset('admin/css/jquery-ui.css')}}"> -->
  <link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css'>

  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- bootstrap wysihtml5 - text editor -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
  <script src="{{asset('admin/js/jquery.min.js')}}"></script>
  <!--<script src="{{asset('admin/js/canvas.js')}}"></script>
  <script src="{{asset('admin/js/chart.min.js')}}"></script>
  <script src="{{asset('admin/js/scriptchart.js')}}"></script>
  <script src="{{asset('admin/js/script_ad.js')}}"></script>-->
</head>