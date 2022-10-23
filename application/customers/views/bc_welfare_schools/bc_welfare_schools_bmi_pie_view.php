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
$page_nav["pa feed_bmi"]["sub"]["bmi_pie_view"]["active"] = true;
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
							<h2>BMI PIE</h2>
							
						</header>

						<!-- widget div-->
						<div>
							

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
										
											<section>
											<label class="label">Select Month</label>
												<div class="form-group col col-6">
													<div class="input-group">
													<input type="text" id="set_data" name="set_data" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $bmi_submitted_month?>">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													</div>
												</div>
												
											</section>
											<section>
														<button type="button" class="btn bg-color-blue txt-color-white btn-sm" id="set_month_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false" style="margin-top: -8px;">
												   Set
												</button>
										
														<button type="button" class="btn bg-color-pink txt-color-white btn-sm pull-right hide" id="bmi_export_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false" style="margin-top: -8px;">
												   Export to Excel
												</button>
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
											
											
											<div class="pull-right" id="image-formula">
												<h5>Reference by</h5>
												<span>
												<img src="../../uploaddir/public/images.png" alt="" style="height:80px;margin-top:6px;margin-left:6px;">
												 </span>
												 <h5>BMI Interpretation</h5>
												 <span>
												<img src="../../uploaddir/public/bmi_range.jpg" alt="" style="width:250px;height:120px;margin-top:6px;margin-left:6px;">
												 </span>
												<!-- <h6><span>Source : <a href="http://apps.who.int/bmi/index.jsp?introPage=intro_3.html" target="_blank">World Health Organisation</a></span></h6> -->
											</div>
										
											<center><div id="pie_request"></div></center>
											<i><label id="request_note" style="display:none;">Note : Write something.</label></i>
											<form style="display: hidden" action="drill_down_to_bmi_report_students" method="POST" id="form_for_bmi_report">
												<input type="hidden" id="ehr_data_for_bmi" name="ehr_data_for_bmi" value=""/>
												<input type="hidden" id="selectedMonth" name="selectedMonth" value=""/>
												
												<input type="hidden" id="ehr_navigation_for_bmi" name="ehr_navigation_for_bmi" value=""/>

												
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
	
	var bmi_data = "";
	var bmi_navigation = [];
	previous_request_a_value = [];
	previous_request_fn = [];
	previous_request_title_value = [];
	previous_request_search = [];
	request_search_arr = [];
	
	initialize_variables(<?php echo $bmi_report?>);
	//change_to_default();

/* function change_to_default(bmi_report){
	draw_bmi_pie();
	/* $('#select_dt_name').val("All");
	$('#school_name').val("All");
 */
//} 
function initialize_variables(bmi_report){

	init_bmi_pie(bmi_report);

	}

function init_bmi_pie(bmi_report){
	bmi_data = bmi_report;
	bmi_navigation = [];
	previous_request_a_value = [];
	previous_request_fn = [];
	previous_request_title_value = [];
	previous_request_search = [];
	request_search_arr = [];
}

draw_bmi_pie();
function draw_bmi_pie(){
	if(bmi_data == 1){
		$("#pie_request").append('No positive values to dispaly');
		$('#image-formula').hide();
	}else{
		console.log("bmi_data",bmi_data);
		$('#image-formula').show();
		$('button').removeClass('hide');
		bmi_navigation.push("BMI PIE");
	bmi_request_pie(bmi_data,"drill_down_bmi_to_students");
}
}



$('#set_month_btn').click(function (e){
	
	current_month = $('#set_data').val();
	
	$.ajax({
		url: 'bmi_pie_view_month_wise',
		type: 'POST',
		data: { "current_month":current_month},
		
		success: function (data) 
		{
		  $('#load_waiting').modal('hide');
		
		  $( "#pie_request" ).empty();
		  //console.log('dataaaaaaaaaaaaaaa',data);
          data = $.parseJSON(data);

		  bmi_report = $.parseJSON(data.bmi_report);
		  initialize_variables(bmi_report);
		  draw_bmi_pie();
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
			"pieInnerRadius": "10%",
			"pieOuterRadius": "60%"
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
					//"#487BC0", "#829C05", "#EDA336","#C3252A"
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
				
					if(onClickFn == "drill_down_bmi_to_students")
					{
						drill_down_bmi_to_students(a.data.label);
					}
					
				}
			}
	      
		});
}

function drill_down_bmi_to_students(label)
{
	
    $.ajax({
		url: 'drill_down_bmi_to_students',
		type: 'POST',
		data: {"case_type" : label, "current_month":current_month},
		success: function (data) 
		{
			
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
	
		$.ajax({
			url: 'generate_bmi_report_to_excel',
			type:'POST',
			data:{"current_month": current_month},
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



/* $(function() {
    $('.date-picker').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
    });
});
 */
 
});		
		

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

