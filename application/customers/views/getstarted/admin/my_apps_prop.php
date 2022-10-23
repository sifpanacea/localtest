<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "My App properties";

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
									<h5>Properties of My app </h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Applications > My Apps </span>
										<br>
										<br>
										To view the properties of an app, just click <button class="btn btn-success btn-xs">View Properties</button> option.
										<br>
										<br>
										<table class="table table-bordered">
										<th> Properties </th>
										<tr>
											<td><B>
												Description
											</B></td>
											<td> Description of the app </td>
											</tr>
											<tr>
											<td><b>
												Category
											</b></td>
											<td> Category of the app </td>
											</tr>
											<tr>
											<td><b>
												Application Type
											</b></td>
											<td> Since it belongs to enterprise apps, by default it is Private type</td>
											</tr>
											<tr>
											<td><b>
												Expiry
											</b></td>
											<td> Expiry date of the app </td>
											</tr>
											<tr>
											<td><b>
												Number Of Pages
											</b></td>
											<td> Total pages</td>
											</tr>
											<tr>
											<td><b>
												Version
											</b></td>
											<td> Current version of the app</td>
											</tr>
											<tr>
											<td><b>
												Created By
											</b></td>
											<td> Identity of the enterprise admin and date created</td>
											</tr>
											</table>
											<br>
											<table class="table table-bordered">
										<th> Application Header Details </th>
										<tr>
											<td><B>
												Company Name
											</B></td>
											<td> Name of the company </td>
											</tr>
											<tr>
											<td><b>
												Address
											</b></td>
											<td> Address details of the company </td>
											</tr>
											<tr>
											<td><b>
												Logo
											</b></td>
											<td> Uploaded logo</td>
											</tr>
											</table>
											<br>
											<table class="table table-bordered">
										<th> Workflow Assignments  </th>
										<tr>
											<td><B>
												Stage Name
											</B></td>
											<td> Name of the stage </td>
											</tr>
											<tr>
											<td><b>
												User
											</b></td>
											<td> Identity of the users assigned </td>
											</tr>
											<tr>
											<td><b>
												Stage Type
											</b></td>
											<td> Type of the stage ( device or web )</td>
											</tr>
											<tr>
											<td><b>
												Can View
											</b></td>
											<td> Sections which are assigned view permissions to the assigned users </td>
											</tr>
											<tr>
											<td><b>
												Can Edit
											</b></td>
											<td> Sections which are assigned edit permissions to the assigned users </td>
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