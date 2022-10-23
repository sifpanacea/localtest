<?php

//initilize the page
//require_once("inc/init.php");

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

		</header>

		<div id="main" role="main">

			<!-- MAIN CONTENT -->
			<div id="content" class="container">

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm" style="
    width: 676px;">
<!-- 				<h1 class="txt-color-red login-header-big">CloudCask</h1> -->
				<!--<div class="hero">

					<div class="pull-left login-desc-box-l">
						<h4 class="paragraph-header">Imagine how sophisticated your office will be if your office is paperless too ! Want to experience? Join with us !</h4>
						<div class="login-app-icons">
							<!--<a href="javascript:void(0);" class="btn bg-color-greenDark txt-color-white btn-sm">Frontend Template</a>
							<a href="javascript:void(0);" class="btn bg-color-greenDark txt-color-white btn-sm">Find out more</a>-
						</div>
					</div>
					
					<img src="<?php echo IMG; ?>demo/iphoneview.jpg" class="pull-right display-image" alt="" style="width:210px">

				</div>-->

				<!--<div class="row">
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
				</div>-->
              
			</div>
					<div class="col-sm-12 col-md-12 col-lg-7" style="margin-left: 20%;">
					<!--<p class="col-xs-12 col-sm-12 col-md-5"></p>-->
						<div class="well no-padding">
						
 						<?php
						 $attributes = array('class' => 'smart-form client-form', 'id' => 'smart-form-register');

  							 echo  form_open('signup/save_user_details_with_device',$attributes);
						?>


							
								<header>
									<?php echo lang('signup_subheading');?>
								</header>
								<fieldset>
                                    <section>
                            			<label class="label" for="first_name"><?php echo lang('register_user_fname_label', 'first_name');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="first_name" id="first_name" value="<?PHP echo set_value('first_name'); ?>">
										</label>
		     						</section>
									<section>
                            			<label class="label" for="last_name"><?php echo lang('register_user_lname_label', 'last_name');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="last_name" id="last_name" value="<?PHP echo set_value('last_name'); ?>">
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="email"><?php echo lang('register_user_email_label', 'email');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="email" name="email" id="email" value="<?PHP echo set_value('email'); ?>">
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="phone"><?php echo lang('register_user_phone_label', 'phone');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="phone" id="phone" value="<?PHP echo set_value('phone'); ?>">
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="password"><?php echo lang('register_user_password_label', 'password');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="password" name="password" id="password" value="<?PHP echo set_value('password'); ?>">
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="password_confirm"><?php echo lang('register_user_password_confirm_label', 'password_confirm');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="password" name="password_confirm" id="password_confirm" value="<?PHP echo set_value('password_confirm'); ?>">
										</label>
		     						</section>
									<section>
                                    <label class="label"><?php echo lang('register_user_groups');?></label>
									<?php foreach ($groups as $groupname):?>
									<label class="checkbox">
									<input type="checkbox" name="groupname[]" value="<?php echo $groupname;?>"><i></i>
									<?php echo $groupname;?>
									</label>
									<?php endforeach?>
                                    </section>
									 <section>
                                    <label class="label"><?php echo lang('register_user_subscribed_companies');?></label>
									<?php foreach ($subscribed_with as $companies):?>
									<label class="checkbox state-disabled"">
									<?php $checked= ' checked="checked"';?>
									<input type="checkbox" name="company[]" disabled="disabled" value="<?php echo $companies;?>"<?php echo $checked;?>><i></i>
									<?php echo $companies;?>
									</label>
									<?php $companyname = $companies; ?>
									<?php endforeach?>
                                    </section>
									<section>
                                    <label class="label"><?php echo lang('register_user_subscribed_plan');?></label>
									<label class="checkbox state-disabled"">
									<?php $checked= ' checked="checked"';?>
									<input type="checkbox" name="plan[]" disabled="disabled" value="<?php echo $plans;?>"<?php echo $checked;?>><i></i>
									<?php echo $plans;?>
									</label>
                                    </section>
									<?php echo form_hidden('dev_uni_no',$device_uniq_no);?>
									<?php echo form_hidden('company',$companyname);?>
									</fieldset>
								<footer>
                                <?php echo form_submit( 'submit1',lang('signup_customer_req'), "class='btn btn-primary'"); ?>
									
								</footer>

								<div class="message">
									<i class="fa fa-check"></i>
									<p>
										<?php echo lang('signup_customer_thanks');?>
									</p>
								</div>
							</form>

						</div>
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

	});
</script>
<script>
$(document).ready(function() {
	<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "<?php echo $message?>",
				color : "#C46A69",
				iconSmall : "fa fa-bell bounce animated",
				timeout:4000
				
			});
<?php } ?>
});
</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>