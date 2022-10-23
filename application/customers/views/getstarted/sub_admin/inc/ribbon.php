	<!-- RIBBON -->
	<div id="ribbon">

		<span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true"><i class="fa fa-refresh"></i></span> </span>

		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<?php
				foreach ($breadcrumbs as $display => $url) {
					$breadcrumb = $url != "" ? '<a class="menu-links" href="'.$url.'dashboard/to_dashboard">'.$display.'</a>' : $display;
					echo '<li>'.$breadcrumb.'</li>';
				}
				echo '<li>'.$page_title.'</li>';
			?>
		</ol>
		<!-- end breadcrumb -->

		<!-- You can also add more buttons to the
		ribbon for further usability

		Example below:

		<span class="ribbon-button-alignment pull-right">
		<span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa-grid"></i> Change Grid</span>
		<span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
		<span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
		</span> -->

				
					<div class="pull-right btn-group dropdown dropdown-large">
						<!--<ul class="nav navbar-nav">
							<li class="">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-info">Help<b class="caret"></b></span></a>-->
									<button class="btn dropdown-toggle btn-xs btn-default btn-circle" data-toggle="dropdown" style="margin-top:5px;">
										 <i class="fa fa-question"></i>
									</button>
								  <ul class="dropdown-menu dropdown-menu-large row">
									<li class="col-sm-6">
										<ul>
										    <li class="dropdown-header">
												 Dashboard
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_dashboard"?>">Dashboard</a>
											</li>
											<li class="divider"></li>
											<li class="dropdown-header">
												 Events
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_create_event_forms"?>">Create Event Forms</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_manage_event_forms"?>">Manage Event forms</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_assign_events"?>">Assign Events</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_user_events"?>">User Assigned Events</a>
											</li>
											<li class="divider"></li>
											<li class="dropdown-header">
												Feedbacks
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_create_feed_forms"?>">Create Feedback Forms</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_manage_feed_forms"?>">Manage Feedbacks</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_assigned_feed"?>">Assigned Feedbacks</a>
											</li>
											<li class="divider"></li>
											<li class="dropdown-header">
												Notifications
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_msg"?>">Create Messages</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_msg_history"?>"> History</a>
											</li>
										</ul>
									</li>
									<li class="col-sm-6">
										<ul>
										    <li class="dropdown-header">
												 SMS
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_sms"?>">SMS Dashboard</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_sms_history"?>">History</a>
											</li>
											<!--<li>
												<a class="menu-links" href="<?php echo URL."help/third_party_sms"?>">Third Party SMS</a>
											</li>-->
											<li class="divider"></li>
											<li class="dropdown-header">
												Profile
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sub_admin_profile"?>">Profile Details</span></a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/edit_sub_admin_profile"?>">Edit Profile</a>
											</li>
											<li class="divider"></li>
											<li class="dropdown-header">
												Tools
											</li>
											            <li>
															<a class="menu-links" href="<?php echo URL."help/sub_admin_change_pwd"?>">Change Password</a>
														</li>
										</ul>
									</li>
								</ul>
		
							<!--</li>
						</ul>-->
		
		
					</div>
	</div>
	
	<!-- END RIBBON -->