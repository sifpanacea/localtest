<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Admin Profile";

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
									<h5>Admin Profile</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> My Profile </span>
										<br>
										<br> * Profile Info </br>
										<br> * Subscription Details </br>
										<br> * Enterprise Details </br>
										<br> * Recent Activities </br>
										<br><b> Profile Info </b><br>
										<br> # Admin's basic info will be listed here 
										          - The Admin's role and contact information<br>
										<br><b> Subscription Details </b><br>
										<br># This tab shows the admin's enterprise subscription details in tabular form. Admin can upgrade his subscription at any time using  <a class="btn btn-sm btn-primary"> <i class="fa fa-level-up"></i> Upgrade</a>  option<br>
										<br><b> Enterprise Details </b><br>
										<br># This tab shows the admin's enterprise details ( website and address details ). <br>
										<br><b> Recent Activities </b><br>
										<br># This tab shows the admin's recent app creation activity in tabular form. <br>
										<br>
										<div class="alert alert-info fade in">
						                <i class="fa-fw fa fa-info"></i>
						                <strong>Info </strong> Admin can update his profile details using  <a class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> Edit Profile</a>  option 
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