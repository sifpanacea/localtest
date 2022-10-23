<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = lang('signup_title');

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
$no_main_header = true;
$page_body_prop = array("id"=>"login");
include("inc/header.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
		<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
		<header id="header">
			<!--<span id="logo"></span>-->

			<div id="logo-group">
				<span id="logo"> <img src="<?php echo IMG; ?>logo-cut.png" alt="TLSTEC Admin"> </span>

				<!-- END AJAX-DROPDOWN -->
			</div>
			<span id="login-header-space"> <span class="hidden-mobile"><?php echo lang('signup_already_reg');?></span> <a href="<?php echo URL.'auth/login'; ?>" class="btn bg-color-greenDark txt-color-white"><?php echo lang('signup_sign_in');?></a></span>

		</header>

		<div id="main" role="main">

			<!-- MAIN CONTENT -->
			<div id="content" class="container">

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm" style="
    width: 676px;">
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
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<div class="well no-padding">
						
 						<?php
						 $attributes = array('class' => 'smart-form client-form', 'id' => 'smart-form-register');

  							 echo  form_open('signup/create_customer',$attributes);
						?>


							<!--<form action="php/demo-register.php" id="smart-form-register" class="smart-form client-form">-->
								<header>
									<?php echo lang('signup_subheading');?>
								</header>
								<fieldset>
									<section>
										<label class="input" id="companyname_label"> <i class="icon-append fa fa-building"></i>
											<input type="text" id="companyname" name="companyname" value="<?php echo set_value('companyname');?>" placeholder="<?php echo lang('signup_customer_company_name');?>">
											<b class="tooltip tooltip-bottom-right"><?php echo lang('signup_customer_company_name_help');?></b> </label>
									</section>

									
                                    <section>
										<label class="input"> <i class="icon-append fa fa-building"></i>
											<input type="text" id="companywebsite" name="companywebsite" placeholder="<?php echo lang('signup_customer_company_website');?>" value="<?php echo set_value('companywebsite');?>">
											<b class="tooltip tooltip-bottom-right"><?php echo lang('signup_customer_company_website_help');?></b> </label>
									</section>
									
									<section>
                                    <label class="textarea textarea-expandable"> <i class="icon-append fa fa-envelope-o"></i>
                                    <textarea rows="3" id="companyaddress" name="companyaddress" class="custom-scroll" placeholder="<?php echo lang('signup_customer_company_address');?>"><?php echo set_value('companyaddress');?></textarea>
                                    </section>
									
									<section>
										<label class="input"> <i class="icon-append fa fa-envelope"></i>
											<input type="email" id="email" name="email" placeholder="<?php echo lang('signup_customer_company_email');?>" value="<?php echo set_value('email');?>">
											<b class="tooltip tooltip-bottom-right"><?php echo lang('signup_customer_company_email_help');?></b> </label>
									</section>

									<section>
										<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" name="password" placeholder="<?php echo lang('signup_customer_password');?>" id="password" value="<?PHP echo set_value('password'); ?>">
											<b class="tooltip tooltip-bottom-right"><?php echo lang('signup_customer_password_help');?></b> </label>
									</section>

									<section>
										<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" id="confirmpassword" name="confirmpassword" placeholder="<?php echo lang('signup_customer_confirm_password');?>" value="<?PHP echo set_value('confirmpassword'); ?>">
											<b class="tooltip tooltip-bottom-right"><?php echo lang('signup_customer_confirm_password_help');?></b> </label>
									</section>
                                    
								</fieldset>

								<fieldset>
									<div class="row">
										<section class="col col-6">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="text" id="username" name="username" placeholder="<?php echo lang('signup_customer_username');?>" value="<?PHP echo set_value('username');?>">
											</label>
										</section>
										<section class="col col-6">
											<label class="input"> <i class="icon-append fa fa-phone"></i>
												<input type="text" name="mobile" id="mobile" placeholder="<?php echo lang('signup_customer_company_contact_mobile');?>" value="<?php echo set_value('mobile');?>">
											</label>
										</section>
									</div>

									<div class="row">
										<section class="col col-6">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="text" id="contactperson" name="contactperson" value="<?php echo set_value('contactperson');?>" placeholder="<?php echo lang('signup_customer_company_contact_person');?>">
											<b class="tooltip tooltip-bottom-right"><?php echo lang('signup_customer_company_contact_person_help');?></b></label>
										</section>
										<section class="col col-6">
											<label class="select">
												<select name="plan">
													<option value="0" selected="" disabled=""><?php echo lang('signup_customer_plan');?></option>
													<option value="Bronze"><?php echo lang('signup_customer_plan_bronze');?></option>
													<option value="Silver"><?php echo lang('signup_customer_plan_silver');?></option>
													<option value="Gold"><?php echo lang('signup_customer_plan_gold');?></option>
													<option value="Diamond"><?php echo lang('signup_customer_plan_diamond');?></option>
												</select> <i></i> </label>
										</section>
									</div>

									<section>
										<label class="checkbox">
											<input type="checkbox" name="subscription" id="subscription">
											<i></i><?php echo lang('signup_customer_offers');?></label>
										<label class="checkbox">
											<input type="checkbox" name="terms" id="terms">
											<i></i><?php echo lang('signup_customer_tc');?></label>
									</section>
								</fieldset>
								<footer>
                                <?php echo form_submit( 'submit1',lang('signup_customer_req'), "class='btn btn-primary'"); ?>
									<!--<button type="submit" class="btn btn-primary"><?php echo form_close();?>
										Register
									</button>-->
								</footer>

								<div class="message">
									<i class="fa fa-check"></i>
									<p>
										<?php echo lang('signup_customer_thanks');?>
									</p>
								</div>
							</form>

						</div>
						<h5 class="text-center"> <?php echo lang('common_follow');?></h5>
													
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
		
		<?php echo lang('tc');?>

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script type="text/javascript">
	runAllForms();
	
	// Model i agree button
	$("#i-agree").click(function(){
		$this=$("#terms");
		if($this.checked) {
			$('#myModal').modal('toggle');
		} else {
			$this.prop('checked', true);
			$('#myModal').modal('toggle');
		}
	});
	
	// Validation
	$(function() {
		// Validation
		$("#smart-form-register").validate({

			// Rules for form validation
			rules : {
				companyname : {
					required : true,
					minlength : 5,
					maxlength : 25
				},
				companywebsite : {
					required : true
				},
				companyaddress : {
					required : true
				},
				email : {
					required : true,
					email : true
				},
				password : {
					required : true,
					minlength : 8,
					maxlength : 25
				},
				confirmpassword : {
					required : true,
					minlength : 8,
					maxlength : 25,
					equalTo : '#password'
				},
				username : {
					required : true
				},
				mobile : {
					required : true,
					number : true
				},
				contactperson : {
					required : true
				},
				plan : {
					required : true
				},
				terms : {
					required : true
				}
			},

			// Messages for form validation
			messages : {
				companyname : {
					required : <?php echo lang('common_comp_name_req');?>,
					minlength : <?php echo lang('common_comp_name_min');?>,
					maxlength : <?php echo lang('common_comp_name_max');?>
				},
				companywebsite : {
					required : <?php echo lang('common_comp_site_req');?>
				},
				companyaddress : {
					required : <?php echo lang('common_comp_addr_req');?>
				},
				email : {
					required : <?php echo lang('common_email_req');?>,
					email : <?php echo lang('common_email_valid');?>
				},
				password : {
					required : <?php echo lang('common_pass_req');?>,
					minlength : <?php echo lang('common_pass_min');?>,
					maxlength : <?php echo lang('common_pass_max');?>
				},
				confirmpassword : {
					required : <?php echo lang('common_con_pass_req');?>,
					minlength : <?php echo lang('common_con_pass_min');?>,
					maxlength : <?php echo lang('common_con_pass_max');?>,
					equalTo : <?php echo lang('common_con_pass_equal');?>
				},
				username : {
					required : <?php echo lang('common_user_req');?>
				},
				mobile : {
					required : <?php echo lang('common_mobile_req');?>,
					number : <?php echo lang('common_mobile_no');?>
				},
				contactperson : {
					required : <?php echo lang('common_cp_req');?>
				},
				plan : {
					required : <?php echo lang('common_plan_req');?>
				},
				terms : {
					required : <?php echo lang('common_tc_req');?>
				}
			},

			// Ajax form submition
			submitHandler : function(form) {
				$(form).ajaxSubmit({
					success : function() {
						$("#smart-form-register").addClass('submited');
					}
				});
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
		
		// Restricting special characters in app name
	$('#companyname').bind('keypress', function (event) {
		var regex = /^\s*[a-zA-Z0-9,\s]+\s*$/;  //new RegExp("^[a-zA-Z0-9]+$"); 
		var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		if (!regex.test(key)) 
		{
			$('#companyname_label').addClass("state-error");
			event.preventDefault();
			return false;
		}
		else
		{
		   $('#companyname_label').removeClass("state-error");  
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
				
			});
<?php } ?>
});
</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>