<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Third Party";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["thirdparty"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
<?php if($message) { ?>	
<div class="alert alert-success alert-block">
						<a class="close" data-dismiss="alert" href="#">×</a>
						<h4 class="alert-heading">Message!</h4>
						<?php echo $message; ?>
					</div><?php } ?>
	<!-- MAIN CONTENT -->
	<div id="content">
	<div class="well well-sm bg-color-darken txt-color-white text-center">
									<h5>Third Party Services</h5>
									
								</div>
	
			   <div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<ul class="demo-btns">
									<li>
										<a href="<?php echo (URL.'example/request_dropbox')?>" id="eg1" class="btn btn-primary"> <i class="fa fa-dropbox"></i> Drop Box </a>
									</li>
									<li>
										<a href="<?php echo (URL.'api/api')?>" id="eg2" class="btn btn-primary"> <i class="fa fa-google-plus"></i> Google Drive </a>
									</li>
									<li>
										<a href="<?php echo (URL.'api/api')?>" id="eg3" class="btn btn-primary"> <i class="fa fa-cloud"></i> Cloud 9 </a>
									</li>
									<li>
										<a href="<?php echo (URL.'api/api_paas')?>" id="eg4" class="btn btn-primary"> <i class="fa fa-leaf"></i> PAAS API </a>
									</li>
								</ul>
										</div>
										</div>
										</div>
				
				
				
				
				
				


	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script>

	$(document).ready(function() {
		// PAGE RELATED SCRIPTS
	})

</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>