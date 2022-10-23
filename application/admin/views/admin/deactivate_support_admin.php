<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Activate (or) Deactivate Admin";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["support admin management"]["sub"]["adminstatus"]["active"] = true;
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

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-6">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-user"></i> </span>
							<h2><?php echo lang('support_deactivate_heading');?></h2>
		
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
							echo form_open("auth/deactivate_support_admin/".$support_admin->_id,$attributes);?>
		      					<!--<form class="smart-form">-->
									<header>
										<?php echo sprintf(lang('support_deactivate_subheading'), $support_admin->username);?>
									</header>
									<fieldset>
                                    <section class="col col-5">
                            		  	<label class="toggle">
											<input type="radio"  name="confirm" value="yes" checked="checked" <?PHP echo set_radio('yes','1',TRUE); ?>>
													<i data-swchon-text="ON" data-swchoff-text="OFF"></i>Yes</label>
											<label class="toggle">
													<input type="radio" value="no" name="confirm"<?PHP echo set_radio('no','1'); ?>>
													<i data-swchon-text="ON" data-swchoff-text="OFF"></i>No</label>
									</section>
                                    </fieldset>
									<footer>
										<input type="submit" class="btn bg-color-yellow txt-color-white" name="submit" value="Deactivate"/>
											
										<button type="button" class="btn btn-default" onclick="window.history.back();">
											Back
										</button>
									</footer>
                                     <?php echo form_hidden($csrf); ?>
  									 <?php echo form_hidden(array('id'=>$support_admin->_id)); ?>
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