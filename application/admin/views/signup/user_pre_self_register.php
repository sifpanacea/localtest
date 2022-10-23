<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "User Registration";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
//$page_css[] = "lockscreen.min.css";
$no_main_header = true;
include("inc/header.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<!-- MAIN CONTENT -->

	<?php 
	  $attributes = array('class'=>'lockscreen animated flipInY');
	  echo form_open('signup/register_user_with_device',$attributes);
	  ?>
		<div class="logo">
			<h1 class="semi-bold"><img src="<?php echo IMG; ?>/demo/iphoneview.jpg" alt="" /> Enter Your Device Unique Number</h1>
		</div>
		<div>
			<div>
                <div class="input-group">
					<input class="form-control" id="device_unique_no" name="device_unique_no" type="text" placeholder="Device Unique Number">
					<div class="input-group-btn">
						<button class="btn btn-primary" type="submit">
							<i class="fa fa-key"></i>
						</button>
					</div>
				</div>
				
			</div>

		</div>
		<p class="font-xs margin-top-5">
			© 2015 TLSTEC · All Rights Reserved.

		</p>
	<?php echo form_close();?>

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php
	// include page footer
	include("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script>

	$(document).ready(function() {
		
		// PAGE RELATED SCRIPTS
		<?php if($message) { ?>
		$.smallBox({
						title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
						content   : "<?php echo $message?>",
						color     : "#2c699d",
						iconSmall : "fa fa-bell bounce animated",
						timeout   : 4000
						
					});
	<?php } ?>
	})

</script>
