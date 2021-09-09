<!DOCTYPE html>
<html>
	@include('admin.includes.head')
	<link rel="icon" href="{{ asset('admin.images/favicon.png') }}">
	<link rel="stylesheet" href="{{asset('admin/css/blue.css')}}">
	<body class="hold-transition login-page">
        @yield('content')
        <script src="{{asset('admin/js/jquery.min.js')}}"></script>
        <script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('admin/js/icheck.min.js')}}"></script>
        <script src="{{asset('admin/js/jquery.validate.min.js')}}"></script>
    </body>
</html>