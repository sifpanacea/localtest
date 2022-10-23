<div class="navbar">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<!--<span class="brand"><span class="hidden-phone"><?php echo lang('common_dash_heading');?></span></span>-->			
					
				
	</div>
  </div>
</div>


<!-- start: Header -->	
<div class="container-fluid">
	<div class="row-fluid">
		<!-- start: Main Menu -->
		<div class="span2 main-menu-span">
			<div class="nav-collapse sidebar-nav">
				<ul class="nav nav-tabs nav-stacked main-menu" id="nav">
					<li>
                    	
                        	<?php echo anchor('dashboard/to_dashboard', lang('common_admin_dash_link'))?>
                        
                    </li>
					<li>
                    	
                        	<?php echo anchor('auth/customers', lang('common_customers_dash_link'))?>
                        
                    </li>
                    
                    <li>
                    	
                        	<?php echo anchor('auth/create_admin', lang('common_create_user_link'))?>
                        
                    </li>
                    <li>
                    	
                        	<?php echo anchor('auth/change_password', lang('common_change_password_link'))?>
                       
                    </li>
                    <li>
                    	
                        	<?php echo anchor('auth/logout', lang('common_logout_link'))?>
                        
                    </li>
                    <!-- <br />
					<li>
                    	<a href="#">
                        	<i class="icon-dash-large icon-th icon-white"></i>
                            <span class="hidden-tablet"> Grid</span>
                        </a>
                    </li>
					<li>
                    	<a href="#">
                       		<i class="icon-dash-large icon-folder-open icon-white"></i>
                            <span class="hidden-tablet"> File Manager</span>
                        </a>
                   	</li>
					<li>
                    	<a href="#">
                        	<i class="icon-dash-large icon-star icon-white"></i>
                            <span class="hidden-tablet"> Icons</span>
                        </a>
                    </li>
					<li>
                    	<a href="#">
                        	<i class="icon-dash-large icon-list-alt icon-white"></i>
                            <span class="hidden-tablet"> Login Page</span>
                        </a>
                    </li> -->
				</ul>
			</div><!--/.well -->
		</div><!--/span-->
		<!-- end: Main Menu -->
		<noscript>
			<div class="alert alert-block span10">
				<h4 class="alert-heading">Warning!</h4>
				<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
			</div>
		</noscript>