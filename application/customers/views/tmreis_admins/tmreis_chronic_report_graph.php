<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Chronic Reports Graph";

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
#chronic_line_graph div.xAxis div.tickLabel {
  transform: translateY(15px) rotate(45deg);
  -ms-transform: translateY(15px) rotate(45deg);
  /* IE 9 */
  -moz-transform: translateY(15px) rotate(45deg);
  /* Firefox */
  -webkit-transform: translateY(10px) rotate(45deg);
  -webkit-transition: width 0px; /* Safari */
  /* Safari and Chrome */
  -o-transform: translateY(15px) rotate(-90deg);
  /* Opera */
  /*rotation-point:50% 50%;*/
  /* CSS3 */
  /*rotation:270deg;*/
  /* CSS3 */
}
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "https://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">

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
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12 chronic_tile">
									<div class="well well-sm well-light chronic_simple">
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
									<!-- CHRONIC ADVANCED -->
									<div class="well well-sm well-light chronic_advanced hide">
									<form class="smart-form" >
										<fieldset style="padding-top: 0px; padding-bottom: 0px;">
										<section class="col col-2 chronic_advanced_start_month">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Select Start Month</label></div>
												<div class="input-group">
											<select id="chronic_advanced_select_start" name="chronic_advanced_select_start" class="form-control">
														<option value='select_month' >-- Select --</option>
														</select> <i></i>
												<span class="input-group-addon"><i class="fa  fa-caret-down"></i></span>
												</div>
												<div class="input-group">

												</div>
										</div>
										</section>
										<section class="col col-2 chronic_advanced_end_month">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Select End Month</label></div>
												<div class="input-group">
											<select id="chronic_advanced_select_end" name="chronic_advanced_select_end" class="form-control">
														<option value='select_month' >-- Select --</option>
														</select> <i></i>
												<span class="input-group-addon"><i class="fa  fa-caret-down"></i></span>
												</div>
										</div>
										</section>
										<section class="col col-4">
										<div class="input-group">
										<label class="label" for="item1">Action</label>
													<button type="button" class="btn bg-color-green txt-color-white btn-sm view_advanced_pill_compliance" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
						                       View
						                    </button>
						             		<button style="margin-left:5px;" type="button" class="btn bg-color-orange txt-color-white btn-sm cancel_chronic_advanced">
											Cancel
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

			
	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
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

<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {

	var today_date = $('#set_data').val();
	var dt_name = $('#select_dt_name').val();
	var school_name = $('#school_name').val();
	var chronic_id_list   = "";
	var chronic_data = "";
	var chronic_ids = <?php echo $chronic_ids; ?>;
	
	initialize_variables(today_date,<?php echo $chronic_ids;?>);

function initialize_variables(today_date,chronic_ids){
	console.log('init fn', today_date);
	today_date = today_date;
	console.log('init fun222222', today_date);


	init_and_update_chronic_id_list(chronic_ids);

}



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
			console.log(result)

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

// custom range
$('#chronic_select_month').change(function(e){
	var selected_month = $("#chronic_select_month").val();
	if(selected_month == "custom_range")
	{
		$('#chronic_line_graph').empty();
		$('#compliance_legend').empty();
		$('.compliance_schedule').remove();
		$('.chronic_simple').addClass('hide');
		$('.chronic_advanced').removeClass('hide');
		var months = $("#chronic_id_list option:selected").attr('months');
		var months_array = months.split(",");
		
		// start
		var options = $("#chronic_advanced_select_start");
		options.empty();
		options.append($("<option />").val("select_startmonth").prop("selected",true).text("--select--"));
	
		for(var i in months_array)
		{
	       options.append($("<option />").val(months_array[i]).text(months_array[i]));
		}

        // end
		var options = $("#chronic_advanced_select_end");
		options.empty();
		options.append($("<option />").val("select_endmonth").prop("selected",true).text("--select--"));
	
		for(var i in months_array)
		{
	       options.append($("<option />").val(months_array[i]).text(months_array[i]));
		}
	}
});


$(document).on("click",'.cancel_chronic_advanced',function(e){
	$('.chronic_advanced').addClass('hide');
	$('.chronic_simple').removeClass('hide');
	$('select[name="chronic_select_month"]').val('select_month');
	$('#chronic_line_graph').empty();
	$('#compliance_legend').empty();
	$('.compliance_schedule').remove();
})

/**
 * @param {int} The month number, 0 based
 * @param {int} The year, not zero based, required to account for leap years
 * @return {Date[]} List with date objects for each day of the month
 */
function getDaysInMonth(month, year) {
     // Since no month has fewer than 28 days
     var date = new Date(year, month, 1);
     var days = [];
     while (date.getMonth() === month) {
        days.push(new Date(date));
        date.setDate(date.getDate() + 1);
     }
     return days;
}

$(document).on("click",'.view_advanced_pill_compliance',function(e){
	
	$('.compliance_schedule').remove();

	var id_         = $('#chronic_id_list').val();
	var case_id     = $("#chronic_id_list option:selected").attr('case_id');
	var start_month = $("#chronic_advanced_select_start").val();
	var end_month   = $("#chronic_advanced_select_end").val();
	
	// start
	var date = new Date();
	var start_month_no = getMonth(start_month);
	var end_month_no   = getMonth(end_month);

	var firstDay = new Date(date.getFullYear(), start_month_no-1, 1);
	var lastDay  = new Date(date.getFullYear(), end_month_no, 0);
	var begin    = firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
	var end      = lastDay.getFullYear()+'-'+(lastDay.getMonth()+1)+'-'+lastDay.getDate();
		
		
	pill_graph_values = [];
		
		// PILL COMPLIANCE GRAPH PLOT
		$.ajax
		({
			url  : 'prepare_pill_compliance_monthly_graph',
			type : 'POST',
			data :{'unique_id':id_,'case_id':case_id,'begin':begin,'end':end},
			async:true,
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
					var schedule = pill_comp_graph_data.schedule;
					
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
					tickSize: [2, "day"],
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

					$('<div class="well pull-right compliance_schedule" style="margin-right:10px;">Schedule : '+schedule+'</div>').insertAfter('#compliance_legend');
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
})

$(document).on("click",'#view_pill_compliance',function(e){
	
	$('.compliance_schedule').remove();

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
			async:true,
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
			async:true,
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
					timeformat:"%b %y",
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

 
});			
		

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

