<?php 
//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "HS Requests";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav['request_status']['active'] = TRUE;
include("inc/nav.php");

?>

<style>


</style>

<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["HS Requests count"] = "";
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="">
			<article class="col-sm-12 col-md-12 col-lg-12">
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
								<header>
									
								</header>
				
								<div class='row'> 
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
										<fieldset>
											<section>
											<div class="input-group">
												<input type="text" id="set_date" name="set_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo date('Y-m-d')?>">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
											</section>
											</fieldset>
											</br>
										 <button type="button" class="btn btn-success" id="set_date_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false" style="padding: 6px;">
					                      Set date
					                    </button>
										</div>
										</div>
										<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
										
										<!-- widget content -->
									<div class="widget-body">
				
										<hr class="simple">
										<ul id="myTab1" class="nav nav-tabs bordered">
											<li class="active">
												<a href="#s1" data-toggle="tab">HS Initiated Request Count <span class="badge bg-color-blue txt-color-white" id="hs_initiated"></span></a>
											</li>
											<li>
												<a href="#s2" data-toggle="tab"> Doctor Response Count <span class="badge bg-color-blue txt-color-white" id="doctors"></span></a>
											</li>
											
										</ul>
				
										<div id="myTabContent1" class="tab-content padding-10">
											<div class="tab-pane fade in active" id="s1">
												<p id="hs_submitted_by">
													
												</p>
											</div>
											<div class="tab-pane fade" id="s2">
												
												<span id="submitted_by_name_dr1"></span>
												<span id="submitted_by_name_dr2"></span>
												<span id="submitted_by_name_dr3"></span>
												<span id="submitted_by_name_dr4"></span>
												<span id="submitted_by_name_dr6"></span>
												
											</div>
											
										</div>
				
									</div>
										</div>
										</div>
			
		</div>
	</div>
</div>
<!-- Modal -->
					<div class="modal fade" id="load_waiting" tabindex="-1" role="dialog" >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h2 class="modal-title" id="myModalLabel">Generating excel sheet, please wait!</h2>
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
</div>
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
	$(document).ready(function() {
		
		var today_date = $('#set_date').val();
		//console.log('php111111111111111', today_date);
		
		$('.datepicker').datepicker({
			minDate: new Date(1900, 10 - 1, 25)
		});
		
		change_to_default(today_date);

		function change_to_default(today_date){
			today_date = today_date;
			request_count_all();
		}
		
		function request_count_all(){
			
			$.ajax({
			url: 'initaite_requests_status_count',
			type: 'POST',
			data: {"today_date" : today_date},
			success: function (data) {			
				$('#load_waiting').modal('hide');
				result = $.parseJSON(data);
				console.log(result);
				document_details_list(result);
				
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
			
		}
		
		$('#set_date_btn').click(function(e){
			
			today_date = $("#set_date").val();
			console.log("today_date====",today_date);
			$.ajax({
			url: 'initaite_requests_status_count',
			type: 'POST',
			data: {"today_date" : today_date},
			success: function (data) {			
				$('#load_waiting').modal('hide');
				$("#submitted_by_name_dr1").html("");
				$("#submitted_by_name_dr2").html("");
				$("#submitted_by_name_dr3").html("");
				$("#submitted_by_name_dr4").html("");
				//$("#submitted_by_name_dr5").html("");
				$("#submitted_by_name_dr6").html("");
				//$("#submitted_by_name_dr7").html("");
				result = $.parseJSON(data);
				document_details_list(result);
				//console.log(result);
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			})
		});
		
		 function document_details_list(result)
		{
			
			if((result.request_count) > 0 ){
				console.log("document_details_list",result)
				initiate_count = result.request_count;
				doctors_count = result.doctors_count;
				//submitted_by = result.submitted_by;
				doctor_name_dr1 = result.doctor_name_dr1;
				doctor_name_dr2 = result.doctor_name_dr2;
				doctor_name_dr3 = result.doctor_name_dr3;
				doctor_name_dr4 = result.doctor_name_dr4;
				//doctor_name_dr5 = result.doctor_name_dr5;
				doctor_name_dr6 = result.doctor_name_dr6;
				//doctor_name_dr7 = result.doctor_name_dr7;
				doctors_count_dr1 = result.doctors_count_list_dr1;
				doctors_count_dr2 = result.doctors_count_list_dr2;
				doctors_count_dr3 = result.doctors_count_list_dr3;
				doctors_count_dr4 = result.doctors_count_list_dr4;
				doctors_count_dr5 = result.doctors_count_list_dr5;
				doctors_count_dr6 = result.doctors_count_list_dr6;
				doctors_count_dr7 = result.doctors_count_list_dr7;
				
				$("#hs_initiated").html(initiate_count);
				$("#doctors").html(doctors_count);
				$("#hs_submitted_by").html("<h6> All Schools Initaited Request Count : "+initiate_count+"</h6>");
				if((doctors_count_dr1)>0)
				{
					$("#submitted_by_name_dr1").html("<h6>"+doctor_name_dr1+"&nbsp;&nbsp;Response count :&nbsp;&nbsp;" +doctors_count_dr1 + "</h6>");
				}
				if((doctors_count_dr2)>0)
				{
					$("#submitted_by_name_dr2").html("<h6>"+doctor_name_dr2+"&nbsp;&nbsp;Response count :&nbsp;&nbsp;" +doctors_count_dr2 + "</h6>");
				}
				if((doctors_count_dr3)>0)
				{
					$("#submitted_by_name_dr3").html("<h6>"+doctor_name_dr3+"&nbsp;&nbsp;Response count :&nbsp;&nbsp;" +doctors_count_dr3 + "</h6>");
				}
				if((doctors_count_dr4)>0)
				{
				$("#submitted_by_name_dr4").html("<h6>"+doctor_name_dr4+"&nbsp;&nbsp;Response count :&nbsp;&nbsp;" +doctors_count_dr4 + "</h6>");
				}
				/* if((doctors_count_dr5)>0)
				{
					$("#submitted_by_name_dr5").html("<h6>"+doctor_name_dr5+"&nbsp;&nbsp;Response count :&nbsp;&nbsp;" +doctors_count_dr5 + "</h6>");
				} */
				if((doctors_count_dr6)>0)
				{
					$("#submitted_by_name_dr6").html("<h6>"+doctor_name_dr6+"&nbsp;&nbsp;Response count :&nbsp;&nbsp;" +doctors_count_dr6 + "</h6>");
				}
				/* if((doctors_count_dr7)>0)
				{
					$("#submitted_by_name_dr7").html("<h6>"+doctor_name_dr7+"&nbsp;&nbsp;Response count :&nbsp;&nbsp;" +doctors_count_dr7 + "</h6>");
				} */				
		}
		else
			{
				initiate_count = result.request_count;
				doctors_count = result.doctors_count;
				$("#hs_initiated").html(initiate_count);
				$("#doctors").html(doctors_count);
				$("#hs_submitted_by").html("<h6>No Initiate Requests</h6>");
				//$("#submitted_by_name_dr1").html("<h6>No Initiate Requests</h6>");
				//$("#submitted_by_name_dr4").html("<h6>No Initiate Requests</h6>");
			}
		} 
		
		/* function document_details_list(result)
		{
			if((result.request_count) > 0 )
			{
				$.each(result,function(i,value){
					initiate_count = result.request_count;
					doctors_count = result.doctors_count;
					//submitted_by = result.submitted_by;
					doctor_name = result.doctor_name;
					//doctor_name_dr4 = result.doctor_name_dr4;
					doctors_counts = result.doctors_count_list;
					//doctors_count_dr4 = result.doctors_count_list_dr4;
					console.log("valuesssssss",value);
					$('#submitted_by_name_dr').html("<h6>"+doctor_name+"&nbsp;&nbsp;Response count &nbsp;&nbsp;" +doctors_counts+ "</h6>");
					//$('#submitted_by_name_dr4').html("<h6>"+doctor_name+"&nbsp;&nbsp;Response count &nbsp;&nbsp;" +doctors_counts+ "</h6>");
					$("#hs_initiated").html(initiate_count);
					$("#doctors").html(doctors_count);
				})
			}
				
		} */
		
		
	});
</script>