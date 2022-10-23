<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Dashboard";

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
									<h5>Dashboard</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Dashboard </span>
										<br><br>
										The admin dashboard provides key insights into your enterprise's activities as well as shortcuts to important and common admin actions
										<br>
										<br>
										<ul id="sparks" class="">
					<li>
						<h5>Subscription<span class="txt-color-blue"> Number of Days left</span></h5>
					</li>
					<li>
						<h5> Papers Saved <span class="txt-color-purple"><i class="fa fa-arrow-circle-up"></i> Number of papers saved </span></h5>
					</li>
					<li>
						<h5> Trees Saved <span class="txt-color-greenDark"><i class="glyphicon glyphicon-tree-deciduous"></i> Number of trees saved</span></h5>
					</li>
				</ul><br><i>NOTE : Approximately, 8333 pieces of paper equals a tree </i><br><br>
										<br> * Live Feeds </br>
										<br> * Analytics </br>
										<br><b> Live Feeds </b><br>
										<br><a rel="tooltip" data-placement="right" data-original-title="<i class='fa fa-check text-success'></i> Graph based on the number of documents submitted per second" data-html="true"># Document live flow</a><br>
										<br># Applications Limit ( as per the plan subscribed ) <br>
										<br># Document submissions limit ( as per the plan subscribed ) <br>
										<br># Third Party Subscription limit ( as per the plan subscribed ) <br>
										<br><a rel="tooltip" data-placement="right" data-original-title="<i class='fa fa-check text-success'></i> Number of documents completed a full cycle of workflow" data-html="true"># Finished Workflows</a><br>
										<br><a rel="tooltip" data-placement="right" data-original-title="<i class='fa fa-check text-success'></i> Number of documents are in progress" data-html="true"># Unfinished Workflows</a><br>
										<br># Apps usage ( Graphical representation ) <br>
										<br># Disk space <br>
										<br><a rel="tooltip" data-placement="right" data-original-title="<i class='fa fa-check text-success'></i> Analytics pattern saved for future reference" data-html="true"># Saved Patterns</a><br>
										<br><b> Analytics </b><br>
										<br>You can query any app with some your defined patterns and can view the results. Provide values and select patterns such as AND or OR. Click <a class="btn btn-default btn-sm" href="javascript:void(0);">Query</a>. Results will be displayed with some graphical data<br>
										<br><b> Save Pattern </b><br>
										<br>You can save any defined patterns and can view the results later. After quering,to save as pattern, click <button class="btn bg-color-greenDark txt-color-white" id="pattern">Save Query Pattern</button> button. Provide title and description. Click Submit ! That's it ! !<br>
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