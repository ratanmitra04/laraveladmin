<!DOCTYPE html>
<html>
	@include('admin.includes.head')
    <link rel="icon" href="{{ asset('frontend/images/home_logo.png') }}">
	<link rel="stylesheet" href="{{asset('admin/css/blue.css')}}">
	<body class="hold-transition login-page">
        @yield('content')

        <script src="{{asset('admin/js/jquery.min.js')}}"></script>
        <script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('admin/js/icheck.min.js')}}"></script>
        <script src="{{asset('admin/js/jquery.validate.min.js')}}"></script>
        <script>
            $(function () {
                $('input').iCheck({
                      checkboxClass: 'icheckbox_square-blue',
                      radioClass: 'iradio_square-blue',
                      increaseArea: '20%' /* optional */
                });
            });
            $(document).ready(function(){
                /*$("#loginForm").validate({
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            required: true,
                            minlength: 6,
                            maxlength: 15
                        }
                    },
                    messages: {
                        email: {
                            required: "Please provide email id",
                            email: "Please provide valid email id"
                        },
                        password: {
                            required: "Please provide a password",
                            minlength: "Password should be 6 - 15 characters",
                            maxlength: "Password should be 6 - 15 characters"
                        }
                    }
                });*/
            });
        </script>
    </body>
</html>