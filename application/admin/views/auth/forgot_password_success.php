<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = lang('title');

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
$page_css[] = "lockscreen.css";
$no_main_header = true;
include("inc/header.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<!-- MAIN CONTENT -->

	    <div class="lockscreen animated flipInY">
		<div class="logo">
			<h1 class="semi-bold"><img src="<?php echo IMG; ?>logo-cut.png" alt="" /></h1>
		</div>
		<div>
			<div>
				<p class="text-muted">
					<h1 class="semi-bold"><?php echo lang('forgot_password_mail_sent');?></h1>
				</p>
				<br>
				<br>
				<p>
				 <a  class="btn btn-success" href="<?php echo URL.'auth/login'; ?>"> <?php echo lang('act_home');?></a>
				</p>
			</div>

		</div>
		<p class="font-xs margin-top-5">
			<?php echo lang('common_copy_rights');?>

		</p>
	  </div>

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