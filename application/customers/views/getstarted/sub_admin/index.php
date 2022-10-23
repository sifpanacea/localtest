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
													<span><i class="fa fa-lg fa-plus-circle"></i> Dashboard </span>
													<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_dashboard"?>"><span><i class="icon-leaf"></i> Dashboard View</span></a>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Events </span>
													<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_create_event_forms"?>"><span><i class="icon-leaf"></i> Create Event Forms </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_manage_event_forms"?>"><span><i class="icon-leaf"></i> Manage Event forms </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_assign_events"?>"><span><i class="icon-leaf"></i> Assign Events </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_user_events"?>"><span><i class="icon-leaf"></i> User Assigned Events</span></a>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Feedbacks </span>
													<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_create_feed_forms"?>"><span><i class="icon-leaf"></i> Create Feedback Forms </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_manage_feed_forms"?>"><span><i class="icon-leaf"></i> Manage Feedbacks </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_assigned_feed"?>"><span><i class="icon-leaf"></i> Assigned Feedbacks </span></a>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Notifications </span>
													<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_msg"?>"><span><i class="icon-leaf"></i> Create Notification Messages </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_msg_history"?>"><span><i class="icon-leaf"></i> Notification History </span></a>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> SMS </span>
													<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_sms"?>"><span><i class="icon-leaf"></i> SMS Dashboard </span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_sms_history"?>"><span><i class="icon-leaf"></i> SMS History </span></a>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Profile</span>
													<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_profile"?>"><span><i class="icon-leaf"></i> Profile Details</span></a>
														</li>
														<li style="display:none">
															<a href="<?php echo URL."help/edit_sub_admin_profile"?>"><span><i class="icon-leaf"></i> Edit Profile </span></a>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Tools </span>
													<ul>
														<li style="display:none">
															<a href="<?php echo URL."help/sub_admin_change_pwd"?>"><span><i class="icon-leaf"></i> Change Password </span></a>
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