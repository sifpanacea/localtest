<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "New Dashboard";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["basic_dashboard"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo(CSS.'AdminLTE.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo(CSS.'ionicons.min.css'); ?>"  rel="stylesheet" type="text/css" />
<!-- <link href="<?php //echo(font.'fontawesome-webfont.ttf'); ?>"  rel="stylesheet" type="text/css" /> -->

<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<style>
#set_date
{
	float:right;
	 width: 200px; 
}


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
		<div class="row hide">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>150</h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>53<sup style="font-size: 20px">%</sup></h3>

              <p>Bounce Rate</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!--./col-->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>44</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>65</h3>

              <p>Unique Visitors</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>


		<article class="col-sm-12 col-md-10 col-lg-10">
			 <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
			 </div>
			</article>
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
			<section class="col col-4">
				<label class="label" for="first_name">District Name</label>
				<label class="select">
				<select id="select_dt_name" >
					<option value='All' >All</option>
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
			<section class="col col-4">
				<label class="label" for="first_name">School Name</label>
				<label class="select">
				<select id="school_name" disabled=true>
						<option value="All" >All</option>
					
					
				</select> <i></i>
			</label>
			</section> <input type="hidden" name="school_code" id="school_code"><br>
			
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
			<section>
		        <article class="col-sm-6 col-md-6 col-lg-6">
		        <div class="jarviswidget jarviswidget-color-purple" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
			<header>
			<span class="widget-icon"> <i class="fa fa-user"></i> </span>
			<h2>Screening Report</h2>
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
		<div>

			<!-- widget edit box -->
			<div class="jarviswidget-editbox">
				<!-- This area used as dropdown edit box -->

			</div>
			<!-- end widget edit box -->

			<!-- widget content -->
			
			<div class="widget-body">
				<div class="row">
					<div class="col-md-3" id="select_year_wise">
							<form class="smart-form">
							<!-- <label class="label">Search Span</label>
							<label class="select">
								<select id="screening_pie_span" class="get_last_screened">
									<option value="Monthly">Monthly</option>
									<option value="Bi Monthly">Bi Monthly</option>
									<option value="Quarterly">Quarterly</option>
									<option value="Half Yearly">Half Yearly</option>
									<option value="2015-16 Academic Year">2015-16 Academic Year</option>
									<option value="2016-17 Academic Year">2016-17 Academic Year</option>
									<option selected value="Yearly">2017-18 Academic Year</option>
								</select> <i></i> </label> -->
						</form>
					</div>
					<div class="col-md-5">
						<div id="screening_report">
						
						</div>
					

						<div id="abnormalties_report_table">
							
						</div>
					</div>
					<!-- <div class="col-md-4 hide" id="test">
						<div id="pie_screening" class="">
							
						
					</div>
				</div> -->
				</div>
						
			</div>
			<!-- end widget content -->

		</div>
		<!-- end widget div -->

		</div>
		</article>
			</section>
			<section>
				<article class="col-sm-6 col-md-6 col-lg-6">
					<div class="jarviswidget jarviswidget-color-purple" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						<header>
							<span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
							<h2>Attendance</h2>
							<label class="pull-right txt-color-white"> <a href="javascript:void(0)" class="abs_not_submitted_schools_list"> Not Submitted Schools : </a><span class="abs_not_submitted_schools"></span></label>
						</header>


						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
								<!-- content -->
								<div id="myTabContent" class="tab-content">
								<div class="row">
								
								
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								
								
								
								
								<div id="pie_absent"></div>
								<form style="display: hidden" action="drill_down_absent_to_students_load_ehr" method="POST" id="ehr_form_for_absent">
									<input type="hidden" id="ehr_data_for_absent" name="ehr_data_for_absent" value=""/>
									<input type="hidden" id="ehr_navigation_for_absent" name="ehr_navigation_for_absent" value=""/>
								</form>
								
								
								<!-- ATTENDANCE SUBMITTED SCHOOLS LIST -->
								<div class="modal fade-in" id="absent_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							×
						</button>
						<h4 class="modal-title" id="myModalLabel">Absent Report Submitted Schools </h4>
					</div>
					<div class="well well-sm well-light">
					<button type="button" class="btn btn-primary" id="absent_sent_school_download">
							Download
						</button>
						</div>
					<div id="absent_sent_school_modal_body" class="modal-body">
		            
					
					</div>
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-primary" id="absent_sent_school_download">
							Download
						</button> -->
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		
		<!-- ATTENDANCE NOT SUBMITTED SCHOOLS LIST -->
		<div class="modal fade-in" id="absent_not_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							×
						</button>
						<h4 class="modal-title" id="myModalLabel">Absent Report Not Submitted Schools </h4>
					</div>
					<div class="well well-sm well-light">
					<button type="button" class="btn btn-primary" id="absent_not_sent_school_download">
							Download
						</button>
						</div>
					<div id="absent_not_sent_school_modal_body" class="modal-body">
					
					</div>
					<div class="modal-footer">
					
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		
								<div class="well well-sm well-light">
								<label class="form-control"> <a href="javascript:void(0)" class="abs_submitted_schools_list">Submitted Schools &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</a> <span class="abs_submitted_schools"></span></label>
								<label class="form-control"> <a href="javascript:void(0)" class="abs_not_submitted_schools_list"> Not Submitted Schools : </a><span class="abs_not_submitted_schools"></span></label>
								</div>
							
							</div>
						</div>
					</div>
				</div>
			</div>
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


	
	<form style="display: hidden" action="<?php echo URL; ?>tmreis_mgmt/get_show_ehr_details" method="POST" id="requests_show_form">
	  <input type="hidden" id="to_date_new" name="to_date_new" value=""/>
	  <input type="hidden" id="school_name_new" name="school_name_new" value=""/>
	  <input type="hidden" id="request_type_new" name="request_type_new" value=""/>
	</form>
	

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

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
	
	
	var today_date = $('#set_date').val();
	var dt_name = $('#select_dt_name').val();
	var school_name = $('#school_name').val();
		
		//console.log('php111111111111111', today_date);
		
		$('.datepicker').datepicker({
			minDate: new Date(1900, 10 - 1, 25)
		});
		
		change_to_default(today_date);
		display_sanitation_graph_default();

		function change_to_default(today_date,screening_report){
			today_date = today_date;
			$('#screening_pie_span').val("Yearly");
			request_count_all();
		}
		
		initialize_variables(today_date,<?php echo $screening_report?>);
		function initialize_variables(today_date,screening_report)
		{
				today_date = today_date;
				init_screening_pie(screening_report);
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
				
				
				document_details_list(result);
				//console.log(result);
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				
			    }
			})
			
		}
		
		$('#set_date_btn').click(function(e){
			
			today_date = $("#set_date").val();
			school_name = $("#school_name").val();
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
	 dt_name = $('#select_dt_name').val();
	 school_name = $('#school_name').val();
	if(dt_name == "All" && school_name == "All"){

	$.ajax({
		url: 'basic_dashboard_with_date',
		type: 'POST',
		data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
					console.log('ALLLLLLL', data);
				$( "#screening_report" ).empty();
				data = $.parseJSON(data);
				
					screening_report  = $.parseJSON(data.screening_report);
					result  = $.parseJSON(data.sanitation_report);
					display_sanitation_graph(result);
					display_yes_no_table(result);
					initialize_variables(today_date,screening_report);
		
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
				console.log('FOR_SCREENING',data);
				result = $.parseJSON(data);
				console.log('total information', result);
				screening_data = result.screening_report;
				schoolEmail = result.school_email_id;
				
				init_screening_pie(screening_data,schoolEmail);
				document_details_list(result)
				display_data_table(screening_data);
				$('#showPIEBtn').html('<button class="btn bg-color-green txt-color-white pull-right" id="showPIE">Show PIE</button>');
				$('#showPIE').click(function(){
					draw_screening_pie();
					$('#test').removeClass('hide');

				});
				result = result.sanitation_report;
				draw_sanitation_report_table(result);
				
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
		var dt_name = $('#select_dt_name').val();
		var school_name = $('#school_name').val();
		
		if(dt_name == "All" && school_name == "All"){
		$.ajax({
			url: 'basic_dashboard_with_date',
			type: 'POST',
			data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span, "dt_name" : dt_name, "school_name" : school_name},
			success: function (data) {
				$('#load_waiting').modal('hide');
				
					$( "#screening_report" ).empty();
					data = $.parseJSON(data);
						screening_report  = $.parseJSON(data.screening_report);
						result  = $.parseJSON(data.sanitation_report);
						display_sanitation_graph(result);
						display_yes_no_table(result);
						initialize_variables(today_date,screening_report);
						draw_all_screening_pie();

					
				
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
			
			

			url = '<?php echo URL."tmreis_mgmt/get_show_ehr_details"; ?>';
			if((result.initiate_request_count_for_today) > 0 ){
				
				
				initiate_request_count_for_today = result.initiate_request_count_for_today;
				doctors_count = result.doctors_count;
				//submitted_by = result.submitted_by;
				doctor_name_dr1 = (result.doctor_name_dr1) ? result.doctor_name_dr1 : "" ;
				doctor_name_dr2 =(result.doctor_name_dr2) ? result.doctor_name_dr2 : "" ;
				doctor_name_dr3 = (result.doctor_name_dr3) ? result.doctor_name_dr3 : "" ;
				doctor_name_dr4 = (result.doctor_name_dr4) ? result.doctor_name_dr4 : "" ;
				doctor_name_dr5 = (result.doctor_name_dr5) ? result.doctor_name_dr5 : "" ;
				doctor_name_dr6 = (result.doctor_name_dr6) ? result.doctor_name_dr6 : "" ;
				doctor_name_dr7 = (result.doctor_name_dr7) ? result.doctor_name_dr7 : "" ;
				doctor_name_dr10 = (result.doctor_name_dr10) ? result.doctor_name_dr10 : "" ;
				doctor_name_dr11 = (result.doctor_name_dr11) ? result.doctor_name_dr11 : "" ;
				doctor_name_dr12 = (result.doctor_name_dr12) ? result.doctor_name_dr12 : "" ;
				doctor_name_dr13 = (result.doctor_name_dr13) ? result.doctor_name_dr13 : "" ;
				doctors_count_dr1 = (result.doctors_count_list_dr1) ? result.doctors_count_list_dr1 : "" ;
				doctors_count_dr2 = (result.doctors_count_list_dr2) ? result.doctors_count_list_dr2 : "" ;
				doctors_count_dr3 = (result.doctors_count_list_dr3) ? result.doctors_count_list_dr3 : "" ;
				doctors_count_dr4 = (result.doctors_count_list_dr4) ? result.doctors_count_list_dr4 : "" ;
				doctors_count_dr5 = (result.doctors_count_list_dr5) ? result.doctors_count_list_dr5 : "" ;
				doctors_count_dr6 = (result.doctors_count_list_dr6) ? result.doctors_count_list_dr6 : "" ;
				doctors_count_dr7 = (result.doctors_count_list_dr7) ? result.doctors_count_list_dr7 : "" ;
				doctors_count_dr10 = (result.doctors_count_list_dr10) ? result.doctors_count_list_dr10 : "" ;
				doctors_count_dr11 = (result.doctors_count_list_dr11) ? result.doctors_count_list_dr11 : "" ;
				doctors_count_dr12 = (result.doctors_count_list_dr12) ? result.doctors_count_list_dr12 : "" ;
				doctors_count_dr13 = (result.doctors_count_list_dr13) ? result.doctors_count_list_dr13 : "" ;
				
				  $('#request_response_table_view').html('<div id="request_response" class="text-center"></div>');

				var table = '<div style="overflow-y: auto;" ><table class="table table-striped table-bordered table-hover" id="requestTable"><thead><tr><th></th><th class="text-center">Requests count</th><th class="text-center">Responses count</th><th colspan="2" class="text-center">Request Attended By</th></tr></thead><tbody>'

				table = table + '<tr><th>Total Requests</th><td>'+initiate_request_count_for_today+'</td><td>'+doctors_count+'</td>'
				
				table = table+'<td rowspan="3"><ul style ="list-style: none"><li>'+doctor_name_dr1+'  '+'<span class="badge">'+doctors_count_dr1+'</span></li><p></p><li>'+doctor_name_dr2+'  '+'<span class="badge">'+doctors_count_dr2+'</span></li> <p></p> <li>'+doctor_name_dr3+'  '+'<span class="badge">'+doctors_count_dr3+'</span></li><p></p><li>'+doctor_name_dr4+'  '+'<span class="badge">'+doctors_count_dr4+'</span></li><p></p><li>'+doctor_name_dr5+'  '+'<span class="badge">'+doctors_count_dr5+'</span></li><p></p><li>'+doctor_name_dr6+'  '+'<span class="badge">'+doctors_count_dr6+'</span></li><p></p><li>'+doctor_name_dr7+'  '+'<span class="badge">'+doctors_count_dr7+'</span></li><p></p><li>'+doctor_name_dr10+'  '+'<span class="badge">'+doctors_count_dr10+'</span></li><p></p><li>'+doctor_name_dr11+'  '+'<span class="badge">'+doctors_count_dr11+'</span></li><p></p><li>'+doctor_name_dr12+'  '+'<span class="badge">'+doctors_count_dr12+'</span></li><p></p><li>'+doctor_name_dr13+'  '+'<span class="badge">'+doctors_count_dr13+'</span></li></ul></td></tr>';
					
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
	
});

</script>
<!-------------------Attendence in pie ----start--->
<script>
	$(document).ready(function(){
		var today_date = $('#set_data').val();
		var dt_name = $('#select_dt_name').val();
		var school_name = $('#school_name').val();
		var absent_sent_schools     = <?php echo $absent_report_schools_list['submitted_count'];?>;
		var absent_not_sent_schools = <?php echo $absent_report_schools_list['not_submitted_count'];?>;
		var absent_submitted_schools_list     = "";
		var absent_not_submitted_schools_list = "";
		
		absent_submitted_schools_list         = <?php echo json_encode($absent_report_schools_list['submitted']);?>;
		absent_not_submitted_schools_list     = <?php echo json_encode($absent_report_schools_list['not_submitted']);?>;

		
		var absent_data = "";
		var absent_navigation = [];
		previous_absent_a_value = [];
		previous_absent_title_value = [];
		previous_absent_search = [];
		absent_search_arr = [];
		initialize_variables(today_date,<?php echo $absent_report?>);
		draw_absent_pie();

		function initialize_variables(today_date,absent_report)
		{
				today_date = today_date;
				init_absent_pie(absent_report);
				$('.abs_submitted_schools').html(absent_sent_schools);
				$('.abs_not_submitted_schools').html(absent_not_sent_schools);
		}

		function init_absent_pie(absent_report){
			absent_data = absent_report;
			absent_navigation = [];
			previous_absent_a_value = [];
			previous_absent_title_value = [];
			previous_absent_search = [];
			absent_search_arr = [];
		}

		today_date = $('#set_data').val();
		/*$.ajax({
		url: 'to_dashboard_with_date',
		type: 'POST',
		data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
			$( "#pie_absent" ).empty();
			
			$( "#pie" ).empty();
		
			
			data = $.parseJSON(data);
			console.log('bhanu1', data);
			absent_report     = $.parseJSON(data.absent_report);
			

			
			// Absent Report
			var absent_submitted_schools_list_count = data.absent_report_schools_list.submitted_count;
			var absent_not_submitted_schools_list_count = data.absent_report_schools_list.not_submitted_count;
			absent_submitted_schools_list     = "";
			absent_not_submitted_schools_list = "";
			absent_submitted_schools_list     = data.absent_report_schools_list.submitted;
	        absent_not_submitted_schools_list = data.absent_report_schools_list.not_submitted; 
			
			
			
			initialize_variables(today_date,absent_report);
			draw_absent_pie();
			
			update_absent_schools_data(absent_submitted_schools_list_count,absent_not_submitted_schools_list_count);
			
			
			
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});*/
		function update_absent_schools_data(absent_submitted_schools_list_count,absent_not_submitted_schools_list_count)
		{
			$('.abs_submitted_schools').html(absent_submitted_schools_list_count);
			
			$('.abs_not_submitted_schools').html(absent_not_submitted_schools_list_count);
		}
		// Absent list sent schools list
$('.abs_submitted_schools_list').click(function(){
	
	if(absent_submitted_schools_list!=null)
	{
		if(absent_submitted_schools_list['school']!="")
		{
		//console.log("submitted schools",absent_submitted_schools_list['school']);
			$('#absent_sent_school_modal_body').empty();
			var table="";
			var tr="";
			table += "<table class='table table-bordered' id='absent_sent_school_modal_body_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
			for(var i=0;i<absent_submitted_schools_list['school'].length;i++)
			{
				var j=i+1;
				table+= "<tr><td>"+j+"</td><td>"+absent_submitted_schools_list['district'][i]+"</td><td>"+absent_submitted_schools_list['school'][i]+"</td><td>"+absent_submitted_schools_list['mobile'][i]+"</td><td>"+absent_submitted_schools_list['person_name'][i]+"</td></tr>"
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
			table += "<table class='table table-bordered'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
			for(var i=0;i<absent_not_submitted_schools_list['school'].length;i++)
			{
				var j=i+1;
				table+= "<tr><td>"+j+"</td><td>"+absent_not_submitted_schools_list['district'][i]+"</td><td>"+absent_not_submitted_schools_list['school'][i]+"</td><td>"+absent_not_submitted_schools_list['mobile'][i]+"</td><td>"+absent_not_submitted_schools_list['person_name'][i]+"</td></tr>"
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
	
	/*if(absent_submitted_schools_list!=null)
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
})*/
var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
      var textRange; var j=0;
      tab = document.getElementById('absent_sent_school_modal_body_tab'); // id of table

      for(j = 0 ; j < tab.rows.length ; j++)
      {    
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
      }

      tab_text=tab_text+"</table>";


      var ua = window.navigator.userAgent;
      var msie = ua.indexOf("MSIE ");

      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
      {
         txtArea1.document.open("txt/html","replace");
         txtArea1.document.write(tab_text);
         txtArea1.document.close();
         txtArea1.focus();
         sa=txtArea1.document.execCommand("SaveAs",true,"absent_sent_school_.xlsx");
      } 
      else //other browser not tested on IE 11
         sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text)); 
        return (sa);
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
					console.log("Segment clicked! See the console for all data passed to the click handler.");
					console.log(onClickFn);
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

function draw_absent_pie(){
	//console.log('absent_data',absent_data);
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
	})
</script>