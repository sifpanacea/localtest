<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Edit App";

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
									<h5>Editing an existing app</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Applications > MY Apps </span>
										<br>
										<br>
										Apps created by you will be listed here. To edit an app, just click <button class="btn btn-success btn-xs">Edit</button> option. By Clicking this you will be redirected to App properties page with properties of selected app. There you can edit your app and as well as the workflow.
										
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