<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = lang('api_title');

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

			<span id="login-header-space"> <span class="hidden-mobile"><?php echo lang('api_already_reg');?></span> <a href="<?php echo URL.'auth/login'; ?>" class="btn bg-color-greenDark txt-color-white"><?php echo lang('api_sign_in');?></a></span>

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
						<h4><header><span style="color:red;"><?php if(isset($message)) { ?><?php echo $message;?><?php }?></span></header></h4>
 						<?php
						 $attributes = array('class' => 'smart-form client-form', 'id' => 'smart-form-register');

  							 echo  form_open('signup/api_create_customer',$attributes);
						?>


							<!--<form action="php/demo-register.php" id="smart-form-register" class="smart-form client-form">-->
								<header>
									<?php echo lang('api_subheading');?>
								</header>
								
								<fieldset>
									<section>
										<label class="input"> <i class="icon-append fa fa-building"></i>
											<input type="text" id="companyname" name="companyname" value="<?php echo set_value('companyname');?>" placeholder="<?php echo lang('api_customer_company_name');?>">
											<b class="tooltip tooltip-bottom-right"><?php echo lang('api_customer_company_name_help');?></b> </label>
									</section>
									
									<section>
										<label class="input"> <i class="icon-append fa fa-tags"></i>
											<input type="text" id="comp_type" name="comp_type" value="<?php echo set_value('comp_type');?>" placeholder="<?php echo lang('api_customer_company_type');?>">
											<b class="tooltip tooltip-bottom-right"><?php echo lang('api_customer_company_type_help');?></b> </label>
									</section>

                                    <section>
										<label class="input"> <i class="icon-append fa fa-laptop"></i>
											<input type="text" id="companywebsite" name="companywebsite" placeholder="<?php echo lang('api_customer_company_website');?>" value="<?php echo set_value('companywebsite');?>">
											<b class="tooltip tooltip-bottom-right"><?php echo lang('api_customer_company_website_help');?></b> </label>
									</section>

                                    <section>
                                    <label class="textarea textarea-expandable"> <i class="icon-append fa fa-envelope-o"></i>
                                    <textarea rows="3" id="companyaddress" name="companyaddress" class="custom-scroll" placeholder="<?php echo lang('api_customer_company_address');?>"><?php echo set_value('companyaddress');?></textarea>
                                    </section>
								</fieldset>
								
								<header>
									<?php echo lang('api_customer_primary');?>
								</header>

								<fieldset>
									<div class="row">
										<section class="col col-6">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="text" id="username" name="username" placeholder="<?php echo lang('api_customer_username');?>" value="<?PHP echo set_value('username');?>">
											</label>
										</section>
										<section class="col col-6">
											<label class="input"> <i class="icon-append fa fa-envelope"></i>
											<input type="email" id="email" name="email" placeholder="<?php echo lang('api_customer_company_email');?>" value="<?php echo set_value('email');?>">
											<b class="tooltip tooltip-bottom-right"><?php echo lang('api_customer_company_email_help');?></b> </label>
										</section>
									</div>

									<div class="row">
										<section class="col col-6">
											<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" name="password" placeholder="<?php echo lang('api_customer_password');?>" id="password" value="<?PHP echo set_value('password'); ?>">
											<b class="tooltip tooltip-bottom-right"><?php echo lang('api_customer_password_help');?></b> </label>
										</section>
										<section class="col col-6">
											<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" id="confirmpassword" name="confirmpassword" placeholder="<?php echo lang('api_customer_confirm_password');?>" value="<?PHP echo set_value('confirmpassword'); ?>">
											<b class="tooltip tooltip-bottom-right"><?php echo lang('api_customer_confirm_password_help');?></b> </label>
										</section>
									</div>
									<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-phone"></i>
											<input type="text" name="mobile"  id="mobile"  placeholder="<?php echo lang('api_customer_company_contact_mobile');?>"value="<?php echo set_value('mobile');?>">
										</label>
									</section>
									<section class="col col-6">
										<label class="select">
											<select name="customer">
												<option value="0" selected="" disabled=""><?php echo lang('api_customer_names');?></option>
												<?php if(isset($customerslist)): ?>
													<?php foreach ($customerslist as $customer):?>
													<option value='<?php echo $customer['_id']?>' ><?php echo ucfirst($customer['display_company_name'])?></option>
													<?php endforeach;?>
													<?php else: ?>
						        					<option value="1"  disabled=""><?php echo lang('api_no_customer');?></option>
						        				<?php endif ?>
											</select> <i></i>
										</label>
										</section>
									</div>

									<section>
										<label class="checkbox">
											<input type="checkbox" name="subscription" id="subscription">
											<i></i><?php echo lang('api_customer_offers');?></label>
										<label class="checkbox">
											<input type="checkbox" name="terms" id="terms">
											<i></i><?php echo lang('api_customer_tc');?></a></label>
									</section>
									
								</fieldset>
								<footer>
                                <?php echo form_submit( 'submit1',lang('api_customer_req'), "class='btn btn-primary'"); ?>
									<!--<button type="submit" class="btn btn-primary"><?php echo form_close();?>
										Register
									</button>-->
								</footer>

								<div class="message">
									<i class="fa fa-check"></i>
									<p>
										<?php echo lang('api_customer_thanks');?>
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
				comp_type : {
					required : true
				},
				companywebsite : {
					required : true
				},
				companyaddress : {
					required : true
				},

				username : {
					required : true
				},
				email : {
					required : true,
					email : true
				},
				password : {
					required : true,
					minlength : 8,
					maxlength : 20
				},
				confirmpassword : {
					required : true,
					minlength : 8,
					maxlength : 20,
					equalTo : '#password'
				},
				mobile : {
					required : true,
					number : true
				},
				customer : {
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
				comp_type : {
					required : <?php echo lang('common_type_req');?>
				},
				companywebsite : {
					required : <?php echo lang('common_comp_site_req');?>
				},
				companyaddress : {
					required : <?php echo lang('common_comp_addr_req');?>
				},
				username : {
					required : <?php echo lang('common_user_req');?>
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
				mobile : {
					required : <?php echo lang('common_mobile_req');?>,
					number : <?php echo lang('common_mobile_no');?>
				},
				customer : {
					required : <?php echo lang('common_customer_req');?>
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

	});
</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>