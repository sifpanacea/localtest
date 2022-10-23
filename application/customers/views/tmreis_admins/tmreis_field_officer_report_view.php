<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Tmreis Field Officer";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa field_officer"]["active"] = true;
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
		//$breadcrumbs["New Crumb"] => "https://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
	
		
		
		
		
		<div class="row">
		<div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<!-- new widget -->
					<div class="jarviswidget" id="wid-id-1000" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						
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
												<input type="text" id="set_data" name="set_data" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $today_date; ?>">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
											</section>
					                    
										</div>
												<div class="row">
													
													
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
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<!-- new widget -->
						<div class="jarviswidget" id="wid-id-1000" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						</article>
					</div>
				
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<!-- new widget -->
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-60" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
							<h2>Field Officer Report</h2>

						</header>

						<!-- widget div-->
			
		<!-- widget grid -->
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-sm-12">
					<!-- new widget -->
					<div class="jarviswidget" id="wid-id-100" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						
						

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
								<!-- content -->
								<div id="myTabContent" class="tab-content">
								<div class="row">
								<br>
							
							<div class="col-xs-12">
								<div class="well well-md well-light">
								
								<div class="col-md-12" id="loading_req_pie" style="display:none;">
									<center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
								</div>
								
								<div id="pie_field_off">
								<?php echo 'No positive values to display'; ?>
								
								<div class="row">
								
								
										<br>
										<div >							
											<div id="pie">
											
											</div>
											<form style="display: hidden" action="tmreis_drill_down_to_filed_officer_docs" method="POST" id="form_for_field_officer">
												<input type="hidden" id="case_type" name="case_type" value=""/>
												<input type="hidden" id="to_date" name="to_date" value=""/>
												<input type="hidden" id="school_code" name="school_code" value=""/>
												<input type="hidden" id="ehr_navigation_for_field_officer" name="ehr_navigation_for_field_officer" value=""/>
											</form>
										</div>
									</div>
								</div>
								
								</div>
								<div class="row">
								<br>
								</div>
								</div>
							</div>
							</div>
							</div>
								<!-- end content -->
							</div>

						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
                 </div>
				</article>
				
			<!-- row -->

			<!-- end row -->
				</div>
				
				
				<!-- Modal -->
					
			<!--</div> end row -->
		</section>

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
	
	var today_date = $('#set_data').val() || "";
	/* if(today_date!="")
	{
		$("#set_date_btn").trigger("click")
		$("#set_date_btn").click()
	} */
	
	var field_officer_report   = <?php echo $field_officers; ?>;
	console.log('field_officer_report==log',field_officer_report);
	var todays_date = <?php echo $today_date; ?>;
	console.log('TODATE IS:',todays_date);
	
	/* if(field_officer_report.val==null)
	{
		
		$("#pie").append('No positive values to dispaly today');
	} */
	
	var field_officer_navigation = [];
	draw_field_officer_pie();
	
	function field_officer_pie(heading, data, onClickFn){
	
	var pie = new d3pie("pie", {
			header: {
			title: {
				text: field_officer_navigation.join(" / ")
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth: 400
	    },
	    data: {
	      content: field_officer_report
		  
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
					$("#case_type").val(a.data.label);
					$("#to_date").val(today_date);
					console.log(data);
					field_officer_navigation.push(heading);
					field_officer_navigation.push(a.data.label);
					$("#ehr_navigation_for_field_officer").val(field_officer_navigation.join(" / "));
					$("#form_for_field_officer").submit();
				}
			}
	      
		});
}

function draw_field_officer_pie(){
	
	if(field_officer_report == 1)
	{
		$("#pie_field_off").append('No positive values to dispaly');
	}
	else
	{	
		//field_officer_navigation.push(today_date);
		field_officer_pie(today_date,"Field Officer Report",field_officer_report);
	}
}

/* function drill_down_to_field_officer(search_arr){

	$.ajax({
		url: 'drill_down_to_filed_officer_docs',
		type: 'POST',
		data: {"type":search_arr[1]},
		success: function (data) {
			console.log(data);
			$("#ehr_data_for_field_officer").val(data);
			field_officer_navigation.push(search_arr[0]);
			$("#ehr_navigation_for_field_officer").val(field_officer_navigation.join(" / "));
			//window.location = "drill_down_screening_to_students_load_ehr/"+data;
			//alert(data);
			
			//$("#ehr_form_for_absent").submit();
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
} */

$(document).on("click",'#field_off_back_btn',function(e){
	
		
		var index = $(this).attr("ind");
		console.log("in back functionnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn-----------");
		console.log(index);
		//$( "#pie_field_off" ).empty();
		if(index>1){
			var ind = index - 1;
		$("#pie_field_off").append('<button class="btn btn-primary pull-right" id="field_off_back_btn" ind= "' + ind + '"> Back </button>');
		}
		field_officer_navigation.pop();
		field_officer_pie();
		
});

$(document).on('click','#set_date_btn',function(e){
	today_date = $('#set_data').val();

	$.ajax({
		url: 'tmreis_field_officer_with_date',
		type: 'POST',
		data: {"today_date" : today_date},
		success: function (data) {
			
			//$('#load_waiting').modal('hide');
			//$( "#pie_field_off" ).empty();
			if(data!=1)
			{
			$( "#pie" ).empty(); 
			data = $.parseJSON(data);
			console.log('checking_352',data);
			field_officer_report = data;
			draw_field_officer_pie();
			}
			else
			{
				$("#pie").empty();
				//$("#pie").append('No positive values to dispaly');
			}
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});
})

</script>
