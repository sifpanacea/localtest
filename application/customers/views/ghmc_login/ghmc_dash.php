<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "GHMC Dashboard";

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

<!--daily start-->
	<div class="row">
<section id="widget-grid" class="">
<article class="col-sm-12 col-md-12 col-lg-4">

	<!-- new widget -->
	<div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="true">
	<header>
		<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
		<h2>Today ( <?php echo $today; ?> ) </h2>
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
		<table class="table table-striped table-hover table-condensed">
									
		<!--for loop-->
			
			
			<thead>
			<tr>
				<th>Total Trips</th>
				<th>Total Weight</th>
			</tr>
			</thead>
			<tbody class="daily_tab">
			<tr>
			<td><h1><?php echo $today_data['trips']; ?> <i class="fa fa-truck"></i></h1></td><td><h1><i class="fa fa-trash-o"></i> <?php echo $today_data['weight']; ?></h1></td>
			</tr>
		</tbody>
		
	</table>
</div>
<!-- end widget content -->

	</div>
	<!-- end widget div -->

</div></article></section>
<!--daily end-->

<!--weekly start-->
<section id="widget-grid-1" class="">
<article class="col-sm-12 col-md-12 col-lg-4">

	<!-- new widget -->
	<div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="true">
	<header>
		<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
		<h2>Weekly ( <?php echo $week; ?> ) </h2>
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
		<table class="table table-striped table-hover table-condensed">
		<!--for loop-->
			<thead>
			<tr>
				<th>Total Trips</th>
				<th>Total Weight</th>
			</tr>
			</thead>
			<tbody class="daily_tab">
			<tr>
			<td><h1><?php echo $week_data['trips']; ?> <i class="fa fa-truck"></i></h1></td><td><h1><i class="fa fa-trash-o"></i> <?php echo $week_data['weight']; ?></h1></td>
			</tr>
		</tbody>
	</table>
</div>
<!-- end widget content -->

	</div>
	<!-- end widget div -->

</div></article></section>
<!--weekly end-->

<!--monthly start-->
<section id="widget-grid-2" class="">
<article class="col-sm-12 col-md-12 col-lg-4">

	<!-- new widget -->
	<div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="true">
	<header>
		<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
		<h2>Monthly ( <?php echo $month; ?> ) </h2>
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
		<table class="table table-striped table-hover table-condensed">
									
		<!--for loop-->
			
			
			<thead>
			<tr>
				<th>Total Trips</th>
				<th>Total Weight</th>
			</tr>
			</thead>
			<tbody class="daily_tab">
			<tr>
			<td><h1><?php echo $month_data['trips']; ?> <i class="fa fa-truck"></i></h1></td><td><h1><i class="fa fa-trash-o"></i> <?php echo $month_data['weight']; ?></h1></td>
			</tr>
		</tbody>
		
	</table>
</div>
<!-- end widget content -->

	</div>
	<!-- end widget div -->

</div></article></section>
<!--monthly end-->
</div>
<div class="row">
<section id="widget-grid-3" class="">
<article class="col-sm-12 col-md-12 col-lg-12">

	<!-- new widget -->
	<div class="jarviswidget" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="true">
	<header>
		<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
		<h2>Trip by Date</h2>
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
							
							<div class="tree">
									<ul>
										<?php foreach ($trips_data['dates'] as $each_date):?>
										<li>
											<span><i class="fa fa-lg fa-calendar"></i> <?php echo $each_date;?></span>
											
													<ul>
														<?php foreach ($trips_data['times'][$each_date] as $each_time):?>
														<li>
															<span class="label label-success"><i class="fa fa-lg fa-plus-circle"></i> <?php echo $each_time;?></span> &ndash; <a href="ghmc_get_trip_details/<?php echo $trips_data['weight_details'][$each_date."_".$each_time][1];?>"><?php echo $trips_data['weight_details'][$each_date."_".$each_time][0];?></a>
														</li>
														<?php endforeach;?>
														
													</ul>
										</li>
										<?php endforeach;?>
									</ul>
								</div>
							
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div></article></section>
					<!-- end of search -->
					</div>

</div>
<!-- END MAIN PANEL -->
			

<!-- ==========================CONTENT ENDS HERE ========================== -->


<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="<?php echo ASSETS_URL; ?>/js/plugin/YOURJS.js"></script>-->

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
	
$('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
$('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function(e) {
	var children = $(this).parent('li.parent_li').find(' > ul > li');
	if (children.is(':visible')) {
		children.hide('fast');
		$(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
	} else {
		children.show('fast');
		$(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
	}
	e.stopPropagation();
});	

 });
 </script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>

