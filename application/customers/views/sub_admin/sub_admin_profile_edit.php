<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Edit Profile";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["home"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
<?php if($message) { ?>	
<div class="alert alert-success alert-block">
						<a class="close" data-dismiss="alert" href="#">×</a>
						<h4 class="alert-heading">Message!</h4>
						<?php echo $message; ?>
					</div><?php } ?>
	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-6">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-user"></i> </span>
							<h2><?php echo lang('edit_profile');?> </h2>
		
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
						 	$attributes = array('class' => 'smart-form');
							echo  form_open_multipart('sub_admin/edit_profile',$attributes);
							?>
		      					<!--<form class="smart-form">-->
									<header>
										 Edit Your Profile Here.
									</header>
									<fieldset>
                                    <section>
                            			<label class="label" for="company"><?php echo lang('edit_profile_company_name_label', 'company');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($company);?>
										</label>
		     						</section>
									<section>
                            			<label class="label" for="company_address"><?php echo lang('edit_profile_address_label', 'company_address');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($company_address);?>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="company_website"><?php echo lang('edit_profile_website_label', 'company_website');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($company_website);?>
										</label>
		     						</section>
                                    
                                    <section>
                            			<label class="label" for="email"><?php echo lang('edit_profile_email_label', 'email');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($email);?>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="phone"><?php echo lang('edit_profile_mobile_label', 'phone');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($phone);?>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="username"><?php echo lang('edit_profile_username_label', 'username');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($username);?>
										</label>
		     						</section>
									
		     						
		     						<section>
		     							<label class="label" for="profile_image"><?php echo lang('edit_profile_image_label', 'profile_image');?></label>
		     							<div class="input input-file">
		     							<span class="button"><input type="file" id="file" name="file" onchange="this.parentNode.nextSibling.value = this.value">Browse</span><input type="text" placeholder="Include profile image" readonly="">
		     						</div>
		     						</section>
		     						
		     						
		     						<section>
		     							<label class="label" for="company_logo"><?php echo lang('edit_profile_logo_label', 'logo_image');?></label>
		     							<div class="input input-file">
		     							<span class="button"><input type="file" id="logo" name="logo" onchange="this.parentNode.nextSibling.value = this.value">Browse</span><input type="text" placeholder="Include logo image" readonly="">
										<div class="note">
												<strong>Note:</strong> Company logo must be of the dimensions 246*52 px
											</div>
		     						</div>
		     						</section>
		     						
									</fieldset>
									<footer>
										<button type="submit" class="btn bg-color-greenDark txt-color-white">
											Submit
										</button>
										<button type="button" class="btn btn-default" onclick="window.history.back();">
											Back
										</button>
									</footer>
								</form>
		
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
<script>
$(document).ready(function() {
<?php $message = $this->session->flashdata('message'); ?>
	<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "<?php echo $message?>",
				color : "#296191",
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