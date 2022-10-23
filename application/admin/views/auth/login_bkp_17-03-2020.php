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
<style>
#myCarousel .item {
  height: 350px;
}
@media only screen and (max-width:600px){
	h5 {
    position: relative;
    bottom: 0px;
    margin-right: -20px;
    margin-left: -11px;
    left: 0px;
    right: 0px;
		}
	#logo img{
		    margin-top: -25px;
		    height: 148px;
	}
	.panacea-custom-title {

		        margin-top: -57px;
    			margin-left: 50px;
	}		
}
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
<header id="header" style="height:125px">
	<!--<span id="logo"></span>-->

	<div id="logo-group">
		<span id="logo"> <img src="<?php echo IMG; ?>PANACEA.jpg" alt="PANACEA" height="100px" style="margin-top:-23px"></span>

		<!-- END AJAX-DROPDOWN -->
	</div>
	<div class="panacea-custom-title">
					<p class="panacea-headertext-top" style="">PANACEA </p>
					<p class="panacea-headertext-bottom" style="">SCHOOL HEALTH PROGRAM</p>
					</div>
	<!--<h5 class="col col-lg-4 col-lg-offset-3"> <b>PANACEA SCHOOL HEALTH PROGRAM </b></h5>-->
	
	<span id="login-header-space" class="hide">
	
	
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
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-7 hidden-xs hidden-sm">
<!-- 				<h1 class="txt-color-red login-header-big">CloudCask</h1> -->
				<div id="myCarousel" class="carousel fade">
											<ol class="carousel-indicators">
												<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
												<li data-target="#myCarousel" data-slide-to="1" class=""></li>
												<li data-target="#myCarousel" data-slide-to="2" class=""></li>
												<li data-target="#myCarousel" data-slide-to="3" class=""></li>
											</ol>
											<div class="carousel-inner">
												<!-- Slide 1 -->
												<div class="item active">
													<img src="<?php echo IMG; ?>IMG_7767.JPG" alt="">
													<div class="carousel-caption caption-right hide">
														<h4>Title 1</h4>
														<p>
															Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
														</p>
														<br>
														<a href="javascript:void(0);" class="hide btn btn-info btn-sm">Read more</a>
													</div>
												</div>
												<!-- Slide 2 -->
												<div class="item">
													<img src="<?php echo IMG; ?>IMG_7766.JPG" alt="">
													<div class="carousel-caption caption-left hide">
														<h4>Title 2</h4>
														<p>
															Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
														</p>
														<br>
														<a href="javascript:void(0);" class="hide btn btn-danger btn-sm">Read more</a>
													</div>
												</div>
												<!-- Slide 3 -->
												<div class="item">
													<img src="<?php echo IMG; ?>IMG_7475.JPG" alt="">
													<div class="carousel-caption hide">
														<h4>A very long thumbnail title here to fill the space</h4>
														<br>
													</div>
												</div>
												<div class="item">
													<img src="<?php echo IMG; ?>IMG_7771.JPG" alt="">
													<div class="carousel-caption hide">
														<h4>A very long thumbnail title here to fill the space</h4>
														<br>
													</div>
												</div>
											</div>
											<a class="left carousel-control" href="#myCarousel" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a>
											<a class="right carousel-control" href="#myCarousel" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a>
										</div>
              
			</div>
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
				<div class="well no-padding">
				
                <?php
						 $attributes = array('class' => 'smart-form client-form', 'id' => 'login-form');

  							 echo  form_open('auth/login',$attributes);
						?>
				<!--	<form action="<?php /*?><?php echo URL; ?><?php */?>" id="login-form" class="smart-form client-form">-->
						<header>DashBoard LogIn</header>

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
				
													
							
			</div>
		</div> 
		<!--<h5 class="text-center" style="position: fixed;bottom: 0px;margin-right: auto;margin-left: auto;left: 0px;right: 0px;"><b>| Powered By  - <a href="http://www.haviktec.com" target="_blank"><img class="text-center" src="<?php //echo IMG; ?>Havik_logo.png" alt="Havik" width="200" height="80"></a> |</b></h5>-->
		
		<h5 class="text-center"><b>| Powered By  - <a href="http://www.haviktec.com" target="_blank"><img class="text-center" src="<?php echo IMG; ?>sif.png" alt="Havik" width="100" height="100"></a> - SIF NOTE | </b></h5>
		
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
	$('.carousel.fade').carousel({
				interval : 3000,
				cycle : true
			});
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