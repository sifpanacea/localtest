<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "GHMC Login";

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
<div class="row" style="margin-top:150px">
<div class="col-md-offset-3 col-md-4 col-lg-4">
	<form action="ghmc_verification" method="POST" id="login-form" class="smart-form client-form" novalidate="novalidate">
								<header>
									GHMC Login
								</header>

								<fieldset>
									
									<section>
										<label class="label">Unique ID</label>
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="text" name="uniqueid">
											<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter unique ID</b></label>
									</section>

									<section>
										<label class="label">Password</label>
										<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" name="password">
											<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your mobile number</b> </label>
										<div class="note">
											<!--<a href="forgotpassword.html">Forgot password?</a> -->
										</div>
									</section>
								</fieldset>
								<footer>
									<button type="submit" class="btn btn-primary">
										Login
									</button>
								</footer>
		</div>					</form>
</div>
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
			color : "#C46A69",
			iconSmall : "fa fa-bell bounce animated",
			timeout : 4000
		});
	<?php } ?>
	})

</script>
