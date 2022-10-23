<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "workflow";

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
									<h5>Defining Workflow</h5>
									
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
													<li>
														<span class="step">2</span> <span class="title">Buiding app</span> 
													</li>
													<li class="active">
														 <span class="step">3</span> <span class="title">Defining Workflow</span> 
													</li>
													<li>
														<span class="step">4</span> <span class="title">Sending notifications</span> 
													</li>
												</ul>
												<div class="clearfix"></div>
										</div>
										<br><br><br>
										Here you can define stage and branching stuffs.
										
										You can create four types of workflow stages. <br><br>
										   * Single Stage <br>
										   * Parallel Branching <br> 
										   * Conditional Branching <br>
										   * API <br><br>
										  
										   By default, single stage is the first stage. you can specify properties like stage name, users,stage type, view permissions & Edit permissions in the settings popover <br><br>
										<table class="table table-bordered">
										<th> Single Stage </th>
										<tr>
											<td><B>
												Stage name
											</B></td>
											<td>A valid name</td>
											</tr>
											<tr>
											<td><b>
												Adding users
											</b></td>
											<td> you can add a entire group or selected users form many groups </td>
											</tr>
											<tr>
											<td><b>
												Stage type
											</b></td>
											<td> Three stage types available. Web, Device and API. You can choose any one.<br> *<b> Web</b> - For web user <br> * <b>Device</b> - For device user <br> * <b>API</b> - For third party processing <br> </td>
											</tr>
											<tr>
											<td><b>
												View permissions
											</b></td>
											<td> you can choose sections of your app that this stage user can view </td>
											</tr>
											<tr>
											<td><b>
												Edit permissions
											</b></td>
											<td> you can choose sections of your app that this stage user can edit </td>
											</tr>
											</table>
											<br>
											<table class="table table-bordered">
										<th> Parallel Branching </th>
										<tr>
											<td><B>
												Branches
											</B></td>
											<td>By default,consists of two branches. Maximum 4 branches can be added including default branches. Inside branches single stages can be added</td>
											</tr>
											<tr>
											<td><b>
												Delete button
											</b></td>
											<td> you can delete parallel branching or branches added </td>
											</tr>
											</table>
											<br>
											<table class="table table-bordered">
										<th> Conditional Branching </th>
										    <tr>
											<td><B>
												Branches
											</B></td>
											<td>Approved and Disapproved</td>
											</tr>
											<tr>
											<td><B>
												Settings
											</B></td>
											<td>Condition for validation can be selected here</td>
											</tr>
											<tr>
											<td><b>
												Branching
											</b></td>
											<td> Any number of single stage can be added inside both approved and disapproved branches</td>
											</tr>
											<tr>
											<td><b>
												Delete button
											</b></td>
											<td> you can delete conditional branching using this option <button type="button" id="hidbtn3" alt="Delete" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></td>
											</tr>
											</table>
											<br>
											<table class="table table-bordered">
										<th> API </th>
										<tr>
											<td><B>
												Select Company
											</B></td>
											<td> Available third party companies will be listed here. you can select any of the companies </td>
											</tr>
											<tr>
											<td><b>
												Select Users
											</b></td>
											<td> you can select users available based on the company you selected</td>
											</tr>
											<tr>
											<td><b>
												View permissions
											</b></td>
											<td> you can choose sections of your app that this stage user can view </td>
											</tr>
											</table>
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