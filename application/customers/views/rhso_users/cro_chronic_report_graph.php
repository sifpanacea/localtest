<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Chronic Report Graph";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["chronic_report_graph"]["active"] = true;
include("inc/nav.php");

?>
<style>
#flot-tooltip { font-size: 12px; font-family: Verdana, Arial, sans-serif; position: absolute; display: none; border: 2px solid; padding: 2px; background-color: #FFF; opacity: 0.8; -moz-border-radius: 5px; -webkit-border-radius: 5px; -khtml-border-radius: 5px; border-radius: 5px; }
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<link href="<?php echo(CSS.'site.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
	
		
		
		<div class="row">
		<div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 hidden">
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
											<div class="row">
											<section class="col col-12">
											<div class="form-group">
												<div class="input-group">
												<input type="text" id="set_data" name="set_data" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $today_date?>">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
											</section>
					                    
										</div>
												<div class="row">
													<section class="col col-6">
														<label class="label" for="first_name">District Name</label>
														<label class="select">
														<select id="select_dt_name" >
															<option value="All">All</option>
															<?php if(isset($distslist)): ?>
																<?php foreach ($distslist as $dist):?>
																<option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
																<?php endforeach;?>
																<?php else: ?>
																<option value="1"  disabled="">No district entered yet</option>
															<?php endif ?>
														</select> <i></i>
													</label>
													</section>
													<section class="col col-6">
														<label class="label" for="first_name">School Name</label>
														<label class="select">
														<select id="school_name" disabled=true>
															<option value='All' >All</option>
															
															
														</select> <i></i>
													</label>
													</section>
													<section class="col col-6">
													<button type="button" class="btn bg-color-pink txt-color-white btn-sm" id="set_date_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
						                       Set
						                    </button>
						                    </section>
													</div>
													
													
													</fieldset>
													
										</form>			
													
							</div>
								<!-- end content -->

						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
                 </div>
				</article>
					
				</div>
				
			<!-- row -->
			<!-- end row -->
			</div>
		

			
			<!-- CHRONIC REPORT LINE GRAPH -->
			<!-- widget grid -->
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-sm-12">
					<!-- new widget -->
					<div class="jarviswidget" id="wid-id-100" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						
						<header>
							<span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
							<h2>Chronic Report Line Graph </h2>

						</header>

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
							<div class="row">
								<br>
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									<div class="well well-sm well-light">
										<form class="smart-form" >
										<fieldset style="padding-top: 0px; padding-bottom: 0px;">
										<section class="col col-3">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Select School</label></div>
												<div class="input-group">
											<select id="chronic_id_school_list" class="form-control">
														<option value='select_school' >-- Select --</option>
														</select> <i></i>
												<span class="input-group-addon"><i class="fa fa-building"></i></span>
												</div>
										</div>
										</section>
										<section class="col col-3">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Select Student</label></div>
												<div class="input-group">
											<select id="chronic_id_list" class="form-control">
														<option value='select_id' >-- Select --</option>
														</select> <i></i>
												<span class="input-group-addon"><i class="fa fa-user"></i></span>
												</div>
										</div>
										</section>
										<section class="col col-2 hide timeline_month">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Select Follow-Up Month</label></div>
												<div class="input-group">
											<select id="chronic_select_month" class="form-control">
														<option value='select_month' >-- Select --</option>
														</select> <i></i>
												<span class="input-group-addon"><i class="fa  fa-caret-down"></i></span>
												</div>
										</div>
										</section>
										<section class="col col-4">
										<div class="input-group">
										<label class="label" for="item1">Note:- select a student (hospital unique id) and proceed</label>
													<button type="button" class="btn bg-color-orange txt-color-white btn-sm" id="view_pill_compliance" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
						                       View Pill Compliance
						                    </button>
											</div>
						                    </section>
										</fieldset>
										</form>
									</div>
									<div>
									<br>
									<div id="chronic_line_graph" style="min-height:300px;max-height:300px;">
									<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									
									
									</div>
									
									</div>
								 <div id="compliance_legend" style="padding-left:10px;padding-top:10px;"></div>
									</div>
								</div>
								
								</div>
		             </div>
						<!-- end widget div -->
					<!--</div>
					 end widget -->
                 </div>
				</article>
				
			<!-- row -->

			<!-- end row  -->
				</div>

				<div class="row">
				
				<div class="col-xs-12 col-sm-6 col-md-10 col-lg-10">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<!-- new widget -->
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-60" data-widget-editbutton="false">
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
							<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
							<h2>Request PIE</h2>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->

							<!-- widget content -->
							<div class="col-md-12" id="loading_request_pie" style="display:none;">
									<center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
								</div>
								
								<div id="request_pies">
								
								<div class="row">
								
								
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									<div class="well well-sm well-light">
										<form class='smart-form'>
										<fieldset style="padding-top: 0px; padding-bottom: 0px;">
										<div class="row">
											<section class="col col-4">
												<label class="label">Select Status Type</label>
												<label class="select">
													<select id="status_type">
														<option selected value="All">All (except Cured)</option>
														<option value="Cured">Cured</option>
														
													</select> <i></i> </label>
											</section>
										</div>
										</fieldset>
										</form>
									</div>
								</div>
								</div>
								
								<div class="row">
								<br>
								
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									<div class="well well-sm well-light">
										<br>
										<div >
										
										<!-- widget content -->
										<div class="col-md-12" id="loading_request_pie" style="display:none;">
												<center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
											</div>
										
											<center><div id="pie_request"></div></center>
											<i><label id="request_note" style="display:none;">Note : Write something.</label></i>
											<form style="display: hidden" action="drill_down_chronic_request_to_students_load_ehr" method="POST" id="ehr_form">
											  <input type="hidden" id="ehr_data" name="ehr_data" value=""/>
											  <input type="hidden" id="ehr_navigation" name="ehr_navigation" value=""/>
											</form>
										</div>
									</div>
								</div>
								
								</div>
								</div>
								<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->
					<!-- end widget -->
					</article>
					</div>
				
			<!-- row -->
			<!-- end row -->
			</div>
		<!-- widget grid -->
				
				
				<!-- Modal -->
					<div class="modal fade" id="load_waiting" tabindex="-1" role="dialog" >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title" id="myModalLabel">Loading dashboard in progress</h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-12">
											<img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" style="display: block; margin: 0 auto; width: 100px;">
										</div>
									</div>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
				
				
			<!-- </div>end row -->
			</section>
<br><br>

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



<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {

/*******************************************************************
 *
 * Helper : Initialize and update id list for chronic case line graph
 *
 */
 
function init_and_update_chronic_id_list(chronic_ids)
{
	chronic_id_list = chronic_ids;
	var options = $("#chronic_id_school_list");
	options.empty();
	options.append($("<option />").val("select_school").prop("selected",true).text("--select--"));
	
	var previous_selected_school = "";
	for(var index in chronic_ids)
	{
       if(chronic_ids[index].school_name !== previous_selected_school)
	   {
       options.append($("<option />").val(chronic_ids[index].school_name).text(chronic_ids[index].school_name));
	   }
	   previous_selected_school = chronic_ids[index].school_name;
	}
	
}

$('#chronic_id_school_list').change(function(e){
	
	var school    = $('#chronic_id_school_list').val();
	var options   = $("#chronic_id_list");
	//console.log();
	options.empty();
	options.append($("<option />").val("select_id").prop("selected",true).text("--select--"));
	
	for(var i in chronic_id_list)
	{
       if(chronic_id_list[i].school_name === school)
	   {
       options.append($("<option />").val(chronic_id_list[i].student_unique_id).text(chronic_id_list[i].student_unique_id).attr('case_id',chronic_id_list[i].case_id).attr('months',chronic_id_list[i].scheduled_months));
	   }
	}
})


$('#set_date_btn').click(function(e){
	today_date = $('#set_data').val();
	//alert(today_date);
	//location.reload();
	//$('#load_waiting').modal('show');

	$.ajax({
		url: 'to_dashboard_with_date',
		type: 'POST',
		data: {"today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
			
			
			data = $.parseJSON(data);
			chronic_ids       = $.parseJSON(data.chronic_ids);
			initialize_variables(today_date,chronic_ids);
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});







$('#select_dt_name').change(function(e){
	dist = $('#select_dt_name').val();
	dt_name = $("#select_dt_name option:selected").text();
	//alert(dist);
	var options = $("#school_name");
	options.prop("disabled", true);
	
	options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
	$.ajax({
		url: 'get_schools_list',
		type: 'POST',
		data: {"dist_id" : dist},
		success: function (data) {			

			result = $.parseJSON(data);
			console.log("nareshhhhhhhhhhhhhhhhhhhhhh",result)

			options.prop("disabled", false);
			options.empty();
			options.append($("<option />").val("All").prop("selected", true).text("All"));
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



$('#chronic_id_list').change(function(e){
	
	$('.timeline_month').removeClass('hide');
	var id_    = $('#chronic_id_list').val();
	var months = $("#chronic_id_list option:selected").attr('months');
	console.log(id_);
	console.log(months);
	var months_array = months.split(",");
	console.log(months_array);
	var options = $("#chronic_select_month");
	options.empty();
	options.append($("<option />").val("select_month").prop("selected",true).text("--select--"));
	
	for(var i in months_array)
	{
       options.append($("<option />").val(months_array[i]).text(months_array[i]));
	}
	
	options.append($("<option />").val("all_months").text("All Months Overview"));
})

function getMonth(monthStr){
    return new Date(monthStr+'-1-01').getMonth()+1;
}

$(document).on("click",'#view_pill_compliance',function(e){
	
	var id_            = $('#chronic_id_list').val();
	var case_id        = $("#chronic_id_list option:selected").attr('case_id');
	var selected_month = $("#chronic_select_month").val();
	
	console.log(id_);
	console.log(case_id);
	console.log(selected_month);
	
	if(selected_month != "all_months")
	{
		var date = new Date();
		var month_no = getMonth(selected_month);
		var firstDay = new Date(date.getFullYear(), month_no-1, 1);
		var lastDay  = new Date(date.getFullYear(), month_no, 0);
		var begin    = firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
		var end      = lastDay.getFullYear()+'-'+(lastDay.getMonth()+1)+'-'+lastDay.getDate();
		
		
		pill_graph_values = [];
		
		// PILL COMPLIANCE GRAPH PLOT
		$.ajax
		({
			url  : 'prepare_pill_compliance_monthly_graph',
			type : 'POST',
			data :{'unique_id':id_,'case_id':case_id,'begin':begin,'end':end},
			async:false,
			beforeSend:function ()
			{

			},
			complete:function ()
			{	
				
			},
			success: function (success_data) 
			{ 
			   $("#load_waiting").modal('hide');
			   success_data = success_data.trim();
			   console.log(success_data);
			   if((success_data!=="") && (success_data!==null))
			   {
				 try
				{
					var pill_comp_graph_data = JSON.parse(success_data);
					var graph    = pill_comp_graph_data.graph_data; 
					var start    = pill_comp_graph_data.start_date; 
					var end      = pill_comp_graph_data.end_date; 
					console.log("graph",graph);
					
					var obj = {'label':'Compliance Percentage','data':graph};
					pill_graph_values.push(obj);
					
		
					$.plot($("#chronic_line_graph"), pill_graph_values, {
					series: 
					{
					lines : {show: true},
					points: {show: true}
					},
					xaxis : {
					mode: 'time',
					tickSize: [1, "day"],
					axisLabel: "Days",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 20,
					min: start,
					max: end
					},
					grid: 
					{
					borderColor: 'black',
					borderWidth: 1
					},
					legend: 
					{
					show: true,
					container: '#compliance_legend'    
					},
					yaxis : {
					min: 0,
					max: 100,
					tickSize:25,
					axisLabel:"Percentage",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 10
					}
					});
				}
				catch(e)
				{
				
				} 
			   }
			   else
			   {
				 $("#chronic_line_graph").html("<center><label>No Data Available !</label></center>");
			   }
			}
		})
	}
	else
	{
        pill_graph_values = [];
		
		// PILL COMPLIANCE GRAPH PLOT
		$.ajax
		({
			url  : 'prepare_pill_compliance_overall_graph',
			type : 'POST',
			data :{'unique_id':id_,'case_id':case_id,'begin':begin,'end':end},
			async:false,
			beforeSend:function ()
			{

			},
			complete:function ()
			{	
				
			},
			success: function (success_data) 
			{ 
			   $("#load_waiting").modal('hide');
			   success_data = success_data.trim();
			   console.log(success_data);
			   if((success_data!=="") && (success_data!==null))
			   {
				 try
				{
					var pill_comp_graph_data = JSON.parse(success_data);
					var graph    = pill_comp_graph_data.graph_data; 
					var start    = pill_comp_graph_data.start_date; 
					var end      = pill_comp_graph_data.end_date; 
					console.log("graph",graph);
					
					var obj = {'label':'Compliance Percentage','data':graph};
					pill_graph_values.push(obj);
					
		
					$.plot($("#chronic_line_graph"), pill_graph_values, {
					series: 
					{
					lines : {show: true},
					points: {show: true}
					},
					grid: { hoverable: true, clickable: false },
					xaxis : {
					mode: "time",
					ticks: 12,
					timeformat:"%b %Y",
					minTickSize: [1, "month"],
					axisLabel: "Months",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 20,
					min: start,
					max: end
				    },
					grid: 
					{
					borderColor: 'black',
					borderWidth: 1
					},
					legend: 
					{
					show: true,
					container: '#compliance_legend'    
					},
					yaxis : {
					min: 0,
					max: 100,
					tickSize:25,
					axisLabel:"Percentage",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 10
					}
					});
				}
				catch(e)
				{
				
				} 
			   }
			   else
			   {
				 $("#chronic_line_graph").html("<center><label>No Data Available !</label></center>");
			   }
			}
		}) 
	}
})
$("select").each(function () {
        $(this).val($(this).find('option[selected]').val());
    });
	
	var request_data = "";
	var request_navigation = [];
	previous_request_a_value = [];
	previous_request_fn = [];
	previous_request_title_value = [];
	previous_request_search = [];
	request_search_arr = [];

		var today_date = $('#set_data').val();

	var dt_name = $('#select_dt_name').val();
	var school_name = $('#school_name').val();
	var chronic_id_list  = "";


$('.datepicker').datepicker({
	minDate: new Date(1900, 10 - 1, 25)
 });
initialize_variables();

change_to_default();

function change_to_default(today_date){
	$('#request_pie_span').val("Monthly");
	$('#screening_pie_span').val("Yearly");
	
	//$('#set_data').val(today_date);
	//$('#select_dt_name').val(dt_name);
	$('#school_name').val(school_name);
}

	
	initialize_variables(<?php echo $request_report?>,today_date,<?php echo $chronic_ids;?>);
	
	function initialize_variables(request_report,chronic_ids){

	init_request_pie(request_report);
		init_and_update_chronic_id_list(chronic_ids);
	}

draw_request_pie();

function init_request_pie(request_report){
	request_data = request_report;
	request_navigation = [];
	previous_request_a_value = [];
	previous_request_fn = [];
	previous_request_title_value = [];
	previous_request_search = [];
	request_search_arr = [];
}


function draw_request_pie(){
	if(request_data == 1){
		$("#pie_request").append('No positive values to dispaly');
	}else{
		request_navigation.push("Request PIE");
	request_pie(request_data,"drill_down_request_to_symptoms");
}
}

function request_pie(data, onClickFn){
	var pie = new d3pie("pie_request", {
		header: {
			title: {
				text: request_navigation.join(" / ")
			}
		},
		size: {
	        canvasHeight: 500,
	        canvasWidth: 590
	    },
	    data: {
	      content: data
	    },
	    labels: {
			
			"outer": {
			"pieDistance": 32
		},
	        inner: {
	            format: "value"
	        },
			
			"mainLabel": {
			"fontSize": 11
		},
			
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					console.log("Segment clicked! See the console for all data passed to the click handler.");
					console.log(onClickFn);
					if(onClickFn == "drill_down_request_to_symptoms"){
						console.log(a);
						previous_request_a_value[0] = data;
						previous_request_fn[0] = "drill_down_request_to_symptoms";
						drill_down_request_to_symptoms(a);
					}/*else if(onClickFn == "drilldown_chronic_request_to_districts"){
						console.log(a);
						previous_request_a_value[1] = data;
						previous_request_fn[1] = "drilldown_chronic_request_to_districts";

						previous_request_search[0] = request_navigation.join(" / ");
						request_search_arr[0] = previous_request_search[0];
						request_search_arr[1] =  a.data.label;
						drilldown_chronic_request_to_districts(request_search_arr);
					}*/else if(onClickFn == "drilldown_chronic_request_to_school"){
						console.log(a);
						previous_request_a_value[2] = data;
						previous_request_fn[2] = "drilldown_chronic_request_to_school";

						previous_request_search[1] = request_navigation.join(" / ");
						request_search_arr[0] = previous_request_search[1];
						request_search_arr[1] =  a.data.label;
						drilldown_chronic_request_to_school(request_search_arr);
					}else if(onClickFn == "drilldown_chronic_request_to_students"){
						request_search_arr[0] = previous_request_search[1];
						request_search_arr[1] =  a.data.label;
						drilldown_chronic_request_to_students(request_search_arr);
					}
					
				}
			}
	      
		});
}

$('#status_type').change(function(e){
	status_type = $('#status_type').val();
	$( "#pie_request" ).hide();
	$("#loading_request_pie").show();
	$.ajax({
		url: 'update_chronic_request_pie',
		type: 'POST',
		data: {"status_type" : status_type},
		success: function (data) {			
			$("#loading_request_pie").hide();
			$( "#pie_request" ).show();
			$( "#pie_request" ).empty();
			console.log(data);
			data = $.parseJSON(data);
			request_data = $.parseJSON(data.request_report);
			console.log(request_data);
			init_request_pie(request_data);
			draw_request_pie();			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});


function drill_down_request_to_symptoms(pie_data){
	status_type = $('#status_type').val();
	$.ajax({
		url: 'drill_down_request_to_symptoms',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "status_type" : status_type},
		success: function (data) {
			//console.log(data);
			var content = $.parseJSON(data);
			//console.log(content);
			$( "#pie_request" ).empty();
			$("#pie_request").append('<button class="btn btn-primary pull-right" id="request_back_btn" ind= "0"> Back </button>');

			request_navigation.push(pie_data.data.label);
			request_pie(content,"drilldown_chronic_request_to_school");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
	});
}

function drilldown_chronic_request_to_districts(pie_data){
	status_type = $('#status_type').val();
	$.ajax({
		url: 'drilldown_chronic_request_to_districts',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "status_type" : status_type},
		success: function (data) {
			//console.log(data);
			var content = $.parseJSON(data);
			//console.log(content);
			$( "#pie_request" ).empty();
			$("#pie_request").append('<button class="btn btn-primary pull-right" id="request_back_btn" ind= "1"> Back </button>');

			request_navigation.push(pie_data[1]);
			request_pie(content,"drilldown_chronic_request_to_school");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
	});
}

function drilldown_chronic_request_to_school(pie_data){
	status_type = $('#status_type').val();
	$.ajax({
		url: 'drilldown_chronic_request_to_school',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "status_type" : status_type},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie_request" ).empty();
			$("#pie_request").append('<button class="btn btn-primary pull-right" id="request_back_btn" ind= "2"> Back </button>');

			request_navigation.push(pie_data[1]);
			request_pie(content,"drilldown_chronic_request_to_students");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
	});
}

function drilldown_chronic_request_to_students(pie_data){
	status_type = $('#status_type').val();
	$.ajax({
		url: 'drilldown_chronic_request_to_students',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "status_type" : status_type},
		success: function (data) {
			console.log(data);
			$("#ehr_data").val(data);
			request_navigation.push(pie_data[1]);
			$("#ehr_navigation").val(request_navigation.join(" / "));
			
			$("#ehr_form").submit();
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

$(document).on("click",'#request_back_btn',function(e){
		var index = $(this).attr("ind");
		console.log('indexxxxxxxxxxxxxxxxxxxxxxx----', index);
		$( "#pie_request" ).empty();
		if(index>0){
			var ind = index-1;
		$("#pie_request").append('<button class="btn btn-primary pull-right" id="request_back_btn" ind= "' + ind + '"> Back </button>');
		}
		request_navigation.pop();
		console.log('fnnnnnnnnnnnnnnnnnnnnnn----', previous_request_fn[index]);
		request_pie(previous_request_a_value[index], previous_request_fn[index]);
});

 
});


</script>

