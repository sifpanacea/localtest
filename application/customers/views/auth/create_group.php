<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Create Group";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["group management"]["sub"]["creategroup"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Group Management"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-6">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-group"></i> </span>
							<h2><?php echo lang('create_group_heading');?></h2>
		
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
							echo  form_open('auth/create_group',$attributes);
							?>
		      					<!--<form class="smart-form">-->
									<!--<header>
										Please Enter The User Information.
									</header>-->
									<fieldset>
                                    <section>
                            			<label class="label" for="group_name"><?php echo lang('create_group_name_label', 'group_name');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="group_name" id="group_name" value="<?php echo set_value('group_name');?>" >
										</label>
		     						</section>

									<section>
                            			<label class="label" for="description"><?php echo lang('create_group_desc_label', 'description');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="text" name="description" id="description"  value="<?php echo set_value('description');?>" >
										</label>
		     						</section>
                                    </fieldset>
									<footer>
										<input type="submit" class="btn bg-color-green txt-color-white" name="submit" value="<?php echo lang('create_group_submit_btn');?>"/>
											
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
	<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
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