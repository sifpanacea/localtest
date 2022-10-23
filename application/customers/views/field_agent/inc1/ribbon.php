	<!-- RIBBON -->
	<div id="ribbon">

		<span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true"><i class="fa fa-refresh"></i></span> </span>

		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<?php
				foreach ($breadcrumbs as $display => $url) {
					$breadcrumb = $url != "" ? '<a class="menu-links" href="'.$url.'sub_admin/to_dashboard">'.$display.'</a>' : $display;
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
									<li class="col-sm-3">
										<ul>
											<li class="dropdown-header">
												 Group Management
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/create_group"?>">Creating Groups</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/edit_group"?>">Editing Groups</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/groups"?>">List all groups</a>
											</li>
											<li class="divider"></li>
											<li class="dropdown-header">
												User Management
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/create_group"?>">Creating Users</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/edit_user"?>">Editing Users</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/users"?>">List all users</a>
											</li>
											<li>
									           <a class="menu-links" href="<?php echo URL."help/user_status_change"?>">Activating/Deactivating Users</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/delete_user"?>">Deleting users</a>
											</li>
											<li class="divider"></li>
											<li class="dropdown-header">
												Imports
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/sql_import"?>">Importing SQL Database</a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/nosql_import"?>"> Importing NoSQL Database </a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/document_import"?>"> Importing Documents</a>
											</li>
										</ul>
									</li>
									<li class="col-sm-3">
										<ul>
											<li class="dropdown-header">
												Profile
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/edit_profile"?>"> Edit Profile</span></a>
											</li>
											<li>
												<a class="menu-links" href="<?php echo URL."help/plan_upgrade"?>">Upgrade</a>
											</li>
											<li class="divider"></li>
											<li class="dropdown-header">
												Application Design
											</li>
											            <li>
															<a class="menu-links" href="<?php echo URL."help/app_prop"?>">Setting properties for app</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/app_design"?>">Adding elements to app</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/workflow"?>"> Defining workflow</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/notifications"?>">Sending notifications</a>
														</li>
														<li class="divider"></li>
											
											            <li>
															<a class="menu-links" href="<?php echo URL."help/documents"?>"> Documents</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/change_pwd"?>">Changing Password</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/predefined_lists"?>"> Creating predefined lists</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/analytics"?>">Analytics </a>
														</li>
										</ul>
									</li>
									<li class="col-sm-3">
										<ul>
											<li class="dropdown-header">
												All Apps
											</li>
											<li>
															<a class="menu-links" href="<?php echo URL."help/edit_allapps"?>"> Editing Existing Application</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/use_allapps"?>"> Using Existing Application</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/delete_allapps"?>"> Deleting Application</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/share_app"?>"> Sharing Application as Community App</a>
														</li>
											<li class="divider"></li>
											<li class="dropdown-header">
												Shared Apps
											</li>
											<li>
															<a class="menu-links" href="<?php echo URL."help/edit_sharedapps"?>"> Editing Existing Application</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/use_sharedapps"?>">Using Existing Application</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/delete_sharedapps"?>"> Deleting Application</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/unshare_app"?>">Unsharing Application</a>
														</li>
														
										</ul>
									</li>
									<li class="col-sm-3">
										<ul>
										                 
														<li class="dropdown-header">
												My Apps
											</li>
											<li>
															<a class="menu-links" href="<?php echo URL."help/edit_myapps"?>"> Editing Existing Application</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/use_myapps"?>"> Using Existing Application</a>
														</li>
														<li>
															<a class="menu-links" href="<?php echo URL."help/delete_myapps"?>"> Deleting Application</a>
														</li>	
														<li class="divider"></li>
                                         <li class="dropdown-header">
												Community Apps
											</li>
											<li>
															<a class="menu-links" href="<?php echo URL."help/use_community_app"?>"> Using App</a>
														</li>
														<li class="divider"></li>
                                           <li class="dropdown-header">
												Draft Apps
											</li>
											<li>
															<a class="menu-links" href="<?php echo URL."help/draft_app"?>"> Continuing from draft</a>
														</li>														
														</ul>
									</li>
								</ul>
		
							<!--</li>
						</ul>-->
		
		
					</div>
	</div>
	
	<!-- END RIBBON -->