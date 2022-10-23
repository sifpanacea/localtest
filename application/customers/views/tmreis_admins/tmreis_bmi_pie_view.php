<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "BMI PIE";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["bmi_pie"]["active"] = true;
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
							<h2>BMI PIE</h2>
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
											<label class="label">Select Month</label>
												
												<div class="form-group">
													<div class="input-group">
													<input type="text" id="set_data" name="set_data" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $bmi_submitted_month?>">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													</div>
												</div>
											</section>
											<section class="col col-4">
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
											<section class="col col-4">
												<label class="label" for="first_name">School Name</label>
															<label class="select">
															<select id="school_name" disabled=true>
																<option value='select' >select</option>
															</select> <i></i>
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
								
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									
										<br>
										
										<!-- widget content -->
										<div class="col-md-12" id="loading_request_pie" style="display:none;">
												<center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
											</div>
											
											<div class="col col-lg-6" id="pie_request"><p id="text_msg"> Select District name and School</p></div>

											<div class="img-formula hide col col-lg-6" id="img-formula">
													
												<h4>Reference by</h4>
												<span>
												<img src="../../uploaddir/public/images.png" alt="" style="height:60px;margin-top:6px;margin-left:6px;">
												 </span>
												 <h4>BMI Interpretation</h4>
												 <span>
												<img src="../../uploaddir/public/bmi_range.jpg" alt="" style="width:250px;height:120px;margin-top:6px;margin-left:6px;">
												 </span>
												<h6><span>Source : <a href="http://apps.who.int/bmi/index.jsp?introPage=intro_3.html" target="_blank">World Health Organisation</a></span></h6>
											</div>
											<br><br>
											<div id="show_bmi_links" class="col-xs-4 col-sm-4 col-md-4 col-lg-4 hide" >
									
											</div>
											<form style="display: hidden" action="drill_down_to_bmi_report_students" method="POST" id="form_for_bmi_report">
												<input type="hidden" id="ehr_data_for_bmi" name="ehr_data_for_bmi" value=""/>
												<input type="hidden" id="selectedMonth" name="selectedMonth" value=""/>
												<input type="hidden" id="ehr_navigation_for_bmi" name="ehr_navigation_for_bmi" value=""/>

												
											</form>
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
							×
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
							×
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
	
	var bmi_data = "";
	var bmi_navigation = [];
	previous_request_a_value = [];
	previous_request_fn = [];
	previous_request_title_value = [];
	previous_request_search = [];
	request_search_arr = [];
	


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
		
		if(dt_name == "select")
		{
			$.SmartMessageBox({
				title : "Alert!",
				content : "Please Select District Inorder to View BMI PIE",
				buttons : '[OK]'
			})
		}

		current_month = $('#set_data').val();
		dt_name = $("#select_dt_name option:selected").text();
		school_name = $("#school_name option:selected").text();

		$.ajax({
			url: 'bmi_pie_view_month_wise',
			type: 'POST',
			data: { "current_month":current_month, "dt_name" : dt_name,"school_name":school_name},
			
			success: function (data) 
			{
			  $('#load_waiting').modal('hide');
			  $('#text_msg').modal('hide');
			  $( "#pie_request" ).empty();
			  data = $.parseJSON(data);
			  bmi_report = $.parseJSON(data.bmi_report);
			  bmi_reported_schools = $.parseJSON(data.bmi_reported_schools);
			  
				if(bmi_report == 1 || bmi_reported_schools == 1){
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
					bmi_request_pie(bmi_report,"drill_down_bmi_to_students");
					
					// 
					
					
					var show_bmi_links = '<div class="well well-sm well-light"><label class="form-control"> <a href="javascript:void(0)" class="bmi_submitted_schools_list">Submitted Schools &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</a> <span class="bmi_submitted_schools_count"></span></label><label class="form-control"> <a href="javascript:void(0)" class="bmi_not_submitted_schools_list"> Not Submitted Schools : </a><span class="bmi_not_submitted_schools_count"></span></label></div>';
					$('#show_bmi_links').html(show_bmi_links);
					
					$('.bmi_submitted_schools_count').html(bmi_reported_schools.submitted_count);
					$('.bmi_not_submitted_schools_count').html(bmi_reported_schools.not_submitted_count);
									
					// BMI submitted Schools Names in modal
					$('.bmi_submitted_schools_list').click(function(){
						
						bmi_submitted_schools_list = bmi_reported_schools.submitted.school;
						if(bmi_submitted_schools_list!=null)
						{
								$('#bmi_submitted_school_modal_body').empty();
								var table="";
								var tr="";
								table += "<table class='table table-bordered' id='bmi_sent_school_modal_body_tab'><thead><tr><th>S.No</th><th> School Name </th></tr></thead><tbody>";
								for(var i=0;i<bmi_submitted_schools_list.length;i++)
								{
									var j=i+1;
									table+= "<tr><td>"+j+"</td><td>"+bmi_submitted_schools_list[i]+"</td></tr>"
								}
								table += "</tbody></table>";
								$(table).appendTo('#bmi_submitted_school_modal_body');
						}
						else
						{
								table+="No Schools";
								$(table).appendTo('#bmi_submitted_school_modal_body');
						}
						$('#bmi_submitted_school_modal').modal('show');
					})
					
					// BMI submitted Schools Names in modal
					$('.bmi_not_submitted_schools_list').click(function(){
						
						bmi_not_submitted_schools_list = bmi_reported_schools.not_submitted.school;
						if(bmi_not_submitted_schools_list!=null)
						{
								$('#bmi_not_submitted_school_modal_body').empty();
								var table="";
								var tr="";
								table += "<table class='table table-bordered' id='bmi_not_sent_school_modal_body_tab'><thead><tr><th>S.No</th><th> School Name </th></tr></thead><tbody>";
								for(var i=0;i<bmi_not_submitted_schools_list.length;i++)
								{
									var j=i+1;
									table+= "<tr><td>"+j+"</td><td>"+bmi_not_submitted_schools_list[i]+"</td></tr>"
								}
								table += "</tbody></table>";
								$(table).appendTo('#bmi_not_submitted_school_modal_body');
						}
						else
						{
								table+="No Schools";
								$(table).appendTo('#bmi_not_submitted_school_modal_body');
						}
						$('#bmi_not_submitted_school_modal').modal('show');
					})
			}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
	});


function bmi_request_pie(data, onClickFn){
	var pie = new d3pie("pie_request", {
		header: {
			title: {
				text: bmi_navigation.join(" / ")
			}
		},
		size: {
	        canvasHeight: 350,
	        canvasWidth: 500 
			
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
					"#ff0000", "#356900", "#D3D300","#ff8000"
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
				
					if(onClickFn == "drill_down_bmi_to_students")
					{
						drill_down_bmi_to_students(a.data.label);
						 //$('#load_waiting').modal('show');
					}
					
				}
			}
	      
		});
}

function drill_down_bmi_to_students(label)
{
	dt_name = $("#select_dt_name option:selected").text();
	school_name = $("#school_name option:selected").text();
	
    $.ajax({
		url: 'drill_down_bmi_to_students',
		type: 'POST',
		data: {"case_type" : label, "current_month":current_month, "dt_name" : dt_name,"school_name":school_name},
		success: function (data) 
		{
			//console.log('SUCCESS_DATA',data);
			$('#load_waiting').modal('hide');
			$("#ehr_data_for_bmi").val(data);
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

 
});		
		

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

