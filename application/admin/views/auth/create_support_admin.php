<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Create Admin";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["support admin management"]["sub"]["createadmin"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Support Admin"] = " ";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-6">
        <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-user"></i> </span>
							<h2><?php echo lang('create_admin_heading');?> </h2>
		
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
							echo  form_open('admin_dash/create_support_admin',$attributes);
							?>
		      					<!--<form class="smart-form">-->
									<header>
										<?php echo lang('create_support_admin_subheading');?>
									</header>
									<fieldset>
                                    <section>
                            			<label class="label" for="first_name"><?php echo lang('create_support_admin_first_name', 'first_name');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="first_name" id="first_name">
										</label>
		     						</section>
									<section>
                            			<label class="label" for="last_name"><?php echo lang('create_support_admin_last_name', 'last_name');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="last_name" id="last_name">
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="company"><?php echo lang('create_support_admin_company_name', 'company');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="company" id="company">
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="email"><?php echo lang('create_support_admin_email', 'email');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="email" name="email" id="email">
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="phone"><?php echo lang('create_support_admin_phone', 'phone');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="phone" id="phone">
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="password"><?php echo lang('create_support_admin_password', 'password');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="password" name="password" id="password">
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="password_confirm"><?php echo lang('create_support_admin_confirm_password', 'password_confirm');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="password" name="password_confirm" id="password_confirm">
										</label>
		     						</section>
									<section>
											<label class="label"><?php echo lang('create_support_admin_level','level');?></label></label>
											<div class="row">
												<div class="col col-4">
													<label class="radio">
														<input type="radio" name="level[]" value="1">
														<i></i>Level 1</label>
													<label class="radio">
														<input type="radio" name="level[]" value="2">
														<i></i>Level 2</label>
												</div>
											</div>
										</section>	
										
                                   	</fieldset>
									<footer>
										<button type="submit" class="btn bg-color-greenDark txt-color-white">
											<?php echo lang('create_support_admin_submit_btn');?>
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



<?php 
	//include footer
	include("inc/footer.php"); 
?>