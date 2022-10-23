<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Assign Events";

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
									<h5>Assign Events</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Events > Assign Events </span>
										<br><br>
										Add Events feature let sub admin to create events<br><br>
										<table class="table table-bordered">
										<tr>
											<td><B>
												Event Icon
											</B></td>
											<td> You can select any of the available icons based on your event</td>
											</tr>
											<tr>
											<td><b>
												Event Title
											</b></td>
											<td> Title of the event </td>
											</tr>
											<tr>
											<td><b>
												Event Description
											</b></td>
											<td> Description of the event </td>
											</tr>
											<tr>
											<td><b>
												Select Users
											</b></td>
											<td> Any users or group of users can be selected to assign </td>
											</tr>
											<tr>
											<td><b>
												Select Event Form
											</b></td>
											<td> Any form can be selected within available forms </td>
											</tr>
											<tr>
											<td><b>
												Event Start Time
											</b></td>
											<td> Start time of the event </td>
											</tr>
											<tr>
											<td><b>
												Event End Time
											</b></td>
											<td> End time of the event</td>
											</tr>
											<tr>
											<td><b>
												Event Place
											</b></td>
											<td> Description of the event </td>
											</tr>
											<tr>
											<td><b>
												Event Colour
											</b></td>
											<td> sub Admin can select any of the available colours based on the event </td>
											</tr>
											</table>
										<i>NOTE : Event will be added to Draggable Events section as soon as the event added. From there you can drag and drop the event to any date. you can even edit or delete the event. By clicking on the particular event, edit and delete options are provided </i><br><br>
										<i>NOTE : Deleting the event will delete all the particular event related actions </i><br><br>
										<div class="alert alert-info fade in">
						                <i class="fa-fw fa fa-info"></i>
						                <strong>Info !</strong> User Status ( Joined or Denied etc., ) can be known in edit event option
					                    </div>
										<br>
										<div class="alert alert-warning fade in">
						                <i class="fa-fw fa fa-warning"></i>
						                <strong>Warning !</strong> Event cannot be placed before the present date.
					                    </div>
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