<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "User Assigned Event Properties";

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
									<h5>User Assigned Event Properties</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Events > User Assigned Events > Properties </span>
										<br><br> Assigned event properties <br><br>
										<table class="table table-bordered">
										<th> Properties </th>
										<tr>
											<td><B>
											Description
											</B></td>
											<td> Description of the event </td>
											</tr>
											<tr>
											<td><b>
												Event Form
											</b></td>
											<td> Form for the event </td>
											</tr>
											<tr>
											<td><b>
												Event Starting Date
											</b></td>
											<td> Starting date of the event </td>
											</tr>
											<tr>
											<td><b>
												Event Start Time
											</b></td>
											<td> Starting time of the event</td>
											</tr>
											<tr>
											<td><b>
												Event End Time
											</b></td>
											<td> Ending time of the event</td>
											</tr>
											<tr>
											<td><b>
												Event Place
											</b></td>
											<td> Event to be held </td>
											</tr>
											<tr>
											<td><b>
												Event Ending
											</b></td>
											<td> End date of the event </td>
											</tr>
											</table><br>
											<table class="table table-bordered">
										<th> Event Form Properties </th>
										<tr>
											<td><B>
												
												Event Form
											</B></td>
											<td>Name of the event form</td>
											</tr>
											<tr>
											<td><b>
												Form Description
											</b></td>
											<td> Description of the event form</td>
											</tr>
											<tr>
											<td><b>
												Expiry
											</b></td>
											<td> Expiry of the event form </td>
											</tr>
											<tr>
											<td><b>
												Created By
											</b></td>
											<td> Form created enterprise admin and date details </td>
											</tr>
											<tr>
											<td><b>
												Number Of Pages
											</b></td>
											<td> Total number of pages in the event form </td>
											</tr>
											<tr>
											<td><b>
												Version
											</b></td>
											<td> Current version of the event form </td>
											</tr>
											</table><br>
											<table class="table table-bordered">
										<th> User Status </th>
										<tr>
											<td><B>
												User ID
											</B></td>
											<td>Identity of the user ( Email ID )</td>
											</tr>
											<tr>
											<td><b>
												Status
											</b></td>
											<td> Status about whether the user filled or not </td>
											</tr>
											<tr>
											<td><b>
												Form
											</b></td>
											<td> If the user filled the form, sub admin can either view and confirm the form or disapprove the form. Sub admin can mention the needed changes in comment </td>
											</tr>
											</table>
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