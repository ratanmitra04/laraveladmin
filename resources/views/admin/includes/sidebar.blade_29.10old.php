<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="{{asset('uploads/adminuser_logos')}}/{{ Auth::guard('admin')->user()->user_logo }}" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p>{{ Auth::guard('admin')->user()->first_name }} {{ Auth::guard('admin')->user()->last_name }}</p>
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
			

			<li class="@if(in_array(Route::current()->getName(), array('users.list','users.add','users.edit','users.view'))) active menu-open @endif">
	          <a href="{{ route('users.list') }}">
	            <i class="fa fa-user"></i> <span>User Management</span>
	          </a>
	        </li>
	        <li class="@if(in_array(Route::current()->getName(), array('business.list','business.add','business.edit','business.edit'))) active menu-open @endif">
	          <a href="{{ route('business.list') }}">
	            <i class="fa fa-user"></i> <span>Busines Management</span>
	          </a>
	        </li>

	        <li class="@if(in_array(Route::current()->getName(), array('category.list','category.add','category.edit','category.view'))) active menu-open @endif">
	          <a href="{{ route('category.list') }}">
	            <i class="fa fa-user"></i> <span>Category Management</span>
	          </a>
	        </li>
			
			<li class="@if(in_array(Route::current()->getName(), array('subscription.list','subscription.add','subscription.edit','subscription.view'))) active menu-open @endif">
	          <a href="{{ route('subscription.list') }}">
	            <i class="fa fa-envelope-open-o"></i> <span>Subscription Management</span>
	          </a>
	        </li>
			
			<li class="@if(in_array(Route::current()->getName(), array('advertise.list','advertise.add','advertise.edit','advertise.view'))) active menu-open @endif">
	          <a href="{{ route('advertise.list') }}">
	            <i class="fa fa-address-card-o"></i> <span>Advertisement Management</span>
	          </a>
	        </li>
			
			<li class="@if(in_array(Route::current()->getName(), array('transaction.list','transaction.add','transaction.edit','transaction.view'))) active menu-open @endif">
	          <a href="{{ route('transaction.list') }}">
	            <i class="fa fa-money"></i> <span>Transaction Management</span>
	          </a>
	        </li>
			
			<li class="@if(in_array(Route::current()->getName(), array('review.list','review.add','review.edit','review.view'))) active menu-open @endif">
	          <a href="{{ route('review.list') }}">
	            <i class="fa fa-commenting-o"></i> <span>Review Management</span>
	          </a>
	        </li>
			
			<li class="@if(in_array(Route::current()->getName(), array('notification.list','notification.add','notification.edit','notification.view'))) active menu-open @endif">
	          <a href="{{ route('notification.list') }}">
	            <i class="fa fa-bell"></i> <span>Notification Management</span>
	          </a>
	        </li>

	        <li class="@if(in_array(Route::current()->getName(), array('cms.list','cms.edit','cmscontents.list','cmscontents.edit'))) active menu-open @endif">
	          <a href="{{ route('cms.list') }}">
	            <i class="fa fa-tasks"></i> <span>Static Content Management</span>
	          </a>
	        </li>	
			
			
			<li class="@if(in_array(Route::current()->getName(), array('settings.edit'))) active menu-open @endif">
				<a href="{{ route('settings.edit') }}">
					<i class="fa fa-cog"></i> <span>Settings</span>
				</a>
			</li>
									

		</ul>
	</section>
	<!-- /.sidebar -->
</aside>