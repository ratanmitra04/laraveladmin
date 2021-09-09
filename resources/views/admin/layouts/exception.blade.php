<!DOCTYPE html>
<html>
<script>
      var csrf_token = '{{ csrf_token() }}';

      var BASEURL = "{{ GlobalVars::ADMIN_BASEURL }}";
</script>
  @include('admin.includes.head')
  <link rel="icon" href="{{ asset('admin/images/favicon.png') }}">
  <body class="hold-transition skin-blue sidebar-mini">
    <style type="text/css">
    #loaderoverlay{position: fixed; left: 0; right: 0; top: 0; bottom: 0; background: rgba(255,255,255,0.7); width: 100%; height: 100%;  z-index: 10000;}
    #loaderoverlay div{position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%); -moz-transform: translate(-50%, -50%); -ms-transform: translate(-50%, -50%);}        
    </style>
    <div id="loaderoverlay" style="display:none"><div><img src="{{ asset('admin/images/loaderoverlay.gif') }}"/></div></div>    
    <div class="wrapper">

      @include('admin.includes.header')
      <!-- Left side column. contains the logo and sidebar -->
  
      @include('admin.includes.sidebar')
      <!-- Content Wrapper. Contains page content -->
      @yield('content')
      <!-- /.content-wrapper -->
      @include('admin.includes.footer')

      <div class="control-sidebar-bg"></div>
    </div>
    @include('admin.includes.script')
  </body>

@yield('script')

<form id="frmDelete" name="frmDelete" method="POST" action="">
    {{ csrf_field() }}
</form>  
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    })
</script>

@yield('pageScript')
</html>
