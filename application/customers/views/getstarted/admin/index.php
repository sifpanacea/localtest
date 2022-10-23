<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Get Started";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Getstarted"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
     				<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->
                                 <!-- NEW WIDGET START -->
				<article class="col-sm-12 col-md-12 col-lg-6">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-editbutton="false">
						<!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
		
						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"
		
						-->
						<header>
							<span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
							<h2>Table of Contents </h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body">
		
								<div class="tree smart-form">
									<ul>
										<li>
											<span><i class="fa fa-lg fa-folder-open"></i> PaaS</span>
											<ul>
											<li>
													<a href="<?php echo URL."help/dashboard"?>"><span><i class="icon-leaf"></i> Dashboard </span></a>
												</li>
											<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Group Management</span>
													<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/create_group"?>"><span><i class="icon-leaf"></i> Creating Groups</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/edit_group"?>"><span><i class="icon-leaf"></i> Editing Group </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/groups"?>"><span><i class="icon-leaf"></i> Listing all Groups</span></a>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> User Management</span>
													<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/create_user"?>"><span><i class="icon-leaf"></i> Creating User</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/edit_user"?>"><span><i class="icon-leaf"></i> Editing User </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/users"?>"><span><i class="icon-leaf"></i> Listing all Users</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/user_status_change"?>"><span><i class="icon-leaf"></i> Activating/Deactivating User</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/delete_user"?>"><span><i class="icon-leaf"></i> Deleting User</span></a>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Sub Admin Management</span>
													<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/create_sub_admin"?>"><span><i class="icon-leaf"></i> Creating sub admin</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/edit_sub_admin"?>"><span><i class="icon-leaf"></i> Editing sub admin </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admins"?>"><span><i class="icon-leaf"></i> Listing all Sub Admins</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_status_change"?>"><span><i class="icon-leaf"></i> Activating/Deactivating Sub Admin</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/delete_sub_admin"?>"><span><i class="icon-leaf"></i> Deleting Sub Admin</span></a>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Third Party </span>
													<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/api_users"?>"><span><i class="icon-leaf"></i> API Users</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/third_party_status_change"?>"><span><i class="icon-leaf"></i> Activating/Deactivating Third Party</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/new_api_users"?>"><span><i class="icon-leaf"></i> New API Users</span></a>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Profile</span>
													<ul>
													    <li style="display:none">
															<a href="<?php echo URL."help/admin_profile"?>"><span><i class="icon-leaf"></i> Profile Details</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/edit_admin_profile"?>"><span><i class="icon-leaf"></i> Edit Profile</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/plan_upgrade"?>"><span><i class="icon-leaf"></i> Upgrade </span></a>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Application Design</span>
													<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/app_prop"?>"><span><i class="icon-leaf"></i> Setting properties for app</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/app_design"?>"><span><i class="icon-leaf"></i> Adding elements to app </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/workflow"?>"><span><i class="icon-leaf"></i> Defining Workflow</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/notifications"?>"><span><i class="icon-leaf"></i> Sending notifications</span></a>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Applications</span>
													<ul>
														<li style="display:none">
															<span><i class="fa fa-lg fa-plus-circle"></i> All Apps</span>
														<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/all_app_properties"?>"><span><i class="icon-leaf"></i> App properties</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/edit_allapps"?>"><span><i class="icon-leaf"></i> Editing Existing Application</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/use_allapps"?>"><span><i class="icon-leaf"></i> Using Existing Application </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/delete_allapps"?>"><span><i class="icon-leaf"></i> Deleting Application</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/share_app"?>"><span><i class="icon-leaf"></i> Sharing Application as Community App</span></a>
														</li>
													</ul></li>
													
													   <li style="display:none">
															<span><i class="fa fa-lg fa-plus-circle"></i> Shared Apps</span>
														<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/shared_app_properties"?>"><span><i class="icon-leaf"></i> App properties</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/edit_sharedapps"?>"><span><i class="icon-leaf"></i> Editing Existing Application</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/use_sharedapps"?>"><span><i class="icon-leaf"></i>Using Existing Application </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/delete_sharedapps"?>"><span><i class="icon-leaf"></i> Deleting Application</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/unshare_app"?>"><span><i class="icon-leaf"></i> Unsharing Application</span></a>
														</li>
													</ul></li>
														
														<li style="display:none">
															<span><i class="fa fa-lg fa-plus-circle"></i> MY Apps</span>
														<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/my_app_properties"?>"><span><i class="icon-leaf"></i> App properties</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/edit_myapps"?>"><span><i class="icon-leaf"></i> Editing Existing Application</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/use_myapps"?>"><span><i class="icon-leaf"></i> Using Existing Application</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/delete_myapps"?>"><span><i class="icon-leaf"></i> Deleting Application</span></a>
														</li>
													</ul></li>
													
													    <li style="display:none">
															<span><i class="fa fa-lg fa-plus-circle"></i> Community Apps</span>
														<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/community_app_properties"?>"><span><i class="icon-leaf"></i> App properties</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/use_community_app"?>"><span><i class="icon-leaf"></i> Using App</span></a>
														</li>
													</ul>
													</li>
													</ul>
												</li>
												<li>
															<span><i class="fa fa-lg fa-plus-circle"></i> Drafts</span>
														<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/draft_app"?>"><span><i class="icon-leaf"></i> Continuing from draft</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/delete_draft"?>"><span><i class="icon-leaf"></i> Delete draft</span></a>
														</li>
													</ul></li>
												<li>
													<a href="<?php echo URL."help/calendar"?>"><span><i class="icon-leaf"></i> Calendar </span></a>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Events </span>
													<ul>
													<li style="display:none">
													<a href="<?php echo URL."help/event_requests"?>"><span><i class="icon-leaf"></i> Event Requests </span></a>
												     </li>	
													 <li style="display:none">
													<a href="<?php echo URL."help/manage_events"?>"><span><i class="icon-leaf"></i> Managing event requests</span></a>
												</li>
												</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Feedback </span>
													<ul>
													<li style="display:none">
													<a href="<?php echo URL."help/feedback_requests"?>"><span><i class="icon-leaf"></i> Feedback Requests</span></a>
												     </li>	
													 <li style="display:none">
													<a href="<?php echo URL."help/manage_feedbacks"?>"><span><i class="icon-leaf"></i> Managing feedback requests </span></a>
												</li>
												</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Tools </span>
													<ul>
													<li style="display:none">
													<a href="<?php echo URL."help/change_pwd"?>"><span><i class="icon-leaf"></i> Changing Password </span></a>
												    </li>
													<li style="display:none">
													<a href="<?php echo URL."help/predefined_lists"?>"><span><i class="icon-leaf"></i> Creating predefined lists </span></a>
												    </li>
													<li style="display:none">
													<a href="<?php echo URL."help/predefined_templates"?>"><span><i class="icon-leaf"></i> Predefined templates </span></a>
												    </li>
													<li style="display:none">
													<a href="<?php echo URL."help/sql_import"?>"><span><i class="icon-leaf"></i> Importing SQL Database </span></a>
												     </li>	
													 <li style="display:none">
													<a href="<?php echo URL."help/nosql_import"?>"><span><i class="icon-leaf"></i> Importing NoSQL Database </span></a>
												</li>
												<li style="display:none">
													<a href="<?php echo URL."help/document_import"?>"><span><i class="icon-leaf"></i> Importing Documents</span></a>
												</li>
												</ul>
												</li>
												
											</ul>
										</li>
									</ul>
								</div>
		
							</div>
							<!-- end widget content -->
		
        </div><!-- ROW -->
				

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S)--> 
<script type="text/javascript">
	
	$(document).ready(function() {
	
		$('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
		$('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function(e) {
			var children = $(this).parent('li.parent_li').find(' > ul > li');
			if (children.is(':visible')) {
				children.hide('fast');
				$(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
			} else {
				children.show('fast');
				$(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
			}
			e.stopPropagation();
		});			
	
	})

</script>
<!--<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<?php 
	//include footer
	include("inc/footer.php"); 
?>