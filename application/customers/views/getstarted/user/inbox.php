<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Inbox";

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
									<h5>Inbox</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Inbox </span>
										<br><br>
										This inbox provides key insights into apps and docs assigned for you
										<br>
										<br> * Application </br>
										<br> * Document </br>
										<br><b> Application </b><br>
										<br>
										Apps assigned for you will populate here and will reside here until you open it.Once you open an app entry,it will be moved to "Installed Apps" tab.
										<br><br>Apps will be marked as <span class="label bg-color-orange">Application</span><br><br>In app detailed view, application and assigned admin related details will be shown.<br><br> To initiate a application, click <button class="btn btn-primary btn-sm replythis"><i class="fa fa-pencil-square-o"></i> Open </button> button. The form will open .Fill form and submit. That's it !<br>
										<br><b> Document </b><br>
										<br>Docs assigned for you will populate here and will reside here until you fill and forward to next stage. <br><br>Docs will be marked as <span class="label bg-color-orange">Document</span><br><br>In doc detailed view, current stage,previous stage and forwarded user related details will be shown.<br><br> If the document is a disapproved one, then reason will be shown.<br><br> To access a document, click <button class="btn btn-primary btn-sm replythis"><i class="fa fa-reply"></i> Access </button> button. The form will open .Fill form and submit. That's it !<br>
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