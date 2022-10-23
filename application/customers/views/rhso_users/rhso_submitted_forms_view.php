<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Panacea Dashboard";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa home"]["active"] = true;
include("inc/nav.php");

?>

<link href="<?php echo(CSS.'jquery.fullPage.css'); ?>" media="screen" rel="stylesheet" type="text/css" /><!--
<link href="<?php echo(CSS.'fullPage_styles.css'); ?>" media="screen" rel="stylesheet" type="text/css" /> 
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<link href="<?php echo(CSS.'site.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
-->

<style>



.dataTables_filter .input-group-addon {
    width: 32px;
    margin-top: 0;
    float: left;
    height: 32px;
    padding-top: 8px;
}
.input-group-addon{
	margin-left: -12px;
}
div.dataTables_info 
{
    padding-top: 9px;
    font-size: 13px;
    font-weight: 700;
    font-style: italic;
    color: #969696;
}
#datatable_fixed_column_paginate
{
	float: right;
}
.chart {
    height: 170px;
}
</style>
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
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
		<div id="fullpage">
			<div class="vertical-scrolling">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<!-- new widget -->
							<div class="jarviswidget" id="wid-id-1000" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
							<!-- widget options:
							usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

							data-widget-colorbutton="false"
							data-widget-editbutton="false"
							data-widget-togglebutton="false"
							data-widget-deletebutton="false"
							data-widget-fullscreenbutton="false"
							data-widget-custombutton="false"
							data-widget-collapsed="true"
							data-widget-sortable="false"

							-->
							<header>
								<span class="widget-icon"> <i class="glyphicon glyphicon-calendar txt-color-darken"></i> </span>
								<h2>Set Date For PIE </h2>

							</header>

							<!-- widget div-->
								<div class="no-padding">
								<!-- widget edit box -->
								<!-- end widget edit box -->

									<div class="widget-body">
									<!-- content -->
										<div id="myTabContent" class="tab-content">
											<div class="well well-sm well-light">
												<form class='smart-form'>
													<fieldset>
														<section class="col col-3 hide">
															<label class="label">Search Span</label>
															<label class="select">
																<select id="rhso_reports_span">
																	<option value="Daily">Daily</option>
																	<option value="Monthly">Monthly</option>
																	<option value="Bi Monthly">Bi Monthly</option>
																	<option value="Quarterly">Quarterly</option>
																	<option value="Half Yearly">Half Yearly</option>
																	<option selected value="Yearly">Yearly</option>
																</select> <i></i> 
															</label>
														</section>
													
														<section class="col col-6 hide">
															<label class="label" for="first_name">District Name</label>
															<label class="select">
															<select id="select_dt_name" >
																<option value='All' >Select a District</option>
																<?php if(isset($distslist)): ?>
																	<?php foreach ($distslist as $dist):?>
																	<option value='<?php echo $dist['_id']?>'><?php echo ucfirst($dist['dt_name'])?></option>
																	<?php endforeach;?>
																	<?php else: ?>
																	<option value="1"  disabled="">No district entered yet</option>
																<?php endif ?>
															</select> <i></i>
														</label>
														</section>
														<section class="col col-6">
														<label class="label" for="school_name">School Name</label>
														<label class="select">
														<select id="school_name">
														<option value='All' >All</option>
																<?php if(isset($schools_list)): ?>
																	<?php foreach ($schools_list as $school):?>
																	<option value='<?php echo $school['_id']?>'><?php echo ucfirst($school['school_name'])?></option>
																	<?php endforeach;?>
																	<?php else: ?>
																	
																<?php endif ?>
														</select> <i></i>
													</label>
													</section>
													
														<section class="col col-4 hide">
															<br>
															<button type="button" class="btn bg-color-pink txt-color-white btn-sm" id="set_date_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false" style="margin-top: 7px;">Set</button>
														</section>
													</fieldset>
												</form>			
											</div>
									<!-- end content -->
									</div>
							<!-- end widget div -->
								</div>
						<!-- end widget -->
								</div>
							</div>
						</article>
					
					</div>
				</div>
			</div>
			
			<div class="vertical-scrolling">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<!-- new widget -->
							<div class="jarviswidget" id="wid-id-1000" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
							<!-- widget options:
							usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

							data-widget-colorbutton="false"
							data-widget-editbutton="false"
							data-widget-togglebutton="false"
							data-widget-deletebutton="false"
							data-widget-fullscreenbutton="false"
							data-widget-custombutton="false"
							data-widget-collapsed="true"
							data-widget-sortable="false"

							-->
							<header>
								<span class="widget-icon"> <i class="glyphicon glyphicon-calendar txt-color-darken"></i> </span>
								<h2>Reports </h2>

							</header>

							<!-- widget div-->
								<div class="no-padding">
								<!-- widget edit box -->
								<!-- end widget edit box -->
									<!-- widget content -->
							<div class="widget-body">
		
								<p>
									<h4>Select Tabs to view the Report</h4>
								</p>
								<hr class="simple">
								<ul id="myTab1" class="nav nav-tabs bordered">
									<li class="active">
										<a href="#sanitation_inspection_report" data-toggle="tab">Sanitation Inspection Report </a>
									</li>
									<li>
										<a href="#food_and_hygiene_report" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i> Food and Hygiene Inspection report</a>
									</li>
									<li>
										<a href="#s3" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i> Checklist for Inspectors</a>
									</li>
									<li>
										<a href="#s4" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i> Civil and Infrastructure Inspection Report</a>
									</li>
								</ul>
		
								<div id="myTabContent1" class="tab-content padding-10">
									<div class="tab-pane fade in active" id="sanitation_inspection_report" >
										
										<h5 id="sanitation_inspection"> Select School </h5>
									</div>
									<div class="tab-pane fade" id="food_and_hygiene_report">
										<h5 id="food_and_hygiene"> Select School </h5>
									</div>
									<div class="tab-pane fade" id="s3">
										<h5> Select Date and District </h5>
									</div>
									<div class="tab-pane fade" id="s4">
										<h5> Select Date and District </h5>
									</div>
								</div>
		
							</div>
							<!-- end widget content -->
								
								</div>
							</div>
						</article>
					
					</div>
				</div>
			</div>
				
					<!-- SANITATION REPORT SUBMITTED SCHOOLS LIST -->
			<div class="modal fade-in" id="sani_repo_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							×
						</button>
						<h4 class="modal-title" id="myModalLabel">Sanitation Inspection </h4>
					</div>
					
					
					<div id="sani_repo_sent_school_modal_body" class="modal-body">
		            
					
					</div>
					<div class="modal-footer">
					  <!-- <button type="button" class="btn btn-primary" id="sani_repo_sent_school_download">
							Download
						</button> -->
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
			
		
		
		</div>
	<!-- End MAIN CONTENT -->
	</div>
	
	<!-- MAIN PANEL -->
</div>



		



<!-- ==========================CONTENT ENDS HERE ========================== -->
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->

<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.barnumbers.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
<script src="<?php echo JS; ?>plugin/morris/raphael.min.js"></script>
<script src="<?php echo JS; ?>plugin/morris/morris.min.js"></script>
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<script src="<?php echo JS; ?>jquery.bootstrap.newsbox.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>
<!--
<script src="<?php echo JS; ?>jquery.fullPage.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS; ?>fullPage_index.js" type="text/javascript" charset="utf-8"></script>
-->

<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {

var school_name = $('#school_name').val();


	<?php if($message) { ?>
	$.smallBox({
					title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
					content : "<?php echo $message?>",
					color : "#296191",
					iconSmall : "fa fa-bell bounce animated",
					timeout : 8000
				});
	<?php } ?>

$('#school_name').change(function(e){
	
	school_name = $("#school_name option:selected").text();
	
	$.ajax({
		url: 'rhso_submitted_reports_school_wise',
		type: 'POST',
		data: { "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
			data = data.trim();
			
           if(data != "NO_DATA_AVAILABLE")	
		   {			   
				result = $.parseJSON(data);
				
				$('#sanitation_inspection_report').html('<div id="general_information" class="chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="water_source" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="toilets" class="chart col-xs-12 col-sm-6 col-md-6 col-lg-6"></div><div id="water_dispensaries_graph" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-6"></div><div id="children_seating_graph" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-6"></div><div id="external_files" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-6"></div><div id="links" name="links"></div>');
				
				$('#sanitation_inspection').remove();
				
				  var general_information  = result.sanitation_inspection_data.general_information;
				  var water                = result.sanitation_inspection_data.water;
				  var toilets              = result.sanitation_inspection_data.toilets;
				  var external_files       = result.sanitation_inspection_data.external_attachments;
				 /*  var links       = result.sanitation_inspection_data.links;
				  console.log('linkssssssss',links);
		 */
			// toilets
			if ($("#general_information").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in general_information) {
				  if (general_information.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+general_information[item].label+'</td><td>'+general_information[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#general_information").html(table)
				
			}
			
			$('#general_information').prepend('<div class=""><strong>General Information</strong></div>');
			
			// water source
			if ($("#water_source").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in water) {
				  if (water.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+water[item].label+'</td><td>'+water[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#water_source").html(table)
				
			}
			
			$('#water_source').prepend('<div class=""><strong>Water Source</strong></div>');
			
			// Toilets
			if ($("#toilets").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in toilets) {
				  if (toilets.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+toilets[item].label+'</td><td>'+toilets[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#toilets").html(table)
				
			}
			
			$('#toilets').prepend('<div class=""><strong>Toilets</strong></div>');
			
				
			var table = '<div style="overflow-y: auto; height:200px;" ><table class=" table table-bordered"><thead><tr><th>Attachments <span class="attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
			var length = Object.keys(external_files).length;
			if(length > 0)
			{
			for(var item in external_files)
			{
			  table = table + '<tr><td><a href="<?php echo URLCustomer;?>'+external_files[item].file_path+'" rel="prettyPhoto[gal]">'+external_files[item].file_client_name+'</a></td></tr>'
			  
			}
			}
			else
			{
			  table = table + '<tr><td>No attachments </td></tr>'
			}
			
			$("#external_files").html(table)
			$('.attach_count').text(length);
		
			$("a[rel^='prettyPhoto']").prettyPhoto();
			
			//$('#links').html(links);
			
			$('#food_and_hygiene_report').html('<div id="food_preparation_area_kitchen" class="chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="cooking_mode" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="storage_vegetables_cutting_area" class="chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="personal_hygiene_foodhandlers" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="condition_of_cooking_containers" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="store_room" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="qualtity_of_raw_material_preparation_food" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="samples_collected" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="provide_eggs" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="milk_and_curd" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="banana_or_fruits" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="cooked_prepared_food_articles" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="drinking_water_facility" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="dining_hall_facility" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="handwash_facilty_dining" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="any_other_issues" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div>');
				
				$('#food_and_hygiene').remove();
				
			var food_preparation_area_kitchen  = result.food_and_hygiene_data.food_preparation_area_kitchen;
			var cooking_mode  = result.food_and_hygiene_data.cooking_mode;
			var storage_vegetables_cutting_area  = result.food_and_hygiene_data.storage_vegetables_cutting_area;
			var personal_hygiene_foodhandlers  = result.food_and_hygiene_data.personal_hygiene_foodhandlers;
			var condition_of_cooking_containers  = result.food_and_hygiene_data.condition_of_cooking_containers;
			var store_room  = result.food_and_hygiene_data.store_room;
			var qualtity_of_raw_material_preparation_food  = result.food_and_hygiene_data.qualtity_of_raw_material_preparation_food;
			var samples_collected  = result.food_and_hygiene_data.samples_collected;
			var provide_eggs  = result.food_and_hygiene_data.provide_eggs;
			var milk_and_curd  = result.food_and_hygiene_data.milk_and_curd;
			var banana_or_fruits  = result.food_and_hygiene_data.banana_or_fruits;
			var cooked_prepared_food_articles  = result.food_and_hygiene_data.cooked_prepared_food_articles;
			var drinking_water_facility  = result.food_and_hygiene_data.drinking_water_facility;
			var dining_hall_facility  = result.food_and_hygiene_data.dining_hall_facility;
			var handwash_facilty_dining  = result.food_and_hygiene_data.handwash_facilty_dining;
			var any_other_issues  = result.food_and_hygiene_data.any_other_issues;
			
			
			
			
			if ($("#food_preparation_area_kitchen").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in food_preparation_area_kitchen) {
					
				  if (food_preparation_area_kitchen.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+food_preparation_area_kitchen[item].label+'</td><td>'+food_preparation_area_kitchen[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#food_preparation_area_kitchen").html(table)
				
			}
			
			$('#food_preparation_area_kitchen').prepend('<center><strong>Food Preparaton Area or Kitchen</strong></center>');
						
			if ($("#cooking_mode").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in cooking_mode) {
					
				  if (cooking_mode.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+cooking_mode[item].label+'</td><td>'+cooking_mode[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#cooking_mode").html(table)
				
			}
			
			$('#cooking_mode').prepend('<center><strong>Cooking Mode</strong></center>');
			
			if ($("#storage_vegetables_cutting_area").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in storage_vegetables_cutting_area) {
					
				  if (storage_vegetables_cutting_area.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+storage_vegetables_cutting_area[item].label+'</td><td>'+storage_vegetables_cutting_area[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#storage_vegetables_cutting_area").html(table)
				
			}
			
			$('#storage_vegetables_cutting_area').prepend('<center><strong>Storage of Vegetables and Cutting area</strong></center>');
			
				if ($("#personal_hygiene_foodhandlers").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in personal_hygiene_foodhandlers) {
					
				  if (personal_hygiene_foodhandlers.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+personal_hygiene_foodhandlers[item].label+'</td><td>'+personal_hygiene_foodhandlers[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#personal_hygiene_foodhandlers").html(table)
				
			}
			
			$('#personal_hygiene_foodhandlers').prepend('<center><strong>Personal Hygiene of Food Handlers</strong></center>');
			
			
			if ($("#condition_of_cooking_containers").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in condition_of_cooking_containers) {
					
				  if (condition_of_cooking_containers.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+condition_of_cooking_containers[item].label+'</td><td>'+condition_of_cooking_containers[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#condition_of_cooking_containers").html(table)
				
			}
			
			$('#condition_of_cooking_containers').prepend('<center><strong>Condition of Cooking Containers</strong></center>');
			
			if ($("#store_room").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in store_room) {
					
				  if (store_room.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+store_room[item].label+'</td><td>'+store_room[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#store_room").html(table)
				
			}
			
			$('#store_room').prepend('<center><strong>Store Room</strong></center>');
			
				if ($("#qualtity_of_raw_material_preparation_food").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in qualtity_of_raw_material_preparation_food) {
					
				  if (qualtity_of_raw_material_preparation_food.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+qualtity_of_raw_material_preparation_food[item].label+'</td><td>'+qualtity_of_raw_material_preparation_food[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#qualtity_of_raw_material_preparation_food").html(table)
				
			}
			
			$('#qualtity_of_raw_material_preparation_food').prepend('<center><strong>Quality of raw material for preperation of food</strong></center>');
			
			
			if ($("#samples_collected").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in samples_collected) {
					
				  if (samples_collected.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+samples_collected[item].label+'</td><td>'+samples_collected[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#samples_collected").html(table)
				
			}
			
			
			$('#samples_collected').prepend('<center><strong>Samples Collected</strong></center>');
			
			if ($("#provide_eggs").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in provide_eggs) {
					
				  if (provide_eggs.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+provide_eggs[item].label+'</td><td>'+provide_eggs[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#provide_eggs").html(table)
				
			}
			
			$('#provide_eggs').prepend('<center><strong>Eggs</strong></center>');
			
			if ($("#milk_and_curd").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in milk_and_curd) {
					
				  if (milk_and_curd.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+milk_and_curd[item].label+'</td><td>'+milk_and_curd[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#milk_and_curd").html(table)
				
			}
			
			$('#milk_and_curd').prepend('<center><strong>Milk and Curd</strong></center>');
			
			if ($("#banana_or_fruits").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in banana_or_fruits) {
					
				  if (banana_or_fruits.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+banana_or_fruits[item].label+'</td><td>'+banana_or_fruits[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#banana_or_fruits").html(table)
				
			}
			
			$('#banana_or_fruits').prepend('<center><strong>Banana or Fruits</strong></center>');
		
			
			if ($("#cooked_prepared_food_articles").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in cooked_prepared_food_articles) {
					
				  if (cooked_prepared_food_articles.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+cooked_prepared_food_articles[item].label+'</td><td>'+cooked_prepared_food_articles[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#cooked_prepared_food_articles").html(table)
				
			}
			
			$('#cooked_prepared_food_articles').prepend('<center><strong>Cooked prepared food articles</strong></center>');
			
			if ($("#drinking_water_facility").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in drinking_water_facility) {
					
				  if (drinking_water_facility.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+drinking_water_facility[item].label+'</td><td>'+drinking_water_facility[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#drinking_water_facility").html(table)
				
			}
			
			$('#drinking_water_facility').prepend('<center><strong>Drinking Water</strong></center>');
			
			
			
			if ($("#dining_hall_facility").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in dining_hall_facility) {
					
				  if (dining_hall_facility.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+dining_hall_facility[item].label+'</td><td>'+dining_hall_facility[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#dining_hall_facility").html(table)
				
			}
			
			$('#dining_hall_facility').prepend('<center><strong>Dining Hall</strong></center>');
			
			
			if ($("#handwash_facilty_dining").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in handwash_facilty_dining) {
					
				  if (handwash_facilty_dining.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+handwash_facilty_dining[item].label+'</td><td>'+handwash_facilty_dining[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#handwash_facilty_dining").html(table)
				
			}
			
			$('#handwash_facilty_dining').prepend('<center><strong>Hand washing facility in dining area</strong></center>');
			
			if ($("#any_other_issues").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"> <thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in any_other_issues) {
					
				  if (any_other_issues.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+any_other_issues[item].label+'</td><td>'+any_other_issues[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#any_other_issues").html(table)
				
			}
			
			$('#any_other_issues').prepend('<center><strong>Any other Issues/Faults</strong></center>');
			
			}
			
		  else
		   {
			   $('#sanitation_inspection').remove();
			   $('#sanitation_inspection_report').html('<center><label id="sanitation_inspection">No sanitation inspection data available</label></center>');
		   }
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
			console.log('error', errorThrown);
		}
		});
	
	});

});


		
		

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

