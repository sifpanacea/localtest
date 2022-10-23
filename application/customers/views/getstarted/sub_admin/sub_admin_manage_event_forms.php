<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Manage Event Forms";

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
									<h5>Manage Event Forms</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Events > Manage Event Forms </span>
										<br><br> Form created event app requests will be shown here <br><br>
										<u> Name </u><br>
										     &nbsp;&nbsp;Name of the event <br><br>
										<u> Description </u><br>
										     &nbsp;&nbsp;Description of the event <br><br>
										<u> Form Status </u><br>
										     &nbsp;&nbsp;Status of the event <br><br>
										<u> Created Time </u><br>
										     &nbsp;&nbsp;Event created time <br><br>
										<u> Action </u><br>
										     &nbsp;&nbsp; * If enterprise admin created and pushed the form, sub admin can view and use that form. <br><br>
											    <b> View this form </b> - Sub Admin can view the form if he need changes, he can comment. Then it will be sent back to enterprise admin for re-creation<br><br>
											    <b> Use this form  </b> - By clicking this option,sub admin will be redirected to <a href='<?php echo URL.'help/sub_admin_assign_events'?>'>assign events</a> page with this form selected by default.<br><br>
                                             &nbsp;&nbsp; * If enterprise admin not yet created the form,then "Form yet to be designed" message will be shown. <br><br>
											<br>
											
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