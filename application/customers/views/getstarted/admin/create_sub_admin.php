<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Create Sub Admin";

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
									<h5>Create Sub Admin</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Users Management > Sub Admin Management > Create Sub Admins </span>
										<br>
										<br>
										<table class="table table-bordered">
										<tr>
											<td><B>
												First name
											</B></td>
											<td> Must be a valid name</td>
											</tr>
											<tr>
											<td><b>
												Last name
											</b></td>
											<td> Must be a valid name </td>
											</tr>
											<tr>
											<td><B>
												Company name
											</B></td>
											<td> Name of the company where sub admin is getting registered. Cannot able to edit company name</td>
											</tr>
											<tr>
											<td><B>
												Email
											</B></td>
											<td> Must be a valid email id </td>
											</tr>
											<tr>
											<td><B>
												Phone
											</B></td>
											<td> Must be a valid 10 digit number </td>
											</tr>
											<tr>
											<td><B>
												Password
											</B></td>
											<td> Must be a valid one </td>
											</tr>
											<tr>
											<td><B>
												Confirm password
											</B></td>
											<td> Must match with password </td>
											</tr>
											</table>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-right" onclick="window.history.back();">
											Back
										</button>
									</div><!-- /.modal-content -->
								</div>
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