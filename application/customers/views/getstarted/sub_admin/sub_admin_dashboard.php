<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Dashboard";

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
		$breadcrumbs["Getstarted"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT --><div id="content">
	<div class="well well-sm bg-color-darken txt-color-white text-center">
									<h5>Dashboard</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Dashboard </span>
										<br><br>
										The admin dashboard provides key insights into your enterprise's activities as well as shortcuts to important and common admin actions
										<br>
										<br>
										<ul id="sparks" class="">
					<li>
						<h5>Subscription<span class="txt-color-blue"> Number of Days left</span></h5>
					</li>
					<li>
						<h5> Papers Saved <span class="txt-color-purple"><i class="fa fa-arrow-circle-up"></i> Number of papers saved </span></h5>
					</li>
					<li>
						<h5> Trees Saved <span class="txt-color-greenDark"><i class="glyphicon glyphicon-tree-deciduous"></i> Number of trees saved</span></h5>
					</li>
				</ul><br><i>NOTE : Approximately, 8333 pieces of paper equals a tree </i><br>
										<br> * Live Feeds </br>
										<br> * Feedbacks </br>
										<br> * Events </br>
										<br> * Query App </br>
										<br><b> Live Feeds </b><br>
										<br><a rel="tooltip" data-placement="right" data-original-title="<i class='fa fa-check text-success'></i> Graph based on the number of documents submitted per second" data-html="true"># Document live flow</a><br>
										<br># Applications Limit ( as per the plan subscribed ) <br>
										<br># Document submissions limit ( as per the plan subscribed ) <br>
										<br># Third Party Subscription limit ( as per the plan subscribed ) <br>
										<br><a rel="tooltip" data-placement="right" data-original-title="<i class='fa fa-check text-success'></i> Number of documents completed a full cycle of workflow" data-html="true"># Finished Workflows</a><br>
										<br><a rel="tooltip" data-placement="right" data-original-title="<i class='fa fa-check text-success'></i> Number of documents are in progress" data-html="true"># Unfinished Workflows</a><br>
										<br># Apps usage ( Graphical representation ) <br>
										<br># Disk space <br>
										<br><a rel="tooltip" data-placement="right" data-original-title="<i class='fa fa-check text-success'></i> Analytics pattern saved for future reference" data-html="true"># Saved Patterns</a><br>
										<br><b> Feedbacks </b><br>
										<br>All feedbacks will be listed here<br><br>
										<table class="table table-bordered">
										<tr>
											<td><B>
												Feedback Name
											</B></td>
											<td> Name of the feedback. By clicking this, it will redirect to <a href="<?php echo URL.'help/sub_admin_feedback_prop'?>">properties</a> page </td>
											</tr>
											<tr>
											<td><b>
												Total Users
											</b></td>
											<td> Total number of users assigned for respective feedback </td>
											</tr>
											<tr>
											<td><b>
												Replied Users
											</b></td>
											<td> Number of users replied </td>
											</tr>
											<tr>
											<td><b>
												Description
											</b></td>
											<td> Description of the feedback </td>
											</tr>
											<tr>
											<td><b>
												Feedback Summary
											</b></td>
											<td> Graphical representation of feedback summary </td>
											</tr>
											</table>
											<i>NOTE : This feedback related details can be converted to pdf by using the option <i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i><u>PDF</u> in <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa-lg"></i></button> menu </i><br><br>
										<br><b> Events </b><br>
										<br>All events will be listed here<br><br>
										<table class="table table-bordered">
										<tr>
											<td><B>
												Event Name
											</B></td>
											<td> Name of the event. By clicking this, it will redirect to <a href="<?php echo URL.'help/sub_admin_event_prop'?>">properties</a> page </td>
											</tr>
											<tr>
											<td><b>
												Total Users
											</b></td>
											<td> Total number of users assigned for respective event </td>
											</tr>
											<tr>
											<td><b>
												Confirmed Users
											</b></td>
											<td> Number of users confirmed </td>
											</tr>
											<tr>
											<td><b>
												Event Time
											</b></td>
											<td> Starting time of the event </td>
											</tr>
											<tr>
											<td><b>
												Event Summary
											</b></td>
											<td> Graphical representation of event summary </td>
											</tr>
											</table>
											<i>NOTE : This event related details can be converted to pdf by using the option <i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i><u>PDF</u> in <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa-lg"></i></button> menu </i><br><br>
										<br><b> Query App </b><br>
										<br>You can query any app with some your defined patterns and can view the results. To query an app, click <button class="btn btn-warning btn-xs">App Analytics</button> of respective app.<br>Provide values and select patterns such as AND or OR. Click <a class="btn btn-default btn-sm" href="javascript:void(0);">Query</a>. Results will be displayed<br>
										<br><b> Saved Patterns </b><br>
										<br>You can save any defined patterns and can view the results later. After quering,to save as pattern, click <a class="btn btn-default btn-sm" href="javascript:void(0);">Save Query Pattern</a> button. Provide title and description. Click Submit ! That's it ! !<br>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-right" onclick="window.history.back();">
											Back
										</button>
										</div>
									</div><!-- /.modal-content -->
								</div>
	<div class="well well-sm bg-color-darken txt-color-white text-center">
	
									
								</div>
	</div><!--END MAIN CONTENT-->
	

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<?php 
	//include footer
	include("inc/footer.php"); 
?>