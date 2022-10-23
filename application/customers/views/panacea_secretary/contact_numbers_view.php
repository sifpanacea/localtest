<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Contact Numbers";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["contact_numbers"]["active"] = true;
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
							<h2>Contact Numbers</h2>
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
								<br>
								
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									
										<br>
										
										<!-- widget content -->
										<div class="col-md-12" id="loading_request_pie" style="display:none;">
												<center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
											</div>
											
											<div class="col col-lg-6" id="contact_no"></p></div>

											

											<form style="display: hidden" action="drill_down_to_contacts_list" method="POST" id="form_for_contacts">
												<input type="hidden" id="contact_type" name="contact_type" value=""/>
												
												<input type="hidden" id="ehr_navigation_for_contacts" name="ehr_navigation_for_contacts" value=""/>

												
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
	

	var request_data = "";
	var contact_navigation = [];
	previous_request_a_value = [];
	previous_request_fn = [];
	previous_request_title_value = [];
	previous_request_search = [];
	request_search_arr = [];
	
	initialize_variables(<?php echo json_encode($contact_numbers); ?>);
	
	function initialize_variables(contact_numbers){

	init_request_pie(contact_numbers);
	}
	
draw_contact_number_pie();

function init_request_pie(contact_numbers){
	request_data = contact_numbers;
	contact_navigation = [];
	previous_request_a_value = [];
	previous_request_fn = [];
	previous_request_title_value = [];
	previous_request_search = [];
	request_search_arr = [];
}


function draw_contact_number_pie(){
	if(request_data == 1){
		$("#pie_request").append('No positive values to dispaly');
	}else{
		contact_navigation.push("Contact Numbers");
	contact_numbers_pie(request_data,"drill_down_request_to_symptoms");
}
}
	



function contact_numbers_pie(data, onClickFn){
	var pie = new d3pie("contact_no", {
		header: {
			title: {
				text: contact_navigation.join(" / ")
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
		
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
			callbacks: {
				onClickSegment: function(a) {
					
					$("#contact_type").val(a.data.label);
					console.log(data);
					//contact_navigation.push(heading);
					contact_navigation.push(a.data.label);
					$("#ehr_navigation_for_contacts").val(contact_navigation.join(" / "));
					$("#form_for_contacts").submit();


				}
			}
	      
		});
}

function drill_down_to_contacts(label)
{
	    $.ajax({
		url: 'drill_down_to_contacts',
		type: 'POST',
		data: {"contact_type" : label },

		success: function (data) 
		{
			console.log('SUCCESS_DATA',data);
			$('#load_waiting').modal('hide');
			$("#ehr_data_for_contacts").val(data);
		
			contact_navigation.push(label);
			$("#ehr_navigation_for_bmi").val(contact_navigation.join(" / "));
			$("#form_for_contacts").submit();
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});
}
 
});		
		

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

