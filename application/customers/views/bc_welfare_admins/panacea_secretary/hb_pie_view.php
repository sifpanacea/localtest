<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "HB PIE";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder 
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa hb"]["sub"]["hb_monthly"]["active"] = true;
include("inc/nav.php");

?>
<style>

</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

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


			<div class="col-xs-12 col-sm-6 col-md-12 col-lg-12">
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
						<h2>HB PIE</h2>
						<center>
							<h2> Male Students :<?php echo $total_students['male'];?> &nbsp;&nbsp; Female Students : <?php echo $total_students['female'];?> &nbsp; &nbsp; Total Students Count : <?php echo $total_students['total_students'];?> &nbsp;&nbsp;</h2>
						</center> 
						<button type="button" class="btn bg-color-pink txt-color-white btn-sm pull-right export-button hide" id="bmi_export_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
							Export to Excel
						</button>
					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->


						<div class="col-md-12" id="loading_request_pie" style="display:none;">
							<center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
						</div>

						<div id="request_pies">

							<div class="row">
								
								
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									<div class="well well-sm well-light">
										<input type="radio" name="gender" class="student_type" value="" checked> All
										<input type="radio" name="gender" class="student_type" id="student_type_boys" value="Male"> Male
										<input type="radio" name="gender" class="student_type" id="student_type_girls"value="Female"> Female
										<br>
										<br>
										<form class='smart-form'>
											<fieldset style="padding-top: 0px; padding-bottom: 0px;">
												<div class="row">

													<section class="col col-3">
														<label class="label">Select Month</label>

														<div class="form-group">
															<div class="input-group">
																<input type="text" id="set_data" name="set_data" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $hb_submitted_month?>">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															</div>
														</div>
													</section>
													<section class="col col-3">
														<label class="label">Select District</label>
														<label class="select">
															<select id="select_dt_name" >
																<option value="select">select</option>
																<?php if(isset($district_list)): ?>
																	<?php foreach ($district_list as $district):?>
																		<option value='<?php echo $district['_id']?>' ><?php echo ucfirst($district['dt_name'])?></option>
																	<?php endforeach;?>
																	<?php else: ?>
																		<option value="1"  disabled="">No district entered yet</option>
																	<?php endif ?>
																</select> <i></i>
															</label>
														</section>
														<section class="col col-3">
															<label class="label" for="first_name">School Name</label>
															<label class="select">
																<select id="school_name" disabled=true>
																	<option value='select' >select</option>
																</select> <i></i>
															</section>
															<section class="col col-3">
																<label class="label" for="age"> Select Age</label>
																<label class="select">
																	<select id="age">
																		<option value="select age"> select </option>
																		<option value="10"> 10 </option>
																		<option value="11"> 11 </option>
																		<option value="12"> 12 </option>
																		<option value="13"> 13 </option>
																		<option value="14"> 14 </option>
																		<option value="15"> 15 </option>
																		<option value="16"> 16(Inter 1st) </option>
																		<option value="17"> 17(Inter 2nd) </option>
																		<option value="18"> 18(Degree 1st) </option>
																		<option value="19"> 19(Degree 2nd) </option>
																		<option value="20"> 20(Degree 3rd) </option>

																	</select><i></i>
																</label>
															</section>

														</div>
														<section>
															<button type="button" class="btn bg-color-pink txt-color-white btn-sm" id="set_date_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
																Set
															</button>



														</section>
													</fieldset>
												</form>
											</div>


										</div>
									</div>

									<div class="row">
										<br>

										<!-- widget content -->
										<div class="col-xs-12 col-sm-3 col-md-5 col-lg-5">
											<div id="hb_report_table">
												<span id="text_msg" class="text-center"> Select District name and School</span>
											</div>
											<br>
											<div id="show_bmi_links" class="hide" >

											</div>
										</div>
										<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4">

											<!-- widget content -->

											
											<div id="pie_request" style=" margin-top: -50px;"></div>
											
											<form style="display: hidden" action="drill_down_to_hb_report_students" method="POST" id="form_for_bmi_report">
												<input type="hidden" id="ehr_data_for_hb" name="ehr_data_for_hb" value=""/>
												<input type="hidden" id="selectedMonth" name="selectedMonth" value=""/>
												<input type="hidden" id="ehr_navigation_for_bmi" name="ehr_navigation_for_bmi" value=""/>												
											</form>
										</div>

									</div>
			



								</div>
								<!-- end widget content -->

								<!-- end widget div -->

							</div>
							<!-- end widget -->
							<!-- end widget -->

						</article>
					</div>
					




					<!-- Modal -->
					<div class="modal fade" id="load_waiting" tabindex="-1" role="dialog" >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title" id="myModalLabel">Loading BMI Report in progress</h4>
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
					
					
					<!-- BMI SUBMITTED SCHOOLS LIST -->
					<div class="modal fade-in" id="bmi_submitted_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
										??
									</button>

									<h4 class="modal-title" id="myModalLabel">BMI Report Submitted Schools </h4>
								</div>

								<div id="bmi_submitted_school_modal_body" class="modal-body">


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
		
		<!-- BMI NOT SUBMITTED SCHOOLS LIST -->
		<div class="modal" id="bmi_not_submitted_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							??
						</button>
						<h4 class="modal-title" id="myModalLabel">BMI Report Not Submitted Schools </h4>
					</div>
					
					<div id="bmi_not_submitted_school_modal_body" class="modal-body">

					</div>
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-primary" id="absent_not_sent_school_download">
							Download
						</button> -->
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>

		<!-- row -->
		<!-- end row -->
	</div>
	<!-- widget grid -->


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

	<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.fillbetween.min.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.pie.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.time.min.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
	<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
	<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>

	<?php 
	//include footer
	include("inc/footer.php"); 
	?>

	<script>
		$(document).ready(function() {

			var current_month = $('#set_data').val();
			var dt_name = $('#select_dt_name option:selected').text();
			var school_name = $('#school_name').val();
			var stu_age = $('#age').val();

			var bmi_data = "";
			var bmi_navigation = [];
			previous_request_a_value = [];
			previous_request_fn = [];
			previous_request_title_value = [];
			previous_request_search = [];
			request_search_arr = [];

			var stu_type = "";
			var stu_age = "";
			draw_hb_pie_state_wide(current_month,stu_type,stu_age);
			$(".student_type").each(function(){
				$(this).click(function ()
				{
					var type = $(this).val();
					stu_type = type;
					stu_age = $("#age option:selected").text();
					draw_hb_pie_state_wide(current_month,stu_type,stu_age);		
				})
			});

			function draw_hb_pie_state_wide(current_month,stu_type,stu_age)
			{

				$.ajax({
					url: 'hb_pie_view_month_wise',
					type: 'POST',
					data: { "current_month":current_month, "dt_name" : dt_name,"school_name":school_name,'student_type': stu_type,'student_age':stu_age},

					success: function (data) 
					{
						$('#pie_request').empty();
						$('#load_waiting').modal('hide');
						$('#text_msg').modal('hide');
						//$( "#pie_request" ).empty();
						data = $.parseJSON(data);
						hb_report = $.parseJSON(data.hb_report);
						display_data_table(hb_report);
			  //bmi_reported_schools = $.parseJSON(data.bmi_reported_schools);
			  
			  if(hb_report == 1){
			  	$("#pie_request").append('No positive values to dispaly');
			  	$('#img-formula').addClass("img-formula hide");
			  	$('#show_bmi_links').addClass("show_bmi_links hide");
					//$('#show_bmi_links').hide();
					
				}else{
					
					//$('#img-formula').show();
					$('#img-formula').removeClass("img-formula hide");
					$('#show_bmi_links').removeClass("show_bmi_links hide");
					$('button').removeClass('export-button hide');
					//bmi_navigation.push("BMI PIE");
					hb_request_pie(hb_report,"drill_down_hb_to_students");
					
					// 
					
					
					//var show_bmi_links = '<div class="well well-sm well-light"><label class="form-control"> <a href="javascript:void(0)" class="bmi_submitted_schools_list">Submitted Schools &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</a> <span class="bmi_submitted_schools_count"></span></label><label class="form-control"> <a href="javascript:void(0)" class="bmi_not_submitted_schools_list"> Not Submitted Schools : </a><span class="bmi_not_submitted_schools_count"></span></label></div>';
				//	$('#show_bmi_links').html(show_bmi_links);

					//$('.bmi_submitted_schools_count').html(bmi_reported_schools.submitted_count);
					//$('.bmi_not_submitted_schools_count').html(bmi_reported_schools.not_submitted_count);
					
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
				console.log('error', errorThrown);
			}
		})
			}	

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
						options.prop("disabled", false);
						options.empty();
						options.append($("<option />").val("select").prop("selected", true).text("All"));
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


			$('#set_date_btn').click(function (e){

				/*if(dt_name == "select")
				{
					$.SmartMessageBox({
						title : "Alert!",
						content : "Please Select District Inorder to View BMI PIE",
						buttons : '[OK]'
					})
				}
				*/
				current_month = $('#set_data').val();
				dt_name = $("#select_dt_name option:selected").text();
				school_name = $("#school_name option:selected").text();
				stu_age = $("#age option:selected").text();

				$.ajax({
					url: 'hb_pie_view_month_wise',
					type: 'POST',
					data: { "current_month":current_month, "dt_name" : dt_name,"school_name":school_name,'student_type':stu_type,'student_age':stu_age},

					success: function (data) 
					{
						$('#pie_request').empty();
						$('#load_waiting').modal('hide');
						$('#text_msg').modal('hide');
						//$( "#pie_request" ).empty();
						data = $.parseJSON(data);
						hb_report = $.parseJSON(data.hb_report);
						display_data_table(hb_report);
						hb_request_pie(hb_report,"drill_down_hb_to_students");
						if(data.hb_reported_schools == undefined)
						{

						}else{
							hb_reported_schools = $.parseJSON(data.hb_reported_schools);
							
							if(hb_report == 1 || hb_reported_schools == 1)
							{
								$("#pie_request").append('No positive values to dispaly');
								$('#img-formula').addClass("img-formula hide");
								$('#show_bmi_links').addClass("show_bmi_links hide");
					
							}else{
								
								//$('#img-formula').show();
								$('#img-formula').removeClass("img-formula hide");
								$('#show_bmi_links').removeClass("show_bmi_links hide");
								$('button').removeClass('export-button hide');
								//bmi_navigation.push("BMI PIE");
								
								
							}	
						}

					},
					error:function(XMLHttpRequest, textStatus, errorThrown)
					{
						console.log('error', errorThrown);
					}
				});
			});


			function hb_request_pie(data, onClickFn){
				var pie = new d3pie("pie_request", {
					header: {
						title: {
							text: bmi_navigation.join(" / ")
						}
					},
					size: {
						canvasHeight: 300,
						canvasWidth: 350 

					},
					data: {
						content: data
					},
					labels: {
						inner: {
							format: "value"
						}
					},
		//pie segments colors
		misc: {
			colors: {
				segments: [
					//"#C3252A", "#829C05", "#EDA336"
					"#ff0000", "#00ff40", "#ffff00","#ff8000"
					]
				}
			},
			tooltips: {
				enabled: true,
				type: "placeholder",
				string: "{label}, {value}"
			},
			callbacks: {

				
				onClickSegment: function(a) {

					if(onClickFn == "drill_down_hb_to_students")
					{
						drill_down_hb_to_students(a.data.label);
						 //$('#load_waiting').modal('show');
						}

					}
				}

			});
			}

			function drill_down_hb_to_students(label)
			{
				dt_name = $("#select_dt_name option:selected").text();
				school_name = $("#school_name option:selected").text();
				age = $("#age option:selected").text();

				$.ajax({
					url: 'drill_down_hb_to_students',
					type: 'POST',
					data: {"case_type" : label, "current_month":current_month, "dt_name" : dt_name,"school_name":school_name,"student_type":stu_type,"student_age":stu_age},
					success: function (data) 
					{
						console.log('SUCCESS_DATA',data);
						$('#load_waiting').modal('hide');
						$("#ehr_data_for_hb").val(data);
						$("#selectedMonth").val(current_month);
						bmi_navigation.push(label);
						$("#ehr_navigation_for_bmi").val(bmi_navigation.join(" / "));
						$("#form_for_bmi_report").submit();
					},
					error:function(XMLHttpRequest, textStatus, errorThrown)
					{
						console.log('error', errorThrown);
					}
				});
			}

			$('#bmi_export_btn').click(function(e){
				current_month = $('#set_data').val();
				dt_name = $("#select_dt_name option:selected").text();
				school_name = $("#school_name option:selected").text();

				$.ajax({
					url: 'generate_bmi_report_to_excel',
					type:'POST',
					data:{"current_month": current_month, "district_name":dt_name, "school_name":school_name},
					success : function (data) {
						$('#load_waiting').modal('hide');
						window.location = data;
					},
					error:function(XMLHttpRequest, textStatus, errorThrown)
					{
						console.log('error', errorThrown);
					}
				});
			});

			function display_data_table(hb_report){

				if(hb_report.length > 0){
		/*if( objLength == 0 ){
			$("#screening_report").html("<h5 class='text-center'><div class='alert alert-danger'>No Screening data available for this School <strong>"+ school_name+"</strong></div></h5>");
		}*/
		var hb_total = 0;
		data_table = '<table id="screening_report_table" class="table table-striped table-bordered" width="100%"><tbody>';
		data_table = data_table + '<tr class="txt-color-magenta"><th>Abnormality</th><th>Count</th><th>EHR</th></tr>';
		$.each(hb_report, function(index, value) {

			data_table = data_table + '<tr>';
			data_table = data_table + '<td id="identifier_table">'+value.label+'</td>';
			data_table = data_table + '<td><span class="badge bg-color-orange">'+value.value+'</span></td>';
			data_table = data_table + '<td><button class="btn btn-primary btn-xs hb_label_btn" id="hb_label_btn" value="'+value.label+'">View</button></td>';
			data_table = data_table + '</tr>';
			hb_total +=  value.value;
		});

		data_table = data_table + '</tbody></table>';
		$("#hb_report_table").html(data_table);
		$('#hb_report_table tr:last').after('<tr class="txt-color-darkblue"><th>HB Total Count</th><td colspan="2"><span class="badge bg-color-orange">'+hb_total+'</span></td></tr>');
		


		$(".hb_label_btn").each(function(){
			$(this).click(function (){
				current_month = $('#set_data').val();
				dt_name = $("#select_dt_name option:selected").text();
				school_name = $("#school_name option:selected").text();
				var stu_age = $("#age option:selected").text();
				var case_type = $(this).val();

				$.ajax({
					url: 'drill_down_hb_to_students',
					type: 'POST',
					data: {"case_type" : case_type, "dt_name":dt_name, "school_name":school_name, "current_month":current_month,'student_type':stu_type,'student_age':stu_age},
					success: function (data) {
						$('#load_waiting').modal('hide');
						$("#ehr_data_for_hb").val(data);
						$("#selectedMonth").val(current_month);

						$("#ehr_navigation_for_bmi").val(case_type);
						$("#form_for_bmi_report").submit();					
                       
					},
					error:function(XMLHttpRequest, textStatus, errorThrown)
					{
						console.log('error', errorThrown);
					}
				});

			});
		});

	}else{
		$("#hb_report_table").html("");
		
		}	//=====================================================================================================

	}


});		


//===================================drill down pie======================
//===================================end of dril down pie================
</script>

