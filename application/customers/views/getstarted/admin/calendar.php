<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Calendar";

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
									<h5>Calendar</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Calendar </span>
										<br>
										<br>
										Calendar Features <br>
										<br> * My Events </br>
										<br> * App Created Schedule </br>
										<br> * App Expiry Schedule </br><br>
										<div class="alert alert-info fade in">
						                <i class="fa-fw fa fa-info"></i>
						                <strong>Info !</strong> You can uncheck any of the options provided to clear it from the calendar view
					                    </div>
										<br><b> My Events </b><br>
										<br>
										Add Events feature let you to create your own events<br><br>
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
												Event Colour
											</b></td>
											<td> You can select any of the available colours based on your event </td>
											</tr>
											</table>
										<i>NOTE : Your event will be added to Draggable Events section as soon as you add the event. From there you can drag and drop the event to any date. you can even edit or delete the event. By clicking on the event, edit and delete options are provided </i><br>
										<br><b> App Created Schedule </b><br>
										<br>Your application created schedule will be listed in the calendar<br><br>
										<i>NOTE : You cannot update the app created schedule</i><br>
										<br><b> App Expiry Schedule </b><br>
										<br>Your application expiry schedule will be listed in the calendar. You can update the app expiry schedule to some other day by simply drag and drop it to any desired date<br>
										<br><br>
										<div class="alert alert-warning fade in">
						                <i class="fa-fw fa fa-warning"></i>
						                <strong>Warning !</strong> You cannot place any event before the present date.
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