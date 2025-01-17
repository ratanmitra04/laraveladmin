<?php
//echo "===>".Auth::guard('admin')->user()->id;exit;
$id=Session::get('key');
$utype=Session::get('utype');
$ulogo=Session::get('ulogo');
if(isset(Auth::guard('admin')->user()->id))
{
?>
<aside class="main-sidebar sidebarbg">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
			<?php
			if(Auth::guard('admin')->user()->id==1)
			{
			?>
			 <img src="{{asset('uploads/adminuser_logos')}}/{{ Auth::guard('admin')->user()->user_logo }}" class="avatar img-circle prelative" alt="avatar">
			<?php
			}
			else
			{
				
				/*if(Auth::guard('admin')->user()->user_type=='SA')*/
				if($utype=='SA')
				{ 
					if(!empty($ulogo))
					{
				?>
						<img src="{{asset('uploads/adminuser_logos')}}/{{ $ulogo }}" class="avatar img-circle prelative" alt="avatar">
				<?php } else {?>	
						<img src="{{asset('admin/images/no_img.jpg')}}" class="user-image" alt="avatar">
				<?php } ?>
						
			<?php } 
				
			?>
			 <!--<img src="{{asset('uploads/adminuser_logos')}}/{{ Auth::guard('admin')->user()->user_logo }}" class="avatar img-circle prelative" alt="avatar">-->
			<?php
			}
			?>
			</div>
			<div class="pull-left info">
				<!-- <p>{{ Auth::guard('admin')->user()->first_name }} {{ Auth::guard('admin')->user()->last_name }}</p> -->
				<p><b>Custom Black Index</b></p>
			</div>
		</div>

		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu" data-widget="tree">
			<!-- <li class="header">MAIN NAVIGATION</li> -->
			<li class="@if(in_array(Route::current()->getName(), array('dashboard'))) active menu-open @endif">
				<a href="{{ route('dashboard') }}">
					<i class="fa fa-dashboard"></i> <span>Dashboard</span>
				</a>
			</li>
			@if(\Helpers::checkPermission($id,'moderators'))
			<li class="@if(in_array(Route::current()->getName(), array('moderators.list','moderators.add','moderators.edit','moderators.view','moderators.activity'))) active menu-open @endif">
	          <a href="{{ route('moderators.list') }}">
	            <i class="fa fa-user-plus"></i> <span>Moderator Management</span>
	          </a>
	        </li>
			@endif
			@if(\Helpers::checkPermission($id,'users'))
			<li class="@if(in_array(Route::current()->getName(), array('users.list','users.add','users.edit','users.view'))) active menu-open @endif">
	          <a href="{{ route('users.list') }}">
	            <i class="fa fa-users"></i> <span>User Management</span>
	          </a>
	        </li>
			@endif
			@if(\Helpers::checkPermission($id,'business'))
	        <li class="@if(in_array(Route::current()->getName(), array('business.list','business.add','business.edit','business.edit'))) active menu-open @endif">
	          <a href="{{ route('business.list') }}">
	            <i class="fa fa-briefcase"></i> <span>Business Management</span>
	          </a>
	        </li>
			@endif
			@if(\Helpers::checkPermission($id,'category'))
	        <li class="@if(in_array(Route::current()->getName(), array('category.list','category.add','category.edit','category.view'))) active menu-open @endif">
	          <a href="{{ route('category.list') }}">
	            <i class="fa fa-sitemap"></i> <span>Category Management</span>
	          </a>
	        </li>
			@endif
			@if(\Helpers::checkPermission($id,'subscription'))
			<li class="@if(in_array(Route::current()->getName(), array('subscription.list','subscription.add','subscription.edit','subscription.view'))) active menu-open @endif">
	          <a href="{{ route('subscription.list') }}">
	            <i class="fa fa-clipboard"></i> <span>Subscription Management</span>
	          </a>
	        </li>
			@endif
			@if(\Helpers::checkPermission($id,'advertise'))
			<li class="@if(in_array(Route::current()->getName(), array('advertise.list','advertise.add','advertise.edit','advertise.view'))) active menu-open @endif">
	          <a href="{{ route('advertise.list') }}">
	            <i class="fa fa-clipboard"></i> <span>Advertisement Management</span>
	          </a>
	        </li>
			@endif
			@if(\Helpers::checkPermission($id,'transaction'))
			<li class="@if(in_array(Route::current()->getName(), array('transaction.list','transaction.add','transaction.edit','transaction.view'))) active menu-open @endif">
	          <a href="{{ route('transaction.list') }}">
	            <i class="fa fa-credit-card-alt"></i> <span>Transaction Management</span>
	          </a>
	        </li>
			@endif
			@if(\Helpers::checkPermission($id,'review'))
			<li class="@if(in_array(Route::current()->getName(), array('review.list','review.add','review.edit','review.view'))) active menu-open @endif">
	          <a href="{{ route('review.list') }}">
	            <i class="fa fa-commenting-o"></i> <span>Review Management</span>
	          </a>
	        </li>
			@endif
			@if(\Helpers::checkPermission($id,'notification'))
			<li class="@if(in_array(Route::current()->getName(), array('notification.list','notification.add','notification.edit','notification.view'))) active menu-open @endif">
	          <a href="{{ route('notification.list') }}">
	            <i class="fa fa-bell"></i> <span>Notification Management</span>
	          </a>
	        </li>
			@endif
			@if(\Helpers::checkPermission($id,'cms'))
	        <li class="@if(in_array(Route::current()->getName(), array('cms.list','cms.edit','cmscontents.list','cmscontents.edit'))) active menu-open @endif">
	          <a href="{{ route('cms.list') }}">
	            <i class="fa fa-book"></i> <span>Static Content Management</span>
	          </a>
	        </li>	
			@endif
			@if(\Helpers::checkPermission($id,'city'))
			<li class="@if(in_array(Route::current()->getName(), array('cities.list','cities.add','cities.edit'))) active menu-open @endif">
	          <a href="{{ route('cities.list') }}">
	            <i class="fa fa-home"></i> <span>City Management</span>
	          </a>
	        </li>
			@endif
			@if(\Helpers::checkPermission($id,'appsettings'))
			<li class="@if(in_array(Route::current()->getName(), array('appsettings.edit'))) active menu-open @endif">
				<a href="{{ route('appsettings.edit') }}">
					<i class="fa fa-cog"></i> <span>App Settings</span>
				</a>
			</li>
			@endif
			@if(\Helpers::checkPermission($id,'settings'))
			<li class="@if(in_array(Route::current()->getName(), array('settings.edit'))) active menu-open @endif">
				<a href="{{ route('settings.edit') }}">
					<i class="fa fa-cog"></i> <span>Settings</span>
				</a>
			</li>
			@endif						

		</ul>
	</section>
	<!-- /.sidebar -->
</aside>
<?php } ?>