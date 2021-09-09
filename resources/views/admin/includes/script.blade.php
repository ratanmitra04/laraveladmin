<!-- jQuery UI 1.11.4 -->
<script src="{{asset('admin/js/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
 // $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- Bootstrap 3.3.7 -->
<script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('admin/js/moment.min.js')}}"></script>
<script src="{{asset('admin/js/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{asset('admin/js/bootstrap-datepicker.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('admin/js/adminlte.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{-- <script src="{{asset('admin/js/dashboard.js')}}"></script> --}}
<!-- AdminLTE for demo purposes -->
<!-- <script src="{{asset('admin/js/demo.js')}}"></script> -->
<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/additional-methods.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/jquery.validate.file.js') }}"></script>
<!-- bootbox for confirm message -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<!-- custom js file for all common use -->
<script src="{{ asset('admin/js/custom.js') }}"></script>

<script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('plugins/ckeditor/plugins/ckfinder/ckfinder.js')}}"></script>
<script src="{{asset('admin/js/jquery-ui.js')}}"></script>
<script src="{{asset('admin/js/script_admin.js')}}"></script>
<script src="{{ asset('admin/js/select2.full.min.js') }}"></script>
@if (!empty($js_array))
	@foreach($js_array as $ja)
    	<script src="{{asset('admin/js/')}}/{{$ja}}"></script>
	@endforeach
@endif
