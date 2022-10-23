<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Reset Password";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
$no_main_header = true;
$page_body_prop = array("id"=>"login", "class"=>"animated fadeInDown");
include("inc/header.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
		<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
		<header id="header">
			<!--<span id="logo"></span>-->

			<div id="logo-group">
				<span id="logo"> <img src="<?php echo IMG; ?>logo-cut.png" alt="TLSTEC"> </span>

				<!-- END AJAX-DROPDOWN -->
			</div>

			<!--<span id="login-header-space"> <span class="hidden-mobile"><?php echo lang('login_need_acc');?></span><a href="<?php echo URL.'auth/signup'; ?>" class="btn bg-color-greenDark txt-color-white"><?php echo lang('login_create_acc');?></a></span>-->

		</header>

		<div id="main" role="main">

			<!-- MAIN CONTENT -->
			<div id="content" class="container">

				<div class="row">
					<!--<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
						
						<div class="hero">

							<div class="pull-left login-desc-box-l">
								<h4 class="paragraph-header">Imagine how sophisticated your office will be if your office is paperless too ! Want to experience? Join with us !</h4>
								<div class="login-app-icons">
								</div>
							</div>
							
							<img src="<?php echo IMG; ?>demo/iphoneview.jpg" class="pull-right display-image" alt="" style="width:210px">

						</div>

						<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<h5 class="about-heading">Moved out of paper, but regretting about typing in web based forms ?</h5>
						<p>
							 Stop worrying. Fill forms on your own handwriting. Getting excited ? Want to know more ? Just one step ahead ! signup !
						</p>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<h5 class="about-heading">Design and deploy business apps instantly!</h5>
						<p>
							We are not just only let you to design your apps. You can also deploy your apps in our secure cloud in minutes and make your enterprise progress easily !
						</p>
					</div>
				</div>
              
			</div>-->
					<div class="col-xs-12 col-sm-6">
						<div class="well no-padding">
							<!--<form action="auth/forgot_password" id="login-form" class="smart-form client-form">-->
							 <?php
						 $attributes = array('class' => 'smart-form client-form', 'id' => 'login-form');

  							 echo  form_open('diabetic_care_auth/reset_password/' . $code,$attributes);
						?>
								<header>
									Reset Password
								</header>

								<fieldset>
									
									<section>
										<label class="label">New password</label>
										<label class="input"> <i class="icon-append fa fa-envelope"></i>
											<input type="password" name="new">
											<b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-teal"></i> Please enter your new password</b></label>
									</section>
									<section>
										<label class="label">Confirm new password</label>
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="password" name="new_confirm">
											<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Re-enter your new password</b> </label>
									</section>
        
								</fieldset>
								<footer>
									<button type="submit" class="btn btn-primary">
										<i class="fa fa-refresh"></i> Reset
									</button>
								</footer>
							<!--</form>-->
							<?php echo form_close();?>

						</div>
						
						<!--<h5 class="text-center"> - Or sign in using -</h5>
															
										<ul class="list-inline text-center">
											<li>
												<a href="javascript:void(0);" class="btn btn-primary btn-circle"><i class="fa fa-facebook"></i></a>
											</li>
											<li>
												<a href="javascript:void(0);" class="btn btn-info btn-circle"><i class="fa fa-twitter"></i></a>
											</li>
											<li>
												<a href="javascript:void(0);" class="btn btn-warning btn-circle"><i class="fa fa-linkedin"></i></a>
											</li>
										</ul>-->
					
					</div>
				</div>
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

		<!--<script type="text/javascript">
			runAllForms();

		</script>-->

<?php 
	//include footer
	include("inc/footer.php"); 
?>