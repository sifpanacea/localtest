<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "App Design";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Getstarted"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Getstarted"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT --><div id="content">
	<div class="well well-sm bg-color-darken txt-color-white text-center">
									<h5>Buiding app with elements</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Application Design </span>
										<br>
										<br>
										<div class="form-bootstrapWizard">
												<ul class="bootstrapWizard form-wizard">
													<li>
														 <span class="step">1</span> <span class="title">Setting app properties</span>
													</li>
													<li class="active">
														<span class="step">2</span> <span class="title">Buiding app</span> 
													</li>
													<li>
														 <span class="step">3</span> <span class="title">Defining workflow</span> 
													</li>
													<li>
														<span class="step">4</span> <span class="title">Sending notifications</span> 
													</li>
												</ul>
												<div class="clearfix"></div>
										</div>
										<br><br><br>
										<table class="table table-bordered">
										<tr>
											<td><B>
												Add Element
											</B></td>
											<td> This option let you to add elements to your app. By clicking this you will be provided with two kind of elements. <center><br><b>Typable and Writable</b></center><br> you can choose the element you want. For example, you could click “Single Line Text in Typable part” and this will add a single line text field to your form. Each field that you add is placed directly below the previous field. <br><br>See our <a href="<?php echo URL.'help/field_types'?>">list of field types</a> for more information</td>
											</tr>
											<tr>
											<td><b>
												Previous
											</b></td>
											<td> This option let you to navigate to the previous page of app you are creating</td>
											</tr>
											<tr>
											<td><b>
												Delete
											</b></td>
											<td>This option let you to delete the current page</td>
											</tr>
											<tr>
											<td><b>
												Next
											</b></td>
											<td> This option let you to navigate to the next pages of the app you are creating</td>
											</tr>
											<tr>
											<td><b>
												Print Template
											</b></td>
											<td> This option let you to add template to your app. Any template* can be added to any page</td>
											</tr>
											<tr>
											<td><b>
												Notify Field
											</b></td>
											<td> This option let you to assign any typable field to be used for notification during execution of workflow</td>
											</tr>
											</table>
											<br>
				                            <div class="alert alert-info fade in">
						                <i class="fa-fw fa fa-info"></i>
						                <strong>Info !</strong> * Templates uploaded in the predefined templates can be used here
					                    </div>
										<br>
				                            <div class="alert alert-warning fade in">
						                <i class="fa-fw fa fa-warning"></i>
						                <strong>Warning !</strong> First section must contain a notify field
					                    </div>
										<br>
										<div class="alert alert-warning fade in">
						                <i class="fa-fw fa fa-warning"></i>
						                <strong>Warning !</strong> First section cannot be re-positioned. For any form, first element is a section break element by default
					                    </div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-right" onclick="window.history.back();">
											Back
										</button>
										</div>
									</div><!-- /.modal-content -->
								</div>
	<div class="well well-sm bg-color-darken txt-color-white text-center">
	
									
								</div>
	</div><!--END MAIN CONTENT-->

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