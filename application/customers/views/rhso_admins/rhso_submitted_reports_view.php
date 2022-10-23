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
													
														<section class="col col-6">
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
														<select id="school_name" disabled=true>
														<option value='select_school' >Select a district first</option>
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
										<a href="#s2" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i> Food and Hygiene Inspection report</a>
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
										
										<h5 id="sanitation_inspection"> Select Date and District </h5>
									</div>
									<div class="tab-pane fade" id="s2">
										<h5> Select Date and District </h5>
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

//var rhso_reports_span = "Daily";
var dt_name = $('#select_dt_name').val();
var school_name = $('#school_name').val();
//var rhso_reports_span = $('#rhso_reports_span').val();

$('#select_dt_name').change(function(e){
	dist = $('#select_dt_name').val();
	dt_name = $("#select_dt_name option:selected").text();
	
	var options = $("#school_name");
	options.prop("disabled", true);
	
	options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
	$.ajax({
		url: 'get_schools_list',
		type: 'POST',
		data: {"dist_id" : dist},
		success: function (data) {			

			result = $.parseJSON(data);
			console.log(result)

			options.prop("disabled", false);
			options.empty();
			options.append($("<option />").val("All").prop("selected", true).text("Select a School"));
			$.each(result, function() {
			    options.append($("<option />").val(this.school_name).text(this.school_name));
			});
					
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
});

$('#school_name').change(function(e){
	school_name = $("#school_name option:selected").text();
});

$('#school_name').change(function(e){
	
	//rhso_reports_span = $('#rhso_reports_span').val();
	dt_name = $("#select_dt_name option:selected").text();
	school_name = $("#school_name option:selected").text();
	
	$.ajax({
		url: 'rhso_submitted_reports_district_wise',
		type: 'POST',
		data: { "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
			data = data.trim();
           if(data != "NO_DATA_AVAILABLE")	
		   {			   
				result = $.parseJSON(data);
			  
				$('#sanitation_inspection_report').html('<div id="general_information" class="chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="water_source" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-5"></div><div id="toilets" class="chart col-xs-12 col-sm-6 col-md-6 col-lg-6"></div><div id="water_dispensaries_graph" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-6"></div><div id="children_seating_graph" class="chart chart col-xs-12 col-sm-6 col-md-6 col-lg-6"></div>');
				
				$('#sanitation_inspection').remove();
				
				  var general_information  = result.sanitation_inspection_data.general_information;
				  var water                = result.sanitation_inspection_data.water;
				  var toilets              = result.sanitation_inspection_data.toilets;
				
		
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

