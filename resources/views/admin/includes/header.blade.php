<?php
$id=Session::get('key');
$utype=Session::get('utype');
$ulogo=Session::get('ulogo');
?>
<header class="main-header">
    <!-- Logo -->
    <a href="{{route('dashboard')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>{{ GlobalVars::ADMIN_LOGO_MINI }}</b></span>
      <!-- logo for regular state and mobile devices -->
      <!-- <span class="logo-lg"><b>{{ GlobalVars::ADMIN_NAME }}</b></span> -->
      <span class="logo-lg"><b>Custom Black Index</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              
			<?php
			if(isset(Auth::guard('admin')->user()->id))
			{
				if(Auth::guard('admin')->user()->user_type=='A'){ ?>
					<img src="{{asset('uploads/adminuser_logos')}}/{{ Auth::guard('admin')->user()->user_logo }}" class="user-image" alt="avatar">
			<?php } 
			if($utype=='SA')
			{ 
				if(!empty($ulogo))
				{
			?>
					<img src="{{asset('uploads/adminuser_logos')}}/{{ $ulogo }}" class="user-image" alt="avatar">
			<?php } else {?>	
					<img src="{{asset('admin/images/no_img.jpg')}}" class="user-image" alt="avatar">
			<?php } ?>
					
			<?php } ?>
				<span class="hidden-xs">{{ Auth::guard('admin')->user()->first_name }} {{ Auth::guard('admin')->user()->last_name }}</span>
			<?php } ?>
             
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                
				<?php
			if(isset(Auth::guard('admin')->user()->id))
			{
				if(Auth::guard('admin')->user()->user_type=='A'){ ?>
					<img src="{{asset('uploads/adminuser_logos')}}/{{ Auth::guard('admin')->user()->user_logo }}" class="user-image" alt="avatar">
			<?php } if($utype=='SA')
			{ 
					if(!empty($ulogo))
				{
			?>
					<img src="{{asset('uploads/adminuser_logos')}}/{{ $ulogo }}" class="user-image" alt="avatar">
			<?php } else {?>	
					<img src="{{asset('admin/images/no_img.jpg')}}" class="user-image" alt="avatar">
			<?php } ?>
					
			<?php } ?>
				 <p>
                  {{ Auth::guard('admin')->user()->first_name }} {{ Auth::guard('admin')->user()->last_name }}
                  <small>Member since {{ \DateTime::createFromFormat('Y-m-d H:i:s', Auth::guard('admin')->user()->created_at)->format('dS M, Y') }}</small>
                </p>
		<?php } ?>
		
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ URL::Route('admin_update_profile') }}" class="btn btn-default btn-flat">Update Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ URL::Route('admin_logout') }}" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->

        </ul>
      </div>
    </nav>
  </header>