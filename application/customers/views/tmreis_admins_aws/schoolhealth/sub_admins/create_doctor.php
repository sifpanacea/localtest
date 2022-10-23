<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Add Doctors";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["refer_doctors"]["sub"]["add_doctors"]["active"] = true;


include("inc/nav.php");
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Referral Doctors"] = "";
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-6">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-user"></i> </span>
							<h2><?php echo "Add Doctor";?> </h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
                            
							<div class="widget-body no-padding">
                            <?php
						 	$attributes = array('class' => 'smart-form','id'=>'create_counsellor');
							echo  form_open('schoolhealth_sub_admin_portal/create_doctor',$attributes);
							?>
		      					<!--<form class="smart-form">-->
									<header>
										Please Enter The Doctor Information.
									</header>
									<fieldset>
                                    <section>
                            			<label class="label" for="first_name"><?php echo lang('create_counsellor_fname_label', 'first_name');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="first_name" id="first_name" value="<?PHP echo set_value('first_name'); ?>" required>
										</label>
		     						</section>
									<section>
                            			<label class="label" for="last_name"><?php echo lang('create_counsellor_lname_label', 'last_name');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="last_name" id="last_name" value="<?PHP echo set_value('last_name'); ?>" required>
										</label>
		     						</section>
                               
                                    <section>
                            			<label class="label" for="email"><?php echo lang('create_counsellor_email_label', 'email');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="email" name="email" id="email" value="<?PHP echo set_value('email'); ?>" required>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="phone"><?php echo lang('create_counsellor_phone_label', 'phone');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="phone" id="phone" value="<?PHP echo set_value('phone'); ?>" required>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="password"><?php echo lang('create_counsellor_password_label', 'password');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="password" id="password" value="<?PHP echo set_value('password'); ?>" required>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="password_confirm"><?php echo lang('create_counsellor_password_confirm_label', 'password_confirm');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="password_confirm" id="password_confirm" value="<?PHP echo set_value('password_confirm'); ?>" required>
										</label>
		     						</section>
									<section>
											<label class="label" for="password_confirm"><?php echo lang('create_counsellor_password_confirm_label', 'password_confirm');?></label>
											<label class="select"> <i class="icon-append fa fa-pencil"></i>
												<select name="class">
													<option value="0" selected="" disabled="">Select one</option>
													<?php foreach($specs as $index=>$spec):?>
													<option value="<?php echo $spec['class_name'];?>"><?php echo $spec['class_name'];?></option>
													<?php endForEach;?>
												</select>
											</label>
									</section>
                                    
									</fieldset>
									<footer>
										<button type="submit" class="btn bg-color-green txt-color-white">
											Submit
										</button>
										<button type="button" class="btn btn-default" onclick="window.history.back();">
											Back
										</button>
									</footer>
									<?php echo form_close();?>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
			</article>
        
        </div><!-- ROW -->
				

	</div>
	<!-- END MAIN CONTENT -->

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
<script type="text/javascript">
	runAllForms();

	$(function() {
		// Validation
		$("#create_counsellor").validate({
			// Rules for form validation
			rules : {
				first_name : {
					required : true,
					minlength : 3,
					maxlength : 25
				},
				last_name : {
					required : true,
					minlength : 3,
					maxlength : 25
				},
				email : {
					required : true,
					email    : true
				},
				phone : {
					required  : true,
					number    : true,
					minlength : 10
				},
				password : {
					required : true,
					minlength : 8,
					maxlength : 20
				},
				password_confirm : {
					required : true,
					equalTo: "#password",
					minlength : 8,
					maxlength : 20
				}
			},

			// Messages for form validation
			messages : {
				first_name : {
					required  : <?php echo lang('counsellor_first_name_required');?>,
					minlength : <?php echo lang('counsellor_first_name_min');?>,
					maxlength : <?php echo lang('counsellor_first_name_max');?>
		
				},
				last_name : {
					required  : <?php echo lang('counsellor_last_name_required');?>,
					minlength : <?php echo lang('counsellor_last_name_min');?>,
					maxlength : <?php echo lang('counsellor_last_name_max');?>
				},
				email : {
					required : <?php echo lang('counsellor_email_required');?>,
					email    : <?php echo lang('counsellor_email_valid');?>,
		
				},
				phone : {
					required : <?php echo lang('counsellor_phone_required');?>,
					number   : <?php echo lang('counsellor_phone_number');?>,
					minlength : <?php echo lang('counsellor_phone_min');?>,
				},
				password : {
					required  : <?php echo lang('counsellor_password_required');?>,
					minlength : <?php echo lang('counsellor_password_min');?>,
					maxlength : <?php echo lang('counsellor_password_max');?>
		
				},
				password_confirm : {
					required : <?php echo lang('counsellor_password_confirm_required');?>,
					minlength : <?php echo lang('counsellor_password_confirm_min');?>,
					maxlength : <?php echo lang('counsellor_password_confirm_max');?>,
					equalTo:   <?php echo lang('counsellor_password_confirm_match');?>
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
					title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
					content   : "<?php echo $message?>",
					color     : "#C46A69",
					iconSmall : "fa fa-bell bounce animated",
					
				});
	<?php } ?>
});
</script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>