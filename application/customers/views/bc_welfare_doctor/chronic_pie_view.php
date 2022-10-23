<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Chronic PIE";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["request_pie"]["active"] = true;
include("inc/nav.php");

?>
<style>
#compliance_legend
{
	background-color: #fff;
    padding: 2px;
    margin-bottom: 8px;
    border-radius: 3px 3px 3px 3px;
    border: 1px solid #E6E6E6;
    display: inline-block;
    margin: 0 auto;
}

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
				
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
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
										
											<div id="pie_request"></div>
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
	
	initialize_variables(<?php echo $request_report?>);
	
	function initialize_variables(request_report){

	init_request_pie(request_report);
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
	        canvasHeight: 250,
	        canvasWidth: 450
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
					if(onClickFn == "drill_down_request_to_symptoms"){
						console.log(a);
						previous_request_a_value[0] = data;
						previous_request_fn[0] = "drill_down_request_to_symptoms";
						drill_down_request_to_symptoms(a);
					}else if(onClickFn == "drilldown_chronic_request_to_districts"){
						console.log(a);
						previous_request_a_value[1] = data;
						previous_request_fn[1] = "drilldown_chronic_request_to_districts";

						previous_request_search[0] = request_navigation.join(" / ");
						request_search_arr[0] = previous_request_search[0];
						request_search_arr[1] =  a.data.label;
						drilldown_chronic_request_to_districts(request_search_arr);
					}else if(onClickFn == "drilldown_chronic_request_to_school"){
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
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie_request" ).empty();
			$("#pie_request").append('<button class="btn btn-primary pull-right" id="request_back_btn" ind= "0"> Back </button>');

			request_navigation.push(pie_data.data.label);
			request_pie(content,"drilldown_chronic_request_to_districts");
			
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
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
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
		

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

