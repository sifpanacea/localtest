<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Feedback Properties";

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
									<h5>Feedback Properties</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Feedbacks > Assigned Feedbacks > Properties </span>
										<br><br> Assigned feedback properties <br><br>
										<table class="table table-bordered">
										<th> Properties </th>
										<tr>
											<td><B>
												Feedback name
											</B></td>
											<td> Name of the feedback</td>
											</tr>
											<tr>
											<td><b>
												Feedback Description
											</b></td>
											<td> Description of the feedback </td>
											</tr>
											<tr>
											<td><b>
												Feedback Expiry
											</b></td>
											<td> Expiry of the feedback </td>
											</tr>
											</table><br>
											<table class="table table-bordered">
										<th> Feedback Form Properties </th>
										<tr>
											<td><B>
												Feedback Form
											</B></td>
											<td>Name of the feedback form</td>
											</tr>
											<tr>
											<td><b>
												Form Description
											</b></td>
											<td> Description of the feedback form</td>
											</tr>
											<tr>
											<td><b>
												Expiry
											</b></td>
											<td> Expiry of the feedback form </td>
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
											<td> Total number of pages in the feedback form </td>
											</tr>
											<tr>
											<td><b>
												Version
											</b></td>
											<td> Current version of the feedback form </td>
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