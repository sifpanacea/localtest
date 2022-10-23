<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Change Password";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["changepassword"]["active"] = true;
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

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-6">
        <div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-lock"></i> </span>
							<h2><?php echo lang('change_password_heading');?></h2>
		
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
							echo  form_open('auth/change_password',$attributes);
							?>
		      					<!--<form class="smart-form">-->
									<!--<header>
										Please Enter The User Information.
									</header>-->
									<fieldset>
                                    <section>
                            			<label class="label" for="old_password"><?php echo lang('change_password_old_password_label', 'old_password');?></label>
                                			<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" name="old" id="old">
										</label>
		     						</section>

									<section>
                            			 <label class="label" for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label>
                                			<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" name="new" id="new" pattern="^.{8}.*$">
										</label>
		     						</section>
                                    <section>
                            			 <label class="label" for="new_confirm"><?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm');?></label>
                                			<label class="input"> <i class="icon-append fa fa-lock"></i>
											<input type="password" name="new_confirm" id="new_confirm" pattern="^.{8}.*$">
										</label>
                                        <!--<?php echo form_input($user_id);?>-->
		     						</section>
                                    </fieldset>
         							<footer>
										<input type="submit" class="btn bg-color-greenDark txt-color-white" name="submit" value="<?php echo lang('change_password_submit_btn');?>"/>
											
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