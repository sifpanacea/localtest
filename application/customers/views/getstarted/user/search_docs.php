<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Search Documents";

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
									<h5>Searching Documents</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Search </span>
										<br>
										<br>
										This tab provides all apps assigned for you with description and time it was created
										<br>
										<br> To search documents for any application, click <a class="btn btn-xs btn-primary"><i class="fa fa-search"></i><span class="hidden-tablet"> Search Documents </span></a> button.<br><br>
										It will open the search page with several options<br><br>
										* Name-Value Search<br><br>
										* Date Search ( Between specified dates )<br><br>
										* All Documents<br><br>
										* Last Document<br><br>
										* Last 10 Documents<br><br>
										* Last Hour Documents<br><br>
										* Today Documents<br><br>
										<table class="table table-bordered">
										<tr>
											<td><B>
												Name-Value Search
											</B></td>
											<td> Searchable fields of this app will be listed here. User may enter the value and will search for matching results </td>
											</tr>
											<tr>
											<td><b>
												Date Search
											</b></td>
											<td> Documents submitted between the two dates specified will be listed </td>
											</tr>
											<tr>
											<td><b>
												All Documents
											</b></td>
											<td> All documents submitted will be listed </td>
											</tr>
											<tr>
											<td><b>
												Last Document
											</b></td>
											<td> The document which was submitted at last ( based on the time ) will be listed </td>
											</tr>
											<tr>
											<td><b>
												Last 10 Documents 
											</b></td>
											<td> The 10 documents which were submitted at last ( based on the time ) will be listed </td>
											</tr>
											<tr>
											<td><b>
												Last Hour Document 
											</b></td>
											<td> The documents which were submitted at the last hour ( based on the time ) will be listed </td>
											</tr>
											<tr>
											<td><b>
												Today Document
											</b></td>
											<td> The documents which were submitted at today ( based on the date ) will be listed </td>
											</tr>
											</table>
											<div class="note">
												<strong>Note:</strong> Search results will also show the externally attached files ( if any )
											</div>
										<!--<br><b> Name - Value Search </b><br>
										<br> Searchable fields of this app will be listed here. User may enter the value and will search for matching results <br>
										<br><b>Date Search </b><br>
										<br> Documents submitted between the two dates specified will be listed <br>
										<br><b> All Documents </b><br>
										<br> All documents submitted will be listed <br>
										<br><b> Last Document </b><br>
										<br> The document which was submitted at last ( based on the time ) will be opened <br>
										<br><b> Last Document </b><br>
										<br> The document which was submitted at last ( based on the time ) will be opened <br>
										<br><b> Last 10 Documents </b><br>
										<br> The 10 documents which were submitted at last ( based on the time ) will be listed <br>
										<br><b> Last Hour Document </b><br>
										<br> The documents which were submitted at the last hour ( based on the time ) will be listed <br>
										<br><b> Today Document </b><br>
										<br> The documents which were submitted at today ( based on the date ) will be listed --><br><br>
										<span class="fa fa-print"> Viewing and Printing Documents </span><br><br>
										Documents of any app retrieved can be able to view and print with predefined template ( if predefined template was assigned for that app ) <br><br>
										To view a document, click <a>view</a>. you can navigate to prev and next pages<br><br>
										To print a document, click <a>print</a>. you can navigate to prev and next pages. you can select/deselect any particular page to print using <span class="fa fa-print"></span> option.<br><br>
										<br><br>
										<div class="alert alert-info fade in">
						                <i class="fa-fw fa fa-info"></i>
						                <strong>Info !</strong> Documents retrieved are completed documents ( finished workflow ) only
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