<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Edit Admin";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["support admin management"]["sub"]["editadmin"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
	$breadcrumbs["Support Admin"] = "";
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
							<h2><?php echo lang('edit_support_admin_heading');?> </h2>
		
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
							echo  form_open(uri_string(),$attributes);
							?>
                           
		      					<!--<form class="smart-form">-->
									<header>
										<?php echo lang('edit_support_admin_subheading');?>
									</header>
									<fieldset>
                                    <section>
                            			<label class="label" for="first_name"><?php echo lang('edit_support_admin_first_name', 'first_name');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($first_name);?> 
										</label>
		     						</section>
									<section>
                            			<label class="label" for="last_name"><?php echo lang('edit_support_admin_last_name', 'last_name');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($last_name);?>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="company"><?php echo lang('edit_support_admin_company_name', 'company');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($company);?>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="email"><?php echo lang('edit_support_admin_email', 'email');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											 <?php echo form_input($email);?>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="phone"><?php echo lang('edit_support_admin_phone', 'phone');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($phone);?>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="password"><?php echo lang('edit_support_admin_password', 'password');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($password);?>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="password_confirm"><?php echo lang('edit_support_admin_confirm_password', 'password_confirm');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($password_confirm);?>
										</label>
									</section>
                                    <section>
                                    <label class="label"><?php echo lang('edit_support_admin_level');?></label>
									<?php 
									     $checkedone = null;
										 $checkedtwo = null;
										 if($user->level=='1')
										 {
										    $checkedone= 'checked="checked"';
										 }
										 else
										 {
										    $checkedtwo= 'checked="checked"';
										 }
									?>
								    <label class="radio">
								       <input type="radio" name="level[]" value="1" <?php echo $checkedone;?>><i></i>
								       Level 1
								    </label>
								    <label class="radio">
								       <input type="radio" name="level[]" value="2" <?php echo $checkedtwo;?>><i></i>
								       Level 2
								    </label>

      							    <?php echo form_hidden('id', $user->id);?>
      								<?php echo form_hidden($csrf); ?>
                                    </section>
									</fieldset>
									<footer>
										<button type="submit" class="btn bg-color-green txt-color-white">
											<?php echo lang('edit_support_admin_submit_btn');?>
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

	
<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>

</script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>