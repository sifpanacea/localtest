<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Students Report";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa basic_dashboard"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<style>
#set_date
{
	float:right;
	 width: 200px; 
}
/*@media only screen and (max-width: 400px) {
  .abs_submitted_schools_list {
    display: block;
  }
  .abs_not_submitted_schools_list{
    display: block;
  }
  
}
*/

</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Reports"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
		
			<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget" id="wid-id-5" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
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
								
								<div class="row">
        <article class="col-sm-12 col-md-10 col-lg-10">
        <div class="jarviswidget jarviswidget-color-orange" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
			<header>
				<span class="widget-icon"> <i class="fa fa-user"></i> </span>
				<h2>Select a district </h2>
			</header>

<!-- widget div-->
<div>
	<!-- widget content -->
	
	<div class="widget-body no-padding">
	<form class=smart-form>
		<!--<form class="smart-form">-->
			
				<fieldset>
			<div class="row">
			<section class="col col-6">
					<label class="label" for="school_name">School Name</label>
					<label class="select">
					<select id="school_name" name="school_name">
					<option value='All' >All</option>
							<?php if(isset($schools_list)): ?>
								<?php foreach ($schools_list as $school):?>
								<option value='<?php echo $school['school_name']?>'><?php echo ucfirst($school['school_name'])?></option>
								<?php endforeach;?>
								<?php else: ?>
								
							<?php endif ?>
					</select> <i></i>
				</label>

				</section>
				 <input type="hidden" name="school_code" id="school_code"><br>
			
			<label class="label"></label>
				<section class="col col-2">
					<button type="button" class="btn bg-color-pink txt-color-white btn-sm" id="set_button" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
				   Set
					</button>
				</section>
			</div>
			</fieldset>	
			</form>
			<form style="display: hidden" action="drill_down_screening_to_students_load_ehr_count" method="POST" id="ehr_form">
			  <input type="hidden" id="ehr_data" name="ehr_data" value=""/>
			  <input type="hidden" id="ehr_navigation" name="ehr_navigation" value=""/>
			</form>
		

	</div>
	<!-- end widget content -->

</div>
<!-- end widget div -->

</div>
</article>

</div><!-- ROW -->
				
								
										<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
										
										<!-- widget content -->
									<div class="widget-body">
				
									 <div class="panel panel-primary" >
								      <div class="panel-heading clearfix">
												<h4 class="panel-title pull-left" style="padding-top: 7.5px;">Daily Health Issues</h4>
													<div class="input-group">
														<input type="text" id="set_date" name="set_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo date('Y-m-d')?>">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<div class="input-group-btn">
														<button type="button" class="btn btn-success button_field" id="set_date_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
														  Set date
															</button>
														</div>
													</div>
											</div>
								      <div class="panel-body">
								      		<div id="request_response_table_view"></div>
								      </div>
								    </div>
									</div>
								</div>
								</div>
								<div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                    
                    <!-- widget content -->
                  <div class="widget-body">
        
                   <div class="panel panel-primary" >
                      <div class="panel-heading clearfix">
                        <h4 class="panel-title pull-left">Total no of rhso submmitted schools list </h4>
                          <div class="input-group">
                            <input type="text" id="set_date_xl" name="set_date_xl" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo date('Y-m-d')?>">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            
                            <div class="input-group-btn">
                            <button type="button" class="btn btn-success button_field" id="set_date_btn_xl">
                              Set date
                              </button>
                            </div>
                          </div>
                      </div>
                      <div class="panel-body">
                      	<div>RHSO Submitted Schools Count:<span id="xl_count"></span></div>
                      	<div id="stud_report">
                Select from drop down to display student report.

                </div>
               
                           <div id="stud_report_rhso"></div>
                      </div>

                    </div>
                  
                  </div>
                </div>
                </div>


		<div class="row">
			<section>
		        <article class="col-sm-6 col-md-6 col-lg-6">
		        <div class="jarviswidget jarviswidget-color-purple" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Screening Report</h2>
	<!-- <label class="label">Search Span</label> -->
	<label class="select pull-right">
	<select id="screening_pie_span" class="get_last_screened form-control" style="color: #705878;">
		<option value="Monthly">Monthly</option>
		<option value="Bi Monthly">Bi Monthly</option>
		<option value="Quarterly">Quarterly</option>
		<option value="Half Yearly">Half Yearly</option>
		<option value="2015-16 Academic Year">2015-16 Academic Year</option>
		<option value="2016-17 Academic Year">2016-17 Academic Year</option>
		<option selected value="Yearly">2017-18 Academic Year</option>
	</select> <i></i> </label>
	<div id="showPIEBtn"></div>
</header>

<!-- widget div-->
<div style="height: 350px">

	<!-- widget edit box -->
	
	<!-- end widget edit box -->

	<!-- widget content -->
	
	<div class="widget-body">
		<div class="row">
			<!-- <div class="col col-md-3" id="select_year_wise">
					<form class="smart-form">
					<label class="label">Search Span</label>
					<label class="select">
						<select id="screening_pie_span" class="get_last_screened">
							<option value="Monthly">Monthly</option>
							<option value="Bi Monthly">Bi Monthly</option>
							<option value="Quarterly">Quarterly</option>
							<option value="Half Yearly">Half Yearly</option>
							<option value="2015-16 Academic Year">2015-16 Academic Year</option>
							<option value="2016-17 Academic Year">2016-17 Academic Year</option>
							<option selected value="Yearly">2017-18 Academic Year</option>
						</select> <i></i> </label>
				</form>
			</div> -->
			<div class="col col-md-5">
				<div id="screening_report">
				
				</div>
			

				<div id="abnormalties_report_table">
					
				</div>
			</div>
			<div class="col col-md-4 hide" id="test">
				<center>
				<div id="pie_screening" class="">
					
				
			</div></center>
		</div>
		</div>
				
	</div>
	<!-- end widget content -->

</div>
<!-- end widget div -->

</div></article>
        <article class="col-sm-6 col-md-6 col-lg-6">
        <div class="jarviswidget jarviswidget-color-purple" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Attendance Report</h2>

</header>

<!-- widget div-->
<div >


	<!-- widget content -->
	
	<div class="widget-body">
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-12">
			<div class="well well-sm well-light">
			
			<div class="well well-sm well-light">
			<br>
			<div id="pie_absent"></div>
			<form style="display: hidden" action="drill_down_absent_to_students_load_ehr" method="POST" id="ehr_form_for_absent">
				<input type="hidden" id="ehr_data_for_absent" name="ehr_data_for_absent" value=""/>
				<input type="hidden" id="ehr_navigation_for_absent" name="ehr_navigation_for_absent" value=""/>
			</form>
			<a href="javascript:void(0)" class="abs_submitted_schools_list">Submitted Schools</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="abs_submitted_schools"></span>
			<a href="javascript:void(0)" class="abs_not_submitted_schools_list pull-right"> Not Submitted Schools </a><span class="abs_not_submitted_schools"></span>
			</div>
		
			</div>
			</div>
			</div>
		</div>
				
	</div>
	<!-- end widget content -->

</div>
<!-- end widget div -->

</article>
</section>



<article class="col-sm-12 col-md-10 col-lg-10">
        <div class="jarviswidget jarviswidget-color-purple" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Sanitation Report</h2>
	<div id="showGraphBtn"></div>
</header>

<!-- widget div-->
<div>

	<!-- widget edit box -->
	<div class="jarviswidget-editbox">
		<!-- This area used as dropdown edit box -->

	</div>
	<!-- end widget edit box -->

	<!-- widget content -->
	
	<div class="widget-body">
		<div class="row">
		<div class="col col-md-5">
		
				<div id="sanitation_report_table"></div>
				<div id="sanitation_report_counts"></div>
			
			<br>
			<div class="form-group checkbox_sanitation hide" >
									
										<div class="col-md-10">
											<label class="checkbox-inline col col-md-3">
													<input type="checkbox" class="checkbox style-0" id="daily">
													<span>Daily</span>
											</label>
											<label class="checkbox-inline col col-md-3">
													<input type="checkbox" class="checkbox style-0" id="weekly">
													<span>Weekly</span>
											</label>
											<label class="checkbox-inline col col-md-3">
													<input type="checkbox" class="checkbox style-0" id="monthly">
													<span>Monthly</span>
											</label>
										</div>
				</div>
			</div>
			<div class="col col-md-7">
			
					<div class="" id="chart_details" style="height:300px">
		
	 				
				</div>
					
			</div>
		
		
				
	</div>
	<div class="row">
		<div class="col col-md-5">
				<div id="show_yes_no"></div>
					<div id="show_yes_no_count"></div>
		</div>
		<div class="col col-md-7">
			<div id="myDiv"><!-- Plotly chart will be drawn inside this DIV --></div>
		</div>
	</div>
	<!-- end widget content -->

</div>
<!-- end widget div -->

</div>

</article>


</div><!-- ROW -->
<br><br>


	
	<form style="display: hidden" action="<?php echo URL; ?>rhso_users/get_show_ehr_details" method="POST" id="requests_show_form">
	  <input type="hidden" id="to_date_new" name="to_date_new" value=""/>
	  <input type="hidden" id="school_name_new" name="school_name_new" value=""/>
	  <input type="hidden" id="request_type_new" name="request_type_new" value=""/>
	</form>
	

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
								<!-- ATTENDANCE SUBMITTED SCHOOLS LIST -->
								<div class="modal fade-in" id="absent_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							×
						</button>
						<h4 class="modal-title" id="myModalLabel">Absent Report Submitted Schools </h4>
					</div>
					<div id="absent_sent_school_modal_body" class="modal-body">
		            
					
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="absent_sent_school_download">
							Download
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		
		<!-- ATTENDANCE NOT SUBMITTED SCHOOLS LIST -->
		<div class="modal" id="absent_not_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							×
						</button>
						<h4 class="modal-title" id="myModalLabel">Absent Report Not Submitted Schools </h4>
					</div>
					<div id="absent_not_sent_school_modal_body" class="modal-body">
					
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="absent_not_sent_school_download">
							Download
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<script src="<?php echo JS; ?>echarts.min.js"></script>
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<script src="<?php echo JS; ?>plotly-1.2.0.min.js"></script>


<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {
	
	var screening_pie_span = "Yearly";
	var screening_data = "";
	var screening_navigation = [];
	previous_screening_a_value = [];
	previous_screening_title_value = [];
	previous_screening_search = [];
	search_arr = [];
	

	var absent_submitted_schools_list     = "";
	var absent_not_submitted_schools_list = "";
	
	
	
	var today_date = $('#set_date').val();
	var dt_name = $('#select_dt_name').val();
	var school_name = $('#school_name').val();
		
		//console.log('php111111111111111', today_date);
		
		$('.datepicker').datepicker({
			minDate: new Date(1900, 10 - 1, 25)
		});
		
		change_to_default(today_date);
		display_sanitation_graph_default();

		function change_to_default(today_date,screening_report, absent_report){
			today_date = today_date;
			$('#screening_pie_span').val("Yearly");
			request_count_all();
		}
		
		initialize_variables(today_date,<?php echo $screening_report?>);
		function initialize_variables(today_date,screening_report,absent_report,absent_submitted_schools_list_count,absent_not_submitted_schools_list_count,absent_submitted_schools_list,absent_not_submitted_schools_list)
		{
				today_date = today_date;
				init_screening_pie(screening_report);
				init_absent_pie(absent_report);
				$('.abs_submitted_schools').html(absent_submitted_schools_list_count);
				$('.abs_not_submitted_schools').html(absent_not_submitted_schools_list_count);
		}
		
		function request_count_all()
		{
			
			$.ajax({
			url: 'initiate_request_count_all_today_date',
			//url: 'initaite_requests_status_count_new_dashboard',
			type: 'POST',
			data: {"today_date" : today_date},
			success: function (data) {			
				$('#load_waiting').modal('hide');
				//console.log('test===========',test);
				result = $.parseJSON(data);
				
				console.log('test===========',result);
				document_details_list(result);
				//console.log(result);
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			})
			
		}
		
		$('#set_date_btn').click(function(e){
			
			today_date = $("#set_date").val();
			
			
			//console.log('school_name',school_name);
			$.ajax({
			url: 'initiate_request_count_all_today_date',
			type: 'POST',
			data: {"today_date" : today_date},
			success: function (data) {			
				$('#load_waiting').modal('hide');
				$("#submitted_by_name_dr1").html("");
				$("#submitted_by_name_dr2").html("");
				$("#submitted_by_name_dr3").html("");
				$("#submitted_by_name_dr4").html("");
				$("#submitted_by_name_dr5").html("");
				$("#submitted_by_name_dr6").html("");
				$("#submitted_by_name_dr7").html("");
				result = $.parseJSON(data);
				console.log('resultresultresultresultresultresult', result);
				document_details_list(result);
				
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			})
		});
		
		
		 
//debugger;
	$('#chronic_request_uniqueids').click(function(){
	console.log("hhhhhhhhhh");
		$('#chronic_request_modal_body').empty();
		var table="";
		var tr="";
		table += "<table class='table table-bordered' id='absent_not_submitted_schools_list_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
		for(var i=0;i<absent_not_submitted_schools_list['school'].length;i++)
		{
			var j=i+1;
			table+= "<tr><td>"+j+"</td><td>"+absent_not_submitted_schools_list['district'][i]+"</td><td>"+absent_not_submitted_schools_list['school'][i]+"</td><td>"+absent_not_submitted_schools_list['mobile'][i]+"</td><td>"+absent_not_submitted_schools_list['person_name'][i]+"</td></tr>"
		}
		table += "</tbody></table>";
		console.log(table)
		$(table).appendTo('#chronic_request_modal_body');
		
	
	$('#chronic_modal').modal('show');
})

	/*******************************************************************
 *
 * Helper : Initialize for screening pie
 *
 */
 
function init_screening_pie(screening_report,schoolEmail)
{
	screening_data 					= screening_report;
	schoolEmail 					= schoolEmail;
	screening_navigation            = [];
	previous_screening_a_value 		= [];
	selected_school_email 			= [];
	previous_screening_title_value 	= [];
	previous_screening_search 		= [];
	search_arr 						= [];
}

function init_absent_pie(absent_report){
	absent_data = absent_report;
	absent_navigation = [];
	previous_absent_a_value = [];
	previous_absent_title_value = [];
	previous_absent_search = [];
	absent_search_arr = [];
}

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
				})		
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


$('#set_button').click(function(e){
	var today_date = $('#set_date').val();
	 //dt_name = $('#select_dt_name').val();
	 school_name = $('#school_name').val();
	if(dt_name == "All" && school_name == "All"){

	$.ajax({
		url: 'basic_dashboard_with_date',
		type: 'POST',
		data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span, "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
					console.log('ALLLLLLL', data);
				$( "#screening_report" ).empty();
				data = $.parseJSON(data);
				
					screening_report  = $.parseJSON(data.screening_report);
					result  = $.parseJSON(data.sanitation_report);
					absent_report  = $.parseJSON(data.absent_report);
					display_sanitation_graph(result);
					display_yes_no_table(result);
					initialize_variables(today_date,screening_report,absent_report);
					// Absent Report
					var absent_submitted_schools_list_count = data.absent_report_schools_list.submitted_count;
					var absent_not_submitted_schools_list_count = data.absent_report_schools_list.not_submitted_count;
					absent_submitted_schools_list     = "";
					absent_not_submitted_schools_list = "";
					absent_submitted_schools_list     = data.absent_report_schools_list.submitted;
			        absent_not_submitted_schools_list = data.absent_report_schools_list.not_submitted; 
					draw_absent_pie();
					draw_all_screening_pie();

			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}else{
	
	
	$.ajax({
		url: 'get_screening_data_with_district_school',
		type: 'POST',
		data: {/*"today_date" : today_date, "screening_pie_span" : screening_pie_span,*//* "dt_name" : dist,*/"today_date" : today_date, "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
			$('#select_year_wise').hide();
				result = $.parseJSON(data);
				screening_data = result.screening_report;
				schoolEmail = result.school_email_id;

				console.log('absnrttttttttt',data);
				
				init_screening_pie(screening_data,schoolEmail);
				document_details_list(result)
				display_data_table(screening_data);
				$('#showPIEBtn').html('<button class="btn bg-color-green txt-color-white pull-right" id="showPIE">Show PIE</button>');
				$('#showPIE').click(function(){
					draw_screening_pie();
					$('#test').removeClass('hide');

				});
				result = result.sanitation_report;
				//draw_sanitation_report_table(result);
				
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}
	
});

	function display_sanitation_graph_default(){

		var today_date = $('#set_date').val();
		//var dt_name = $('#select_dt_name').val();
		var school_name = $('#school_name').val();
		
		if(school_name == "All"){
		$.ajax({
			url: 'basic_dashboard_with_date',
			type: 'POST',
			data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span,"school_name" : school_name},
			success: function (data) {
				$('#load_waiting').modal('hide');
				
					$( "#screening_report" ).empty();
					data = $.parseJSON(data);
						screening_report  = $.parseJSON(data.screening_report);
						result  = $.parseJSON(data.sanitation_report);
						absent_report  = $.parseJSON(data.absent_report);
						display_sanitation_graph(result);
						display_yes_no_table(result);
						initialize_variables(today_date,screening_report,absent_report,absent_submitted_schools_list_count,absent_not_submitted_schools_list_count,absent_submitted_schools_list,absent_not_submitted_schools_list);
						// Absent Report
						var absent_submitted_schools_list_count = data.absent_report_schools_list.submitted_count;
						var absent_not_submitted_schools_list_count = data.absent_report_schools_list.not_submitted_count;
						absent_submitted_schools_list     = "";
						absent_not_submitted_schools_list = "";
						absent_submitted_schools_list     = data.absent_report_schools_list.submitted;
				        absent_not_submitted_schools_list = data.absent_report_schools_list.not_submitted; 
						draw_all_screening_pie();
						draw_absent_pie();

					
				
				},
				error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
				}
			});
	}
	}

	function document_details_list(result)
		{
			today_date = $("#set_date").val();
			school_name = $('#school_name').val();
			
			

			url = '<?php echo URL."rhso_users/get_show_ehr_details"; ?>';
			if((result.initiate_request_count_for_today) > 0 ){
				
				
				initiate_request_count_for_today = result.initiate_request_count_for_today;
				doctors_count = result.doctors_count;
				//submitted_by = result.submitted_by;
				doctor_name_dr1 = result.doctor_name_dr1;
				doctor_name_dr2 = result.doctor_name_dr2;
				doctor_name_dr3 = result.doctor_name_dr3;
				doctor_name_dr4 = result.doctor_name_dr4;
				doctor_name_dr5 = result.doctor_name_dr5;
				doctor_name_dr6 = result.doctor_name_dr6;
				doctor_name_dr7 = result.doctor_name_dr7;
				doctors_count_dr1 = result.doctors_count_list_dr1;
				doctors_count_dr2 = result.doctors_count_list_dr2;
				doctors_count_dr3 = result.doctors_count_list_dr3;
				doctors_count_dr4 = result.doctors_count_list_dr4;
				doctors_count_dr5 = result.doctors_count_list_dr5;
				doctors_count_dr6 = result.doctors_count_list_dr6;
				doctors_count_dr7 = result.doctors_count_list_dr7;
				
				  $('#request_response_table_view').html('<div id="request_response" class="text-center"></div>');

				var table = '<div style="overflow-y: auto;" ><table class="table table-striped table-bordered table-hover" id="requestTable"><thead><tr><th></th><th class="text-center">Requests count</th><th class="text-center">Responses count</th><th colspan="2" class="text-center">Request Attended By</th></tr></thead><tbody>'

				table = table + '<tr><th>Total Requests</th><td>'+initiate_request_count_for_today+'</td><td>'+doctors_count+'</td>'
				
				if((doctors_count_dr1)>0)
				{
					//$("#submitted_by_name_dr1").html( );
					table = table+'<td>'+doctor_name_dr1+'</td><td>'+doctors_count_dr1+'</td></tr>';
				
				}
				if((doctors_count_dr2)>0)
				{
					table = table+'<td>'+doctor_name_dr2+'</td><td>'+doctors_count_dr2+'</td></tr>';
					
				}
				if((doctors_count_dr3)>0)
				{
					table = table+'<td>'+doctor_name_dr3+'</td><td>'+doctors_count_dr3+'</td></tr>';
				}
				if((doctors_count_dr4)>0)
				{
					table = table+'<td>'+doctor_name_dr4+'</td><td>'+doctors_count_dr4+'</td></tr>';
				}
				if((doctors_count_dr5)>0)
				{
					table = table+'<td>'+doctor_name_dr5+'</td><td>'+doctors_count_dr5+'</td></tr>';
				}
				if((doctors_count_dr6)>0)
				{
					table = table+'<td>'+doctor_name_dr6+'</td><td>'+doctors_count_dr6+'</td></tr>';
				}
				if((doctors_count_dr7)>0)
				{
					table = table+'<td>'+doctor_name_dr7+'</td><td>'+doctors_count_dr7+'</td></tr>';
				}
				
				
					
					table = table + '<tr><th>Normal Requests</th><td>'+result.normal_requests_count+'</td><td class="hide">'+today_date+'</td><td class="hide">Normal</td><td class="hide">'+school_name+'</td><td><button class="btnShow">Show</button></td></tr>'

					table = table + '<tr><th>Emergency Requests</th><td>'+result.emergency_requests_count+'</td><td class="hide">'+today_date+'</td><td class="hide">Emergency</td><td class="hide">'+school_name+'</td><td><button class="btnShow">Show</button></td></tr>'
					
					table = table + '<tr><th>Chronic Requests</th><td><span id="chronic_request_uniqueids">'+result.chronic_requests_count+'</td><td class="hide">'+today_date+'</td><td class="hide">Chronic</td><td class="hide">'+school_name+'</td><td><button class="btnShow">Show</button></td></tr>'
					
			
				$("#request_response").html(table);
				table = table + '</tbody></table></div>';
				
								
		}
		else
			{
				initiate_request_count_for_today = result.initiate_request_count_for_today;
				doctors_count = result.doctors_count;
				$("#request_response").html(initiate_request_count_for_today);
				$("#request_response").html(doctors_count);
				//$("#request_response").html("<h6>No Initiate Requests</h6>");
				$("#request_response_table_view").html("<h6>No Initiate Requests</h6>");
				//$("#submitted_by_name_dr1").html("<h6>No Initiate Requests</h6>");
				//$("#submitted_by_name_dr4").html("<h6>No Initiate Requests</h6>");
			}
			
			$("#requestTable").each(function(){
			 	$('.btnShow').click(function (){
	        		 var currentRow=$(this).closest("tr"); 
					 var to_date_new=currentRow.find("td:eq(1)").text(); // get current row 1st TD
					 var request_type_new=currentRow.find("td:eq(2)").text(); // get current row 2nd TD
					 var school_name_new=currentRow.find("td:eq(3)").text(); // get current row 4th TD
				
					console.log('to_date_new',to_date_new);
					console.log('request_type_new',request_type_new);
					console.log('school_name_new',school_name_new);
					
					 
					$("#to_date_new").val(to_date_new);
					$("#request_type_new").val(request_type_new);
					$("#school_name_new").val(school_name_new);
					
					$("#requests_show_form").submit();

					 
				});
			 });

		} 
		
	
		

function display_data_table(screening_data){
		
		console.log('screening_data', screening_data);
		//debugger;
		screening_data.forEach(function(e){
		if( e.value === 0){
			$('#abnormalties_report_table').hide();
			$('#pie_screening').hide();
			$("#screening_report").html("<h5 class='text-center'><div class='alert alert-danger'>No Screening data available for this School <strong>"+ school_name+"</strong></div></h5>");
		}
		else{
			$('#abnormalties_report_table').show();
			data_table = '<table id="screening_report_table" class="table table-striped table-bordered" width="100%"><tbody>';
			data_table = data_table + '<tr class="txt-color-magenta"><th>Identifier Name</th><th>No.of Issues found</th><th>Abnormalities</th></tr>';
				$.each(screening_data, function(index, value) {
				
				data_table = data_table + '<tr>';
				data_table = data_table + '<td id="identifier_table">'+value.label+'</td>';
				data_table = data_table + '<td><span class="badge bg-color-orange">'+value.value+'</span></td>';
				data_table = data_table + '<td><button class="btn btn-primary btn-xs identifier_label_btn" id="identifier_label_btn" value="'+value.label+'">View</button></td>';
				data_table = data_table + '</tr>';
			
				});
			data_table = data_table + '</tbody></table>';

			$("#screening_report").html(data_table);
		
	

    	$(".identifier_label_btn").each(function(){
			 	$(this).click(function (){
	        		var selectedLabel = $(this).val();
	        		    $.ajax({
						url: 'get_screening_data_with_abnormalities',
						type: 'POST',
						data: {"selectedLabel" : selectedLabel, "schoolEmail":schoolEmail},
						success: function (data) {
							$('#load_waiting').modal('hide');
							abnormalities = $.parseJSON(data);
							console.log(abnormalities);
							display_abnormalties_table(abnormalities,selectedLabel);
							
							console.log('abnormalities', abnormalities);
							
							},
						    error:function(XMLHttpRequest, textStatus, errorThrown)
							{
							 console.log('error', errorThrown);
						    }
						});
					
				});
			 });

    	}
	})

			
			//=====================================================================================================
			
	}


	function display_abnormalties_table(abnormalities,selectedLabel){
		if(abnormalities.length > 0){
			table = '<div class="panel panel-default"><div class="panel-heading"><h4 class="text-center">'+selectedLabel+'</h4></div><div class="panel-body"><table id="abnormality_report_table" class="table table-striped table-bordered" width="100%"><tbody>';


			$.each(abnormalities, function(index, value) {
				table = table + '<tr>';
				table = table + '<td id="abnormality_label_name">'+value.label+'</td>';
				table = table + '<td>'+value.value+'</td>';
				table = table + '<td><button class="btn btn-primary btn-xs abnormality_label_btn" id="abnormality_label_btn" value="'+value.label+'">Show EHR</button></td>';
				table = table + '</tr>';
					
			});

			table = table + '</tbody></table></div></div>';

			$("#abnormalties_report_table").html(table);

			}

			else
			{
				$("#abnormalties_report_table").html('<h5>No data available</h5>');
			}

			$(".abnormality_label_btn").each(function(){
			 	$(this).click(function (){
	        		var symptome_type = $(this).val();
	        		  $.ajax({
						url: 'drill_down_screening_to_students_count',
						type: 'POST',
						data: {"symptome_type" : symptome_type, "schoolEmail":schoolEmail},
						success: function (data) {
							$("#ehr_data").val(data);
							$("#ehr_navigation").val(symptome_type);
							
							$("#ehr_form").submit();
							
							},
						    error:function(XMLHttpRequest, textStatus, errorThrown)
							{
							 console.log('error', errorThrown);
						    }
						});
					
				});
			 });

		}

				
/*******************************************************************
 *
 * Helper : Screening Pie
 *
 */
 
function screening_pie(heading, screening_data, onClickFn){
	var pie = new d3pie("pie_screening", {
		header: {
			title: {
				text: heading
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth: 400
	    },
	    data: {
	      content: screening_data
	    },
	    labels: {
	        inner: {
	            format: "value"
	        }
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					
					if(onClickFn == "drilling_screening_to_abnormalities_pie"){
						console.log('drilling_screening_to_abnormalities_pie===461', a);
						previous_screening_a_value[1] = screening_data;
						selected_school_email = schoolEmail;
						console.log('selected_school_email===464', selected_school_email);
						previous_screening_title_value[1] = "Screening Pie Chart";
						
						drilling_screening_to_abnormalities_pie(a);
					}else if(onClickFn == "drill_screening_to_students_pie"){
						
						search_arr =  a.data.label;
						console.log("drill_screening_to_students_pie==1060",search_arr);
						drill_screening_to_students_pie(search_arr);
					}else{
						index = onClickFn;
						
						if(index == 1){
							drilling_screening_to_abnormalities_pie(a);
						}else if (index == 2){
							search_arr[0] = previous_screening_search[3];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_screening_to_students_pie(search_arr);
						}
					}
					
				}
			}
	      
	});
}

/*******************************************************************
 *
 * Helper : Draw screening pie
 *
 */
 
function draw_screening_pie()
{
	if(screening_data == 1)
	{
		$("#pie_screening").append('No positive values to dispaly<br><br>');
	}
	else
	{
		screening_pie("Screening Pie Chart", screening_data, "drilling_screening_to_abnormalities_pie");
	}
}

/*******************************************************************
 *
 * Helper : Screening Pie - drill to abnormalities
 *
 *
 */
 
function drilling_screening_to_abnormalities_pie(pie_data)
{	
	$.ajax({
		url: 'drilling_screening_to_abnormalities_pie',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "schoolEmail":selected_school_email/*, "today_date" : today_date, "screening_pie_span" : screening_pie_span*/},
		success: function (data) {
			console.log('drilling_screening_to_abnormalities_pie====525==',data);
			var content = $.parseJSON(data);
			console.log('CONTENT', content);
			$("#pie_screening").empty();
			$("#pie_screening").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "1"> Back </button>');
			//screening_navigation.push(pie_data.data.label);
			screening_pie(pie_data.data.label, content, "drill_screening_to_students_pie");
			
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});
}

/*******************************************************************
 *
 * Helper : Screening Pie - drill to students
 *
 *
 */
 
function drill_screening_to_students_pie(search_arr)
{
	
	$.ajax({
		url : 'drill_screening_to_students_pie',
		type: 'POST',
		data: {"data" : search_arr, "schoolEmail":selected_school_email/*, "today_date" : today_date, "screening_pie_span" : screening_pie_span*/},
		success: function (data) {
			$("#ehr_data").val(data);
		
			$("#ehr_navigation").val(search_arr);
			
			$("#ehr_form").submit();
			
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
}

/*******************************************************************
 *
 * Helper : Screening Pie - back button functionality
 *
 *
 */
 
$(document).on("click",'#screening_back_btn',function(e){
	
	var index = $(this).attr("ind");
	$( "#pie_screening" ).empty();
	
	if(index>1)
	{
		var ind = index - 1;
		$("#pie_screening").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "' + ind + '"> Back </button>');
	}
	
	screening_pie(previous_screening_title_value[index], previous_screening_a_value[index], index);
});

draw_all_screening_pie();




$('#screening_pie_span').change(function(e){
	today_date = $('#set_date').val();
	screening_pie_span = $('#screening_pie_span').val();
	$( "#screening_pies" ).hide();
	$("#loading_screening_pie").show();
	if(screening_pie_span != "Yearly" )
	{
		$("#refresh_screening_data").hide();
	}else{
		$("#refresh_screening_data").show();
	}
	console.log('error', today_date);
	console.log('error', screening_pie_span);
	$.ajax({
		url: 'update_screening_pie',
		type: 'POST',
		data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {			
			$("#loading_screening_pie").hide();
			$( "#screening_report" ).show();
			$( "#screening_report" ).empty();
			data = $.parseJSON(data);
			screening_data = $.parseJSON(data.screening_report);
			console.log(screening_data);
			init_screening_pie(screening_data);
			draw_all_screening_pie();			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});


function screening_pie_all(heading, screening_data, onClickFn){
	var pie = new d3pie("screening_report", {
		header: {
			title: {
				text: screening_navigation.join(" / "),
				"fontSize": 18,
				"font": "open sans"
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth: 400//600
	    },
	    data: {
	      content: screening_data
	    },
	    labels: {
			outer:
			{
				"pieDistance": 20
			},
	        inner: {
	            format: "value",
				},
		/*	mainLabel: {
				color: "#fff",
				font: "arial",
				fontSize: 15
				},*/
			 truncation: {
				enabled: true,
				truncateLength:10
			} 
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					d3.select(this).on('click',null);
					if(onClickFn == "drill_down_screening_to_abnormalities"){
						console.log(a);
						previous_screening_a_value[1] = screening_data;
						previous_screening_title_value[1] = "Screening Pie Chart";
						console.log(previous_screening_a_value);
						drill_down_screening_to_abnormalities(a);
					}else if(onClickFn == "drill_down_screening_to_districts"){
						console.log(a);
						previous_screening_a_value[2] = screening_data;
						previous_screening_title_value[2] = heading;
						previous_screening_search[2] = heading;
						console.log(previous_screening_a_value);
						drill_down_screening_to_districts(a);
					}else if(onClickFn == "drill_down_screening_to_schools"){
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						previous_screening_a_value[3] = screening_data;
						previous_screening_title_value[3] = heading;
						previous_screening_search[3] = heading;
						console.log(previous_screening_a_value);
						search_arr[0] = previous_screening_search[3];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_screening_to_schools(search_arr);
					}else if(onClickFn == "drill_down_screening_to_students"){
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						search_arr[0] = previous_screening_search[3];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_screening_to_students(search_arr);
					}else{
						index = onClickFn;
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						//previous_screening_a_value[index] = previous_screening_a_value[index];
						//previous_screening_title_value[index] = previous_screening_title_value[index];
						//previous_screening_search[index] = previous_screening_title_value[index];
						console.log("value from previous function -------------------------------------------");
						//console.log(previous_screening_a_value);

						if(index == 1){
							drill_down_screening_to_abnormalities(a);
						}else if (index == 2){
							drill_down_screening_to_districts(a);
						}else if (index == 3){
							search_arr[0] = previous_screening_search[3];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_screening_to_schools(search_arr);
						}
					}
					
				}
			}
	      
	});
}

function draw_all_screening_pie(){
	if(screening_data == 1){
		console.log('in false of abbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
		$("#screening_report").append('No positive values to dispaly<br><br>');
	}else{
		screening_navigation.push("Screening Pie Chart");
		screening_pie_all("Screening Pie Chart", screening_data, "drill_down_screening_to_abnormalities");
	
	}
}

function drill_down_screening_to_abnormalities(pie_data){
today_date = $('#set_date').val();
	$.ajax({
		url: 'drilling_screening_to_abnormalities',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#screening_report" ).empty();
			$("#screening_report").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "1"> Back </button>');
			screening_navigation.push(pie_data.data.label);
			screening_pie_all(pie_data.data.label, content, "drill_down_screening_to_districts");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}

function drill_down_screening_to_districts(pie_data){

	$.ajax({
		url: 'drilling_screening_to_districts',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#screening_report" ).empty();
			$("#screening_report").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "2"> Back </button>');
			screening_navigation.push(pie_data.data.label);
			screening_pie_all(pie_data.data.label, content, "drill_down_screening_to_schools");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}

	function drill_down_screening_to_schools(pie_data){
	console.log("in school pieeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee---------------------pie data");
	console.log(pie_data);
	$.ajax({
		url: 'drilling_screening_to_schools',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#screening_report" ).empty();
			$("#screening_report").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "3"> Back </button>');
			screening_navigation.push(pie_data[1]);
			screening_pie_all(pie_data[1], content, "drill_down_screening_to_students");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}

	function drill_down_screening_to_students(pie_data){
		

		$.ajax({
			url: 'drill_down_screening_to_students',
			type: 'POST',
			data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
			success: function (data) {
				console.log(data);
				$("#ehr_data").val(data);
				screening_navigation.push(pie_data[1]);
				$("#ehr_navigation").val(screening_navigation.join(" / "));
				//window.location = "drill_down_screening_to_students_load_ehr/"+data;
				//alert(data);
				
				$("#ehr_form").submit();
				
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
		}
		
		
	/*******************************************************************
 *
 * Helper : Sanitation Report 
 *
 *
 */
 
function draw_sanitation_report_table(result)
{
	   /*if(result)	
	   {			   
		  */

		  $('#sanitation_report_table').html('<div class="row col-md-12"><div id="campus" class="col-md-4"></div><div id="kitchen" class="col-md-4"></div><div id="toilets" class="col-md-4"></div></div><div class="row col-md-12" ><div id="water_Supply_Condition" class="col-md-3"></div><div id="dormitories" class="col-md-3"></div><div id="store" class="col-md-3"></div><div id="waste_management" class="col-md-3"></div></div><div class="row col-md-10"><div id="water" class="col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div><div class="col-md-4" id="campus_attachments"></div><div class="col-md-4" id="kitchen_attachments"></div><div class="col-md-4" id="toilets_attachments"></div><div class="col-md-4" id="dormitories_attachments"></div>');
		 //use chart class in above table
		  var campus           = result.campus;
		
		  var toilets   = result.toilets;
		  var kitchen        = result.kitchen;
		  var dormitories            = result.dormitories;
		  var store 				 = result.store;
		  
		  var waste_management   = result.waste_management;
		  var water   = result.water;
		  var water_Supply_Condition   = result.water_Supply_Condition;
		  var campus_attachments   = result.campus_attachments;
		  var toilets_attachments   = result.toilets_attachments;
		  var kitchen_attachments   = result.kitchen_attachments;
		  var dormitories_attachments   = result.dormitories_attachments;
		

		  /*var external_files     = result.external_attachments;*/
		  
		  //console.log("external_files==>",external_files);
		  
	
		// hand wash
		campus = $.parseJSON(campus);
			if ($("#campus").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered" id="campus_table"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>';
				
				for (var item in campus) {
				  if (campus.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+campus[item].label+'</td><td>'+campus[item].value+'</td></tr>';
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#campus").html(table)
			}

			
			$('#campus').prepend('<div class="">campus</div>');
			// cleanliness
			kitchen = $.parseJSON(kitchen);
			if ($("#kitchen").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>test</th><th>Value</th></tr></thead><tbody>'
				for (var item in kitchen) {
				  if (kitchen.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+kitchen[item].label+'</td><td>'+kitchen[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#kitchen").html(table)
			}
			
			$('#kitchen').prepend('<div class="spec">kitchen</div>');
			
			// water dispensaries
			toilets = $.parseJSON(toilets);
			if ($("#toilets").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;"><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in toilets) {
				  if (toilets.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+toilets[item].label+'</td><td>'+toilets[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#toilets").html(table)
			}
			
			$('#toilets').prepend('<div class="spec">Toilets</div>');
			
			// dormitories
			dormitories = $.parseJSON(dormitories);
			
			if ($("#dormitories").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in dormitories) {
				  if (dormitories.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+dormitories[item].label+'</td><td>'+dormitories[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#dormitories").html(table)
			}
			var campus_daily = $('#daily').prop('checked');
				//debugger;
				if(campus_daily == false){
					$("#campus,#toilets,#kitchen,#campus_attachments,#kitchen_attachments,#toilets_attachments").addClass("hide");
				}else{
					$("#campus,#toilets,#kitchen,#campus_attachments,#kitchen_attachments,#toilets_attachments").removeClass("hide");
				}
			
			$('#dormitories').prepend('<div class="spec">dormitories</div>');
			// Store
			store = $.parseJSON(store);
			if ($("#store").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in store) {
				  if (store.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+store[item].label+'</td><td>'+store[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#store").html(table)
			}
				
		$('#store').prepend('<div class="spec">Store</div>');
		//Waster Management
		waste_management = $.parseJSON(waste_management);
			if ($("#waste_management").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in waste_management) {
				  if (waste_management.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+waste_management[item].label+'</td><td>'+waste_management[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#waste_management").html(table)
			}
				
		$('#waste_management').prepend('<div class="spec">Waste Management</div>');

		//water_supply_condition
		water_Supply_Condition = $.parseJSON(water_Supply_Condition);
			if ($("#water_Supply_Condition").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in water_Supply_Condition) {
				  if (water_Supply_Condition.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+water_Supply_Condition[item].label+'</td><td>'+water_Supply_Condition[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#water_Supply_Condition").html(table)
			}
				
		$('#water_Supply_Condition').prepend('<div class="spec">Wate Supply Condition</div>');
			
			var weekly_submit = $('#weekly').prop('checked');
				//debugger;
				if(weekly_submit == false){
					$("#water_Supply_Condition,#waste_management,#dormitories,#store,#dormitories_attachments").addClass("hide");
				}else{
					$("#water_Supply_Condition,#waste_management,#dormitories,#store,#dormitories_attachments").removeClass("hide");
				}

		//mothly water
		
		water = $.parseJSON(water);
			if ($("#water").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in water) {
				  if (water.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+water[item].label+'</td><td>'+water[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#water").html(table)
			}
			
			$('#water').prepend('<div class="spec">Water</div>');

			var mothly_get = $('#monthly').prop('checked');
				//debugger;
				if(mothly_get == false){
					$("#water").addClass("hide");
				}else{
					$("#water").removeClass("hide");
				}

		
		// campus external files
		campus_attachments = $.parseJSON(campus_attachments);
		//console.log(campus_attachments);	
		var table = '<div style="overflow-y: auto; height:200px;" ><table class=" table table-bordered"><thead><tr><th>Campus Attachments <span class="attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
		var length = Object.keys(campus_attachments).length;
		if(length > 0)
		{
		for(var item in campus_attachments)
		{
	      table = table + '<tr><td><a href="<?php echo URLCustomer;?>'+campus_attachments[item].file_path+'" rel="prettyPhoto[gal]">'+campus_attachments[item].file_client_name+'</a></td></tr>'
		  
		}
		}
		else
		{
	      table = table + '<tr><td>No attachments </td></tr>'
		}
		
		table = table + '</tbody></table></div>';
		
		$("#campus_attachments").html(table)
		$('.attach_count').text(length);
		
		$("a[rel^='prettyPhoto']").prettyPhoto();

		
		//kitchen_attachments
		kitchen_attachments = $.parseJSON(kitchen_attachments);
		console.log(kitchen_attachments);	
		var table = '<div style="overflow-y: auto; height:200px;" ><table class=" table table-bordered"><thead><tr><th>Kitchen Attachments <span class="attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
		var length = Object.keys(kitchen_attachments).length;
		if(length > 0)
		{
		for(var item in kitchen_attachments)
		{
	      table = table + '<tr><td><a href="<?php echo URLCustomer;?>'+kitchen_attachments[item].file_path+'" rel="prettyPhoto[gal]">'+kitchen_attachments[item].file_client_name+'</a></td></tr>'
		  
		}
		}
		else
		{
	      table = table + '<tr><td>No attachments </td></tr>'
		}
		
		table = table + '</tbody></table></div>';
		
		$("#kitchen_attachments").html(table)
		$('.attach_count').text(length);
		
		$("a[rel^='prettyPhoto']").prettyPhoto();

		// toilets external files
		toilets_attachments = $.parseJSON(toilets_attachments);
		console.log(toilets_attachments);	
		var table = '<div style="overflow-y: auto; height:200px;" ><table class=" table table-bordered"><thead><tr><th>Toilets Attachments <span class="attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
		var length = Object.keys(toilets_attachments).length;
		if(length > 0)
		{
		for(var item in toilets_attachments)
		{
	      table = table + '<tr><td><a href="<?php echo URLCustomer;?>'+toilets_attachments[item].file_path+'" rel="prettyPhoto[gal]">'+toilets_attachments[item].file_client_name+'</a></td></tr>'
		  
		}
		}
		else
		{
	      table = table + '<tr><td>No attachments </td></tr>'
		}
		
		table = table + '</tbody></table></div>';
		
		$("#toilets_attachments").html(table)
		$('.attach_count').text(length);
		
		$("a[rel^='prettyPhoto']").prettyPhoto();

		dormitories_attachments = $.parseJSON(dormitories_attachments);
		//console.log(dormitories_attachments);	
		var table = '<div style="overflow-y: auto; height:200px;" ><table class=" table table-bordered"><thead><tr><th>Dormitories Attachments <span class="attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
		var length = Object.keys(dormitories_attachments).length;
		if(length > 0)
		{
		for(var item in dormitories_attachments)
		{
	      table = table + '<tr><td><a href="<?php echo URLCustomer;?>'+dormitories_attachments[item].file_path+'" rel="prettyPhoto[gal]">'+dormitories_attachments[item].file_client_name+'</a></td></tr>'
		  
		}
		}
		else
		{
	      table = table + '<tr><td>No attachments </td></tr>'
		}
		
		table = table + '</tbody></table></div>';
		
		$("#dormitories_attachments").html(table)
		$('.attach_count').text(length);
		
		$("a[rel^='prettyPhoto']").prettyPhoto();
			
	
	 /*  }
	   else
	   {
		   $('#sanitation_report_table').html('<br><center><label id="sanitation_report_note">No sanitation report data available</label></center>');
	   }*/
	
}
	$('.abs_submitted_schools_list').click(function(){
	
	if(absent_submitted_schools_list!=null)
	{
		if(absent_submitted_schools_list['school']!="")
		{
			$('#absent_sent_school_modal_body').empty();
			var table="";
			var tr="";
			table += "<table class='table table-bordered'><thead><tr><th>S.No</th><th> District </th><th> School Name </th></tr></thead><tbody>";
			for(var i=0;i<absent_submitted_schools_list['school'].length;i++)
			{
				var j=i+1;
				table+= "<tr><td>"+j+"</td><td>"+absent_submitted_schools_list['district'][i]+"</td><td>"+absent_submitted_schools_list['school'][i]+"</td></tr>"
			}
			table += "</tbody></table>";
			$(table).appendTo('#absent_sent_school_modal_body');
		}
		else
		{
			table+="No Schools";
			$(table).appendTo('#absent_sent_school_modal_body');
		}
	}
	else
	{
		table+="No Schools";
		$(table).appendTo('#absent_sent_school_modal_body');
	}
	$('#absent_sent_school_modal').modal('show');
})

// Absent list not sent schools list
$('.abs_not_submitted_schools_list').click(function(){
	
	if(absent_not_submitted_schools_list!=null)
	{
		if(absent_not_submitted_schools_list['school']!="")
		{
			$('#absent_not_sent_school_modal_body').empty();
			var table="";
			var tr="";
			table += "<table class='table table-bordered'><thead><tr><th>S.No</th><th> District </th><th> School Name </th></tr></thead><tbody>";
			for(var i=0;i<absent_not_submitted_schools_list['school'].length;i++)
			{
				var j=i+1;
				table+= "<tr><td>"+j+"</td><td>"+absent_not_submitted_schools_list['district'][i]+"</td><td>"+absent_not_submitted_schools_list['school'][i]+"</td></tr>"
			}
			table += "</tbody></table>";
			console.log(table)
			$(table).appendTo('#absent_not_sent_school_modal_body');
		}
		else
		{
			table+="No Schools";
			$(table).appendTo('#absent_not_sent_school_modal_body');
		}
	}
	else
	{
		 table+="No Schools";
		 $(table).appendTo('#absent_not_sent_school_modal_body');
	}
	$('#absent_not_sent_school_modal').modal('show');
})

// Absent list sent schools list download
$('#absent_sent_school_download').click(function(){
	
	if(absent_submitted_schools_list!=null)
	{
       $.ajax({
		url : 'download_absent_sent_schools_list',
		type: 'POST',
		data: {"data" : absent_submitted_schools_list,"today_date" : today_date},
		success: function (data) {
			window.location = data;
			$("#absent_sent_school_modal").modal('hide');
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}
	else
	{

	}
})

// Absent list not sent schools list download
$('#absent_not_sent_school_download').click(function(){
	
	if(absent_not_submitted_schools_list!=null)
	{
       $.ajax({
		url : 'download_absent_not_sent_schools_list',
		type: 'POST',
		data: {"data" : absent_not_submitted_schools_list,"today_date" : today_date},
		success: function (data) {
			window.location = data;
			$("#absent_not_sent_school_modal").modal('hide');
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}
	else
	{

	}
	})
function absent_pie(heading, data, onClickFn){
	var pie = new d3pie("pie_absent", {
		header: {
			title: {
				text: absent_navigation.join(" / ")
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth: 400
	    },
	    data: {
	      content: data
	    },
	    labels: {
	        inner: {
	            format: "value"
	        }
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					d3.select(this).on('click',null);
					if(onClickFn == "drill_down_absent_to_districts"){
						console.log(a);
						previous_absent_a_value[1] = absent_data;
						previous_absent_title_value[1] = today_date;
						console.log(previous_absent_a_value);
						console.log("11111111111111111111111111111111111111111");
						drill_down_absent_to_districts(a);
					}else if(onClickFn == "drill_down_absent_to_schools"){
						console.log(a);
						previous_absent_a_value[2] = data;
						previous_absent_title_value[2] = heading;
						previous_absent_search[2] = heading;
						console.log(previous_absent_a_value);
						search_arr[0] = previous_absent_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						console.log("calling school funnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn");
						drill_down_absent_to_schools(search_arr);
					}else if(onClickFn == "drill_down_absent_to_students"){
						search_arr[0] = previous_absent_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						console.log("calling student funcccccccccccccccccccccccccc");
						drill_down_absent_to_students(search_arr);
					}else{
						index = onClickFn;
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						//previous_screening_a_value[index] = previous_screening_a_value[index];
						//previous_screening_title_value[index] = previous_screening_title_value[index];
						//previous_screening_search[index] = previous_screening_title_value[index];
						console.log("value from previous function -------------------------------------------");
						//console.log(previous_screening_a_value);
		
						if (index == 1){
							drill_down_absent_to_districts(a);
						}else if (index == 2){
							search_arr[0] = previous_absent_search[2];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_absent_to_schools(search_arr);
						}
						
					}
					
				}
			}
	      
		});
}
draw_absent_pie();
function draw_absent_pie(){
	if(absent_data == 1){
		$("#pie_absent").append('No positive values to dispaly');
	}else{
		absent_navigation.push(today_date);
	absent_pie(today_date,absent_data,"drill_down_absent_to_districts");
}
}

function drill_down_absent_to_districts(pie_data){
	console.log(pie_data);

	$.ajax({
		url: 'drilldown_absent_to_districts',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data),"today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie_absent" ).empty();
			//$("#pie_absent").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_absent_back(1);"> Back </button>');
			$("#pie_absent").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "1"> Back </button>');

			absent_navigation.push(pie_data.data.label);
			absent_pie(pie_data.data.label,content,"drill_down_absent_to_schools");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}

function drill_down_absent_to_schools(pie_data){
	console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaschooooooooooooooooooooooooooooooooool");
	console.log(pie_data);
	$.ajax({
		url: 'drilling_absent_to_schools',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data),"today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			var content = $.parseJSON(data);
			$( "#pie_absent" ).empty();
			//$("#pie_absent").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_absent_back(2);"> Back </button>');
			$("#pie_absent").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "2"> Back </button>');

			absent_navigation.push(pie_data[1]);
			absent_pie(pie_data[1],content,"drill_down_absent_to_students");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function drill_down_absent_to_students(pie_data){

	$.ajax({
		url: 'drill_down_absent_to_students',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			console.log(data);
			$("#ehr_data_for_absent").val(data);
			absent_navigation.push(pie_data[1]);
			$("#ehr_navigation_for_absent").val(absent_navigation.join(" / "));
			//window.location = "drill_down_screening_to_students_load_ehr/"+data;
			//alert(data);
			
			$("#ehr_form_for_absent").submit();
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

$(document).on("click",'#absent_back_btn',function(e){
		var index = $(this).attr("ind");
		console.log("in back functionnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn-----------");
		console.log(index);
		$( "#pie_absent" ).empty();
		if(index>1){
			var ind = index - 1;
		$("#pie_absent").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "' + ind + '"> Back </button>');
		}
		absent_navigation.pop();
		absent_pie(previous_absent_title_value[index], previous_absent_a_value[index], index);
});

	function display_sanitation_graph(result){
	
	var resultLength =  Object.values(result).length;
	
	if(resultLength > 0)
	{
		data_table = '<table id="datatable_fixed_column" class="table table-striped table-bordered"><thead><th></th><th colspan="3">No.of submitted schools</th></tr> <th>Cleanliness in below Area </th><th>Once </th><th>Twice </th><th>Thrice </th> </thead> <tbody>';

		data_table = data_table + '<tr><td>Campus</td><td>'+result.once+ '</td><td>'+result.twice + '</td><td>'+result.thrice + '</td></tr><tr><td>Kitchen</td><td>'+result.kit_once+ '</td><td>'+result.kit_twice + '</td><td>'+result.kit_thrice + '</td></tr><tr><td>Dining Halls</td><td>'+result.dininghall_once+ '</td><td>'+result.dininghall_twice + '</td><td>'+result.dininghall_thrice + '</td></tr><tr><td>Toilets</td><td>'+result.toilets_once+ '</td><td>'+result.toilets_twice + '</td><td>'+result.toilets_thrice + '</td></tr><tr><td>Wellness Centre</td><td>'+result.wellness_centre_once+ '</td><td>'+result.wellness_centre_twice + '</td><td>'+result.wellness_centre_thrice + '</td></tr><tr><td>Dormitories</td><td>'+result.dormitories_once+ '</td><td>'+result.dormitories_twice + '</td><td>'+result.dormitories_thrice + '</td></tr><tr><td>Store</td><td>'+result.store_once+ '</td><td>'+result.store_twice + '</td><td>'+result.store_thrice + '</td></tr><tr><td>Water</td><td>'+result.water_once+ '</td><td>'+result.water_twice + '</td><td>'+result.water_thrice + '</td></tr>';

		$("#sanitation_report_counts").html(data_table);

		/*$(document).ready(function(){*/
	var dom = document.getElementById("chart_details");

	var myChart = echarts.init(dom);
	var app = {};
	option = null;
	option = {
		legend: {                   
					/*right: 'center',*/
					bottom: -5,
					/*orient: 'horizontal'*/    
				},
		tooltip: {},
		dataset: {
			dimensions: ['Cleanliness', 'Campus', 'Kitchen','Toilets', 'Dining Halls','Wellness Centre','Dormitories','Store','Water'],
			 source: [
				{Cleanliness: 'Once', 'Campus': result.once, 'Kitchen': result.kit_once,'Toilets':result.toilets_once, 'Dining Halls': result.dininghall_once,'Wellness Centre':result.wellness_centre_once,'Dormitories':result.dormitories_once,'Store':result.store_once,'Water':result.water_once},
				{Cleanliness: 'Twice', 'Campus': result.twice, 'Kitchen': result.kit_twice,'Toilets':result.toilets_twice, 'Dining Halls': result.dininghall_twice,'Wellness Centre':result.wellness_centre_twice,'Dormitories':result.dormitories_twice,'Store':result.store_twice,'Water':result.water_twice},
				{Cleanliness: 'Thrice', 'Campus': result.thrice, 'Kitchen': result.kit_thrice,'Toilets':result.toilets_thrice, 'Dining Halls': result.dininghall_thrice,'Wellness Centre':result.wellness_centre_thrice,'Dormitories':result.dormitories_thrice,'Store':result.store_thrice,'Water':result.water_thrice}
			   /* {product: 'Walnut Brownie', 'Campus': 72.4, 'Kitchen': 53.9, 'Dormitores': 39.1}*/
			]
		},
		xAxis: {type: 'category'},
		yAxis: {},
		// Declare several bar series, each will be mapped
		// to a column of dataset.source by default.
		series: [
			{type: 'bar'},
			{type: 'bar'},
			{type: 'bar'},
			{type: 'bar'},
			{type: 'bar'},
			{type: 'bar'},
			{type: 'bar'},
			{type: 'bar'},
		]
	};

	if (option && typeof option === "object") {
		myChart.setOption(option, true);
	}
	}
	else{
		$('#sanitation_report_table').append('No sanitation report submitted Today ');
	}

		
	
			
			
			//=====================================================================================================
			/*}else{
				$("#sanitation_report_counts").html('<h5>No students to display for this school</h5>');
			}*/
	}


	function display_yes_no_table(result)
	{
		data_table = '<table id="datatable_fixed_col" class="table table-striped table-bordered" ><thead> <th>Item </th><th>Yes </th><th>No</th> </thead> <tbody>';
		data_table = data_table + '<tr><td>Animal Aroun the Campus</td><td>'+result.animal_yes+ '</td><td>'+result.animal_no + '</td></tr><tr><td>Any Damages To The Toilets</td><td>'+result.damages_toilets_yes+ '</td><td>'+result.damages_toilets_no + '</td></tr><tr><td>Daily Menu Followed</td><td>'+result.kitchen_menu_yes_count+ '</td><td>'+result.kitchen_menu_no_count + '</td></tr><tr><td>Utensils Cleanliness</td><td>'+result.kitchen_Utensils_yes_count+ '</td><td>'+result.kitchen_Utensils_no_count + '</td></tr><tr><td>Hand Gloves Used By Serving People</td><td>'+result.kitchen_hand_gloves_yes_count+ '</td><td>'+result.kitchen_hand_gloves_no_count + '</td></tr><tr><td>Staffmembers Tasty Food Before Serving Meals</td><td>'+result.kitchen_tasty_food_yes_count+ '</td><td>'+result.kitchen_tasty_food_no_count + '</td></tr><tr><td>RO Plant</td><td>'+result.ro_yes_count+ '</td><td>'+result.ro_no_count + '</td></tr><tr><td>Bore Water</td><td>'+result.bore_yes_count+ '</td><td>'+result.bore_no_count + '</td></tr><tr><td>No Plant Working</td><td>'+result.noplant_yes_count+ '</td><td>'+result.noplant_no_count + '</td></tr><tr><td>Water Tank Cleaning</td><td>'+result.watertank_yes_count+ '</td><td>'+result.watertank_no_count + '</td></tr><tr><td>Any Damages To Beds</td><td>'+result.beddamges_yes_count+ '</td><td>'+result.beddamges_no_count + '</td></tr><tr><td>Any Default Items Issued</td><td>'+result.defaultitem_yes_count+ '</td><td>'+result.defaultitem_no_count + '</td></tr><tr><td>Separate dumping of Inorganic waste</td><td>'+result.inorganic_yes_count+ '</td><td>'+result.inorganic_no_count + '</td></tr><tr><td>Separate dumping of Organic waste</td><td>'+result.organic_yes_count+ '</td><td>'+result.organic_no_count + '</td></tr><tr><td>Dustbins</td><td>'+result.dustbins_yes_count+ '</td><td>'+result.dustbins_no_count + '</td></tr>';
		$("#show_yes_no_count").html(data_table);

		var trace1 = {
		  x: ['Animal Aroun the Campus', 'Damages To The Toilets', 'Daily Menu Followed', 'Utensils Cleanliness', 'Hand Gloves Used By Serving People','Staffmembers Tasty','RO Plant','Bore Water','No Plant Working','Water Tank Cleaning','Any Damages To Beds','Any Default Items Issued','Inorganic waste','Organic waste','Dustbins'], 
		  y: [result.animal_yes, result.damages_toilets_yes, result.kitchen_menu_yes_count, result.kitchen_Utensils_yes_count,result.kitchen_hand_gloves_yes_count , result.kitchen_tasty_food_yes_count,result.ro_yes_count,result.bore_yes_count,result.noplant_yes_count,result.watertank_yes_count,result.beddamges_yes_count,result.defaultitem_yes_count,result.inorganic_yes_count,result.organic_yes_count,result.dustbins_yes_count],  
		  name: 'Yes', 
		  marker: {color: '#E3AF73'}, 
		  type: 'bar'
		};

		var trace2 = {
		  x: ['Animal Aroun the Campus', 'Any Damages To The Toilets', 'Daily Menu Followed', 'Utensils Cleanliness', 'Hand Gloves Used By Serving People','Staffmembers Tasty','RO Plant','Bore Water','No Plant Working','Water Tank Cleaning','Any Damages To Beds','Any Default Items Issued','Inorganic waste','Organic waste','Dustbins'], 
		  y: [result.animal_no, result.damages_toilets_no, result.kitchen_menu_no_count, result.kitchen_Utensils_no_count, result.kitchen_hand_gloves_no_count , result.kitchen_tasty_food_no_count,result.ro_no_count,result.bore_no_count,result.noplant_no_count,result.watertank_no_count,result.beddamges_no_count,result.defaultitem_no_count,result.inorganic_no_count,result.organic_no_count,result.dustbins_no_count], 
		  name: 'No', 
		  marker: {color: '#C93633'}, 
		  type: 'bar'
		};

		var data = [trace1, trace2];
		
		var layout = {barmode: 'group'};

		Plotly.newPlot('myDiv', data, layout);
	}


	//===================== auto load function====
function set_date_xl(){
	var xl_date = $("#set_date_xl").val();
		
		$.ajax({
		url  : 'get_rhso_submitted_report_count',
		type : 'POST',
		data : { "xl_date" : xl_date},
		success: function (data) {
				//data = data.trim();
				
				var xl_data = JSON.parse(data);
				
				if(xl_data == false){
					$("#xl_count").text("zero");
					console.log("no data");

				}else{
				var identity = xl_data[0]['identity'].length;
				
				$("#xl_count").text(identity);
				 display_data_table(xl_data);
				console.log('datattttttttttttttttttt',xl_data);
			}

			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
}
set_date_xl();
$("#set_date_btn_xl").click(function(){
		var xl_date = $("#set_date_xl").val();
		
		$.ajax({
		url  : 'get_rhso_submitted_report_count',
		type : 'POST',
		data : { "xl_date" : xl_date},
		success: function (data) {
				//data = data.trim();
				
				var xl_data = JSON.parse(data);
				
				if(xl_data == false){
					$("#xl_count").text("zero");
					console.log("no data");

				}else{
				var identity = xl_data[0]['identity'].length;
				
				$("#xl_count").text(identity);
				 display_data_table(xl_data);
				console.log('datattttttttttttttttttt',xl_data);
			}

			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});

	});
//================data table for list of schools
function display_data_table(xl_data){
    if(xl_data.length > 0){
     
     data_table = '<table class ="table table-striped table-striped" width="100%"><thead><th>Name of the school</th><th>view</th></thead> <tbody>'

      $.each(xl_data, function() {
        //console.log(this.doc_data.widget_data["page2"]['Personal Information']['AD No']);
        for (i = 0;i < this.identity.length;i++){
          var xl_date = $("#set_date_xl").val();
         
          if(xl_data[0].date === xl_date){
        data_table = data_table +'<tr>';
    
        
        /*data_table = data_table + '<tr><th>Name and Designation of Inspecting Officer</th><td>'+this.identity[i]['name and designation of inspecting officer'] + '</td></tr>';*/
      
        data_table = data_table + '<td id="schoolName">'+this.identity[i]['name of the school/district/region'] + '</td>';

        

        data_table = data_table + '<td><button class="btn btn-primary btn-xs report_by_schoolName" id="abnormality_label_btn" value="'+this.identity[i]['name of the school/district/region']+'">Show</button></td>';
        
         
      

        data_table = data_table + '</tr>';

      }
        }


          
      });




      data_table = data_table + '</tbody></table>';

      $("#stud_report").html(data_table);
  }
   $(".report_by_schoolName").each(function(){
			 	$(this).click(function (){
	        		var schoolName = $(this).val();
	        		var todayDate = $('#set_date_xl').val();
	        	
	        		  $.ajax({
						url: 'download_rhso_report_xl_sheet',
						type: 'POST',
						data: {"schoolName" : schoolName, "todayDate":todayDate},
		
						success: function (data) {
							
							 result = $.parseJSON(data);
					          console.log('resulttttttttttttt',result);
					          display_rhso_table(result);
							
							},
					    error:function(XMLHttpRequest, textStatus, errorThrown)
						{
						 console.log('error', errorThrown);
					    }
						});
					
				});
			 });
}

function display_rhso_table(result){
    if(result.length > 0){

     data_table = '<table id="datatable_fixed_column_rhso" class ="table table-striped table-hover table-bordered" width="100%"><thead>';

      $.each(result, function() {
       
        for (i = 0;i < this.identity.length;i++){
          school_name = $('.report_by_schoolName').val();
         

      
         
          if(this.identity[i]['name of the school/district/region'] === school_name){
          	
        data_table = data_table ;
       
        
       
      data_table = data_table + '<tr><th><h2>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; <strong>RHSO HEALTH&HYGIENE  SANITATION AND FOOD INSPECTION REPORT</strong></h2></th></tr>';  
      data_table = data_table + '<tr><th>Name and Designation of Inspecting Officer</th><td>'+this.identity[i]["name and designation of inspecting officer"] + '</td></tr>';

      data_table = data_table + '<tr><th>Name of the school</th><td>'+this.identity[i]["name of the school/district/region"] + '</td></tr>'; 
      data_table = data_table + '<tr><th>Date of visiting</th><td>'+this.identity[i]["date of visiting"] + '</td></tr>';
      data_table = data_table + '<tr><th>Principal name and number</th><td>'+this.identity[i]["principal name and number"] + '</td></tr>';
      data_table = data_table + '<tr><th>HS name and number</th><td>'+this.identity[i]["hs name and number"] + '</td></tr>';
      data_table = data_table + '<tr><th>ACT name and number</th><td>'+this.identity[i]["act name and number"] + '</td></tr>';
      data_table = data_table + '<tr><th>Total strenth</th><td>'+this.identity[i]["total strenth"] + '</td></tr>';


      data_table = data_table + '<tr><th><strong>HEALTH AND HYGIENE</strong></th><th><strong>Status</strong></th><th><strong>Remarks</strong></th></tr>';

     
      /*var test = this.identity[i];
      var testarray =[];
      for (var key in test){
      	console.log("tstetttttttttttttttttttttttttttttttt",test[key]);
      	var suman =test[key].split(",");
      	testarray.push(suman);
      }
      console.log("testinf arerara  fadfsaf a",testarray);

      debugger;*/
      var abc = this.identity[i]["info to panacea"].split(",");
      var wellness = this.identity[i]["wellness centre with amenities"].split(",");
       var table  = this.identity[i]["table maintanance"].split(",")
      var first_aid_kit = this.identity[i]["first aid kit"].split(",")
      var medical_equipments = this.identity[i]["medical equipments"].split(",")
      var medicines_Emergency = this.identity[i]["medicines in general/emergency"].split(",")
      var medical_records = this.identity[i]["medical records"].split(",")
      var chronic_diseases = this.identity[i]["chronic diseases/special care"].split(",") 
      var medical_screening = this.identity[i]["medical screening"].split(",")
      var wellness_club = this.identity[i]["wellness club/healthy tuesday"].split(",") 
      var flow_charts = this.identity[i]["flow charts"].split(",")
      var hand_wash = this.identity[i]["hand wash"].split(",")
      var ro_plant = this.identity[i]["ro plant/drining water"].split(",")
      var incinetators = this.identity[i]["incinetators"].split(",")
      var awareness_program = this.identity[i]["awareness program"].split(",")
      var sick_diet = this.identity[i]["sick diet"].split(",")
      var school_Campus = this.identity[i]["school campus"].split(",")
      var school_Building = this.identity[i]["school building and class rooms"].split(",")
      var dormitory = this.identity[i]["dormitory"].split(",")
      var kitchen_Dining_hall = this.identity[i]["kitchen and dining hall"].split(",")
      var cooking_utensils = this.identity[i]["cooking utensils"].split(",")
      var personal_cooks = this.identity[i]["personal hygiene of food handlers and cooks"].split(",")
      var toilets_both = this.identity[i]["toilets and both rooms"].split(",")
      var washing = this.identity[i]["washing area"].split(",")
      var drinage = this.identity[i]["drinage area"].split(",")
      var clening_water = this.identity[i]["clening water tanks"].split(",")
      var sanitizers = this.identity[i]["sanitizers"].split(",")
      var garbage_pots_Disposal = this.identity[i]["garbage pots/disposal of waste"].split(",")
      var hand_washing_facility = this.identity[i]["hand washing facility"].split(",")

      

      var food_preparation = this.identity[i]["food preparation area/kitchen"].split(",")
      var cooking_mode = this.identity[i]["cooking mode"].split(",")
      var storage = this.identity[i]["storage of vegetables and cutting area"].split(",")
      var personal_Hygiene = this.identity[i]["personal hygiene of food handlers"].split(",")
      var condition = this.identity[i]["condition of cooking containers"].split(",")
      var store = this.identity[i]["store room"].split(",")
      var quality = this.identity[i]["quality of raw material for preparation f food "].split(",")
      var eggs = this.identity[i]["eggs"].split(",")
      var milk = this.identity[i]["milk and curd"].split(",")
      var banana = this.identity[i]["banana /fruit"].split(",")
      var ro_Plant = this.identity[i]["ro plant"].split(",")
      var dining_hall = this.identity[i]["dining hall"].split(",")
      var fly_catechers = this.identity[i]["fly catechers"].split(",")
      var hand_washing = this.identity[i]["hand washing facility in dining area"].split(",")
      var dress_code = this.identity[i]["dress code for cooking workers/sanition workers"].split(",")
      var hs_performance = this.identity[i]["hs performance"].split(",")
      var act_perfome = this.identity[i]["act  performance"].split(",")
      var suggetions = this.identity[i]["suggestions and comments"].split(",")
      var overall  = this.identity[i]["overall rating"].split(",")

      data_table = data_table + '<tr><th>Info to Panacea</th><td>'+abc[0] + '</td><td>'+abc[1]+'</td></tr>';
      
      
      data_table = data_table + '<tr><th>wellness centre with amenities</th><td>'+wellness[0] + '</td><td>'+wellness[1] + '</td></tr>'; 
      data_table = data_table + '<tr><th>Table Maintanance</th><td>'+ table[0]+ '</td><td>'+table[1] + '</td></tr>';
      data_table = data_table + '<tr><th>First Aid kit</th><td>'+first_aid_kit[0] + '</td><td>'+first_aid_kit[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Medical Equipments</th><td>'+ medical_equipments[0]+ '</td><td>'+medical_equipments[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Medicines in general/Emergency</th><td>'+ medicines_Emergency[0]+ '</td><td>'+ medicines_Emergency[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Medical records</th><td>'+medical_records[0] + '</td><td>'+medical_records[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Chronic diseases/special care</th><td>'+chronic_diseases[0]+ '</td><td>'+chronic_diseases[1]+ '</td></tr>'; 
      data_table = data_table + '<tr><th>Medical screening</th><td>'+medical_screening[0] + '</td><td>'+ medical_screening[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Wellness Club/healthy Tuesday</th><td>'+wellness_club[0] + '</td><td>'+wellness_club[1] + '</td></tr>'; 
      data_table = data_table + '<tr><th>Flow charts</th><td>'+flow_charts[0] + '</td><td>'+ flow_charts[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Hand wash</th><td>'+hand_wash[0] + '</td><td>'+hand_wash[1] + '</td></tr>';
      data_table = data_table + '<tr><th>RO Plant/Drining water</th><td>'+ ro_plant[0]+ '</td><td>'+ro_plant[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Incinetators</th><td>'+ incinetators[0]+ '</td><td>'+incinetators[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Awareness program</th><td>'+ awareness_program[0]+ '</td><td>'+awareness_program[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Sick diet</th><td>'+ sick_diet[0]+ '</td><td>'+sick_diet[1] + '</td></tr>';

      //sanitation
      data_table = data_table + '<tr><th><strong>SANITATION</strong></th><th><strong>Status</strong></th><th><strong>Remarks</strong></th></tr>';


      data_table = data_table + '<tr><th>School Campus</th><td>'+school_Campus[0] + '</td><td>'+school_Campus[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>School Building and Class rooms</th><td>'+school_Building[0] + '</td><td>'+school_Building[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Dormitory</th><td>'+dormitory[0] + '</td><td>'+dormitory[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Kitchen and Dining hall</th><td>'+ kitchen_Dining_hall[0] + '</td><td>'+ kitchen_Dining_hall[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Cooking Utensils</th><td>'+cooking_utensils[0] + '</td><td>'+cooking_utensils[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Personal Hygiene of food handlers and cooks</th><td>'+personal_cooks[0]+ '</td><td>'+personal_cooks[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Toilets and Both rooms</th><td>'+ toilets_both[0]+ '</td><td>'+ toilets_both[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>washing area</th><td>'+ washing[0]+ '</td><td>'+ washing[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Drinage area</th><td>'+drinage[0] + '</td><td>'+drinage[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Clening water tanks</th><td>'+clening_water[0] + '</td><td>'+clening_water[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Sanitizers</th><td>'+sanitizers[0] + '</td><td>'+sanitizers[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Garbage pots/Disposal of waste</th><td>'+ garbage_pots_Disposal[0]+ '</td><td>'+ garbage_pots_Disposal[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Hand washing facility</th><td>'+hand_washing_facility[0] + '</td><td>'+hand_washing_facility[1] + '</td></tr>';

      data_table = data_table + '<tr style="background-color:lightblue;"><th><strong>FOOD AND HYGIENE</strong></th><th><strong>Status</strong></th><th><strong>Remarks</strong></th></tr>';

      data_table = data_table + '<tr><th>Food Preparation Area/Kitchen</th><td>'+food_preparation[0] + '</td><td>'+food_preparation[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Cooking Mode</th><td>'+ cooking_mode[0]+ '</td><td>'+ cooking_mode[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Storage of Vegetables and Cutting area</th><td>'+ storage[0]+ '</td><td>'+ storage[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Personal Hygiene of food handlers</th><td>'+ personal_Hygiene[0]+ '</td><td>'+ personal_Hygiene[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Condition of Cooking Containers</th><td>'+condition[0] + '</td><td>'+condition[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Store room</th><td>'+store[0] + '</td><td>'+store[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Quality of raw material for preparation f food</th><td>'+quality[0] + '</td><td>'+quality[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Eggs</th><td>'+ eggs[0]+ '</td><td>'+ eggs[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Milk and Curd</th><td>'+milk[0] + '</td><td>'+milk[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Banana /Fruit</th><td>'+banana[0]  + '</td><td>'+banana[1]  + '</td></tr>';
      data_table = data_table + '<tr><th>RO Plant</th><td>'+ ro_Plant[0]+ '</td><td>'+ro_Plant[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Dining Hall</th><td>'+ dining_hall[0] + '</td><td>'+ dining_hall[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Fly Catechers</th><td>'+fly_catechers[0] + '</td><td>'+fly_catechers[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Hand washing facility in dining area</th><td>'+hand_washing[0] + '</td><td>'+hand_washing[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Dress code for cooking workers/sanition workers</th><td>'+dress_code[0] + '</td><td>'+dress_code[1] + '</td></tr>';
      data_table = data_table + '<tr><th>Hs Performance</th><td>'+ hs_performance[0]+ '</td><td>'+ hs_performance[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>ACT  performance</th><td>'+act_perfome[0] + '</td><td>'+ act_perfome[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Suggestions and comments</th><td>'+suggetions[0] + '</td><td>'+ suggetions[1]+ '</td></tr>';
      data_table = data_table + '<tr><th>Overall Rating</th><td>'+overall [0]+ '</td><td>'+ overall [1]+ '</td></tr>';

        data_table = data_table + '</thead> <tbody>';
      }
        }
          
      });

      data_table = data_table + '</tbody></table><div><button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">Back</button></div>';
      debugger;
      $("#stud_report_rhso").html(data_table);

      //=========================================data table functions=====================================
      
            /* BASIC ;*/
        var responsiveHelper_dt_basic = undefined;
        var responsiveHelper_datatable_fixed_column = undefined;
        var responsiveHelper_datatable_col_reorder = undefined;
        var responsiveHelper_datatable_tabletools = undefined;
        
        var breakpointDefinition = {
          tablet : 1024,
          phone : 480
        };
    
        $('#stud_report_rhso').dataTable({
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#stud_report_rhso'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });
    
      /* END BASIC */
      var js_url = "<?php echo JS; ?>";
      /* COLUMN FILTER  */
        var otable = $('#datatable_fixed_column_rhso').DataTable({
         
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
         "oTableTools": {
               "aButtons": [
                    {
                     "sExtends": "xls",
                     "sTitle": "TLSTEC Schools Report",
                     "sPdfMessage": "TLSTEC Schools Excel Export",
                     "sPdfSize": "letter"
                   },
                  {
                    "sExtends": "print",
                    "sMessage": "TLSTEC Schools Printout <i>(press Esc to close)</i>"
                }],
               "sSwfPath": js_url+"datatables/swf/copy_csv_xls_pdf.swf"
            },
        "autoWidth" : true,
        "preDrawCallback" : function() {
          // Initialize the responsive datatables helper once.
          if (!responsiveHelper_datatable_fixed_column) {
            responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column_rhso'), breakpointDefinition);
          }
        },
        "rowCallback" : function(nRow) {
          responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
          responsiveHelper_datatable_fixed_column.respond();
        }   
      
        });
        
        
        $("#datatable_fixed_column_rhso thead th input[type=text]").on( 'keyup change', function () {
          
            otable
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();
                
        } );
        
      
      
      //=====================================================================================================
      }else{
        $("#stud_report_rhso").html('<h5>No students to display for this school</h5>');
      }
  }

      //=========================================data table functions=====================================


	
});

</script>
