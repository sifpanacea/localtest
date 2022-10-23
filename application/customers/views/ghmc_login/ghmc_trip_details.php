<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "GHMC Trip Details";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["home"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style>
.txt-color-bluee
{
color:#214e75;!important
}


</style>



<div id="main" role="main">
<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<div id="content">

<section id="widget-grid-2" class="">
<article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

	<!-- new widget -->
	<div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="true">
	<header>
		<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
		<h2>Trips by Details </h2>
	</header>

	<!-- widget div-->
	<div role="content">

		<!-- widget edit box -->
		<div class="jarviswidget-editbox">
			<!-- This area used as dropdown edit box -->

		</div>
		<!-- end widget edit box -->

		<!-- widget content -->
		<div class="widget-body no-padding">
		
		<!-- Strat of email -->
							
							
							<h2 class="email-open-header">Details of garbage trip on <?php echo $trip['time'];?>, <?php echo $trip['date'];?></h2>
							
							<div class="inbox-message">
	<p>
		Details of the garbage trip are as follows,
	</p>
	<p>
		Mobile number of truck driver (or) the number used to submit trip : <?php echo $trip['user_mobile'];?>
		<br>
		Weighbridge reading : <?php echo $trip['weighbridge'];?>
	</p>
	
	<br>
	<br>
</div>

<div class="inbox-download">
	Photographs taken
	
	<ul class="inbox-download-list">
		<?php $image_counter = 0?>
		<?php foreach ($trip['images'] as $each_image):?>
		<li>
			<div class="well well-sm">
				<span>
					<img src="<?php echo URLCustomer.$each_image['file_path'];?>">
				</span>
				<br>
				<p>
					Photo take at:
				</p>
				<strong><?php if ($image_counter == 0) {echo $trip['pic1_loc'];}else{echo $trip['pic2_loc'];}?></strong> 
				<br>
			</div>
		</li>
		<?php $image_counter++?>
		<?php endforeach;?>
	</ul>
</div>

<footer>
									
	<button type="button" class="btn btn-default" onclick="window.history.back();">
		Back
	</button>
</footer>
							
							
							<!-- End of email -->
		
</div>
<!-- end widget content -->

	</div>
	<!-- end widget div -->

</div></article></section>
					<!-- end of search -->

</div>
<!-- END MAIN PANEL -->
			

<!-- ==========================CONTENT ENDS HERE ========================== -->
<input type='hidden' id='queryapp' value='<?php echo set_value('queryapp', (isset($template->app_template)) ? json_encode($template->app_template) : ''); ?>' /><input type='hidden' id='queryid' value='<?php echo set_value('queryid', (isset($template->_id)) ? ($template->_id) : ''); ?>' /><input type='hidden' id='appname' value='<?php echo set_value('appname', (isset($template->app_name)) ? ($template->app_name) : ''); ?>' /><input type="hidden" id="get_pattern"/>
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<script>

$(document).ready(function() {
	//console.log("ready")
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

