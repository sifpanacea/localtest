<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = lang('login_title');

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
		<span id="logo"> <img src="<?php echo IMG; ?>logo-cut.png" alt="TLSTEC"></span>

		<!-- END AJAX-DROPDOWN -->
	</div>

	<span id="login-header-space">
	
	
		<a href='<?php echo URL; ?>lang_switch/switchLanguage/english'>English (US)</a>
		<a href='<?php echo URL; ?>lang_switch/switchLanguage/hindi'>हिन्दी</a>
		<a href='<?php echo URL; ?>lang_switch/switchLanguage/zh_cn'>中国（简体）</a>
		<a href='<?php echo URL; ?>lang_switch/switchLanguage/zh_tw'>中國（繁體）</a>
	
	
	<span class="hidden-mobile"><?php echo lang('login_need_acc');?></span>
	<a href="<?php echo URL.'signup/customer_signup'; ?>" class="btn bg-color-greenDark txt-color-white" disabled ><?php echo lang('login_create_acc');?></a> <a href="<?php echo URL.'signup/api_signup'; ?>" class="btn bg-color-blueDark txt-color-white" disabled><?php echo lang('login_create_api');?></a></span> 

</header>

<div id="main" role="main">

	<!-- MAIN CONTENT -->
	<div id="content" class="container">

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
<!-- 				<h1 class="txt-color-red login-header-big">CloudCask</h1> -->
				<div class="hero">

					<div class="pull-left login-desc-box-l">
						<h4 class="paragraph-header"><?php echo lang('common_passage_1');?></h4>
						<div class="login-app-icons">
							<!--<a href="javascript:void(0);" class="btn bg-color-greenDark txt-color-white btn-sm">Frontend Template</a>
							<a href="javascript:void(0);" class="btn bg-color-greenDark txt-color-white btn-sm">Find out more</a>-->
						</div>
					</div>
					
					<img src="<?php echo IMG; ?>demo/iphoneview.jpg" class="pull-right display-image" alt="" style="width:210px">

				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<h5 class="about-heading"><?php echo lang('common_passage_2');?></h5>
						<p>
							 <?php echo lang('common_passage_3');?>
						</p>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<h5 class="about-heading"><?php echo lang('common_passage_4');?></h5>
						<p>
							<?php echo lang('common_passage_5');?>
						</p>
					</div>
				</div>
              
			</div>
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
				<div class="well no-padding">
				
                <?php
						 $attributes = array('class' => 'smart-form client-form', 'id' => 'login-form');

  							 echo  form_open('auth/login',$attributes);
						?>
				<!--	<form action="<?php /*?><?php echo URL; ?><?php */?>" id="login-form" class="smart-form client-form">-->
						<header><?php echo lang('login_subheading');?></header>

						<fieldset>
							
							<section>
								<label class="label" for="identity"><?php echo lang('login_identity_label');?></label>
                                <label class="input"> <i class="icon-append fa fa-user"></i>
									<input type="email" name="identity" id="identity">
									<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> <?php echo lang('login_identity_label_help');?></b></label>
							</section>

							<section>
								<label class="label" for="password"><?php echo lang('login_password_label');?></label>
                                <label class="input"> <i class="icon-append fa fa-lock"></i>
									<input type="password" name="password" id="password">
									<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> <?php echo lang('login_password_label_help');?></b> </label>
								<div class="note">
									<a href="<?php echo URL."auth/forgot_password";?>"><?php echo lang('login_forgot_password');?></a>
								</div>
							</section>

							<section>
								<label class="checkbox" for="remember">
                                <input type="checkbox" name="remember" value="1" id="remember">
									<i></i><?php echo lang('login_remember_label');?></label>
							</section>
						</fieldset>
						<footer>
							<button type="submit" class="btn bg-color-greenDark txt-color-white" value="Login" name="submit">
							<?php echo lang('login_submit_btn');?>
							</button>
						</footer>
				<!--	</form>-->
					<?php echo form_close();?>
				</div>
				<h5 class="text-center"><?php echo lang('common_follow');?></h5>
													
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
									<li>
										<a href="javascript:void(0);" class="btn btn-warning btn-circle"><i class="fa fa-youtube"></i></a>
									</li>
									<li>
										<a href="javascript:void(0);" class="btn btn-info btn-circle"><i class="fa fa-google-plus"></i></a>
									</li>
								</ul>
								
				
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

<script type="text/javascript">
	runAllForms();

	$(function() {
		// Validation
		$("#login-form").validate({
			// Rules for form validation
			rules : {
				identity : {
					required : true,
					email : true
				},
				password : {
					required : true,
					minlength : 3,
					maxlength : 20
				}
			},

			// Messages for form validation
			messages : {
				identity : {
					required : <?php echo lang('login_email_req');?>,
					email : <?php echo lang('login_email_valid');?>
				},
				password : {
					required : <?php echo lang('login_pass_req');?>
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});

	});
</script>
<script>
$(document).ready(function() {
	<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; <?php echo lang('common_message');?>",
				content : "<?php echo $message?>",
				color : "#C46A69",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>
});
</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>