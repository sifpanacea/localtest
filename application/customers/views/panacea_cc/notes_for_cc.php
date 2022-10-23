<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Panacea Dashboard";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa notes_cc"]['sub']["raise_notes"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="row">
		<div class="col-xs-12 col-sm-4 col-md-10 col-lg-12">
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<!-- <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-comments"></i> </span>
						<h2>Default Tabs with border </h2>
					</header>
				</div> -->
				<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
						<h2>Save Notes For Requests</h2>		
					</header>
				

					<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->
					<div class="row">
						<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
							<div class="well well-sm well-light">
								<?php
								$attributes = array('class' => 'smart-form','id'=>'web_view','name'=>'userform');
								echo  form_open_multipart('panacea_cc/save_cc_notes_ref',$attributes);
								?>
									<fieldset>
										<div class="row">
											<section class="col col-3">
												<label class="label" for="">Type Of Request</label>
												<label class="select">
													<select id="request_type" name="request_type">
														<option value="Normal">Normal</option>
														<option value="Emergency">Emergency</option>
														<option value="Chronic">Chronic</option>
													</select> <i></i>
												</label>
											</section>
											<section class="col col-3">
												<label class="label" for="">SubType Of Request</label>
												<label class="select">
													<select id="subtype" name="subtype"></select>
												</label>
											</section>
											<section class="col col-3">
												  <label class="label" for="tags">School Name </label>
												  <input type="select" class="form-control school_name" id="school_name" name="school_name">

											</section>
										</div>
										<div class="row">
											<section  class="col col-4">
												<label class="label" for="">Student Name</label>
												<input class="form-control student_name" list="student_name" name="student_name" />

												<datalist id="student_name" name="">
												</datalist>
												<button type="button" class="btn btn-primary student_requests">get data</button>

											</section>
											<section  class="col col-4">
												<label class="label" for="">Problem Info</label>
												<textarea rows="2" cols="80" class="form-control custom-scroll" id="problem_info" name="problem_info"></textarea>
											</section>
											<section class="col col-2">
												<label class="label">Status</label>
												<label class="select">
												<select name="status_info" id="status_info">
													<option value="0">Choose an option</option>
													<option selected value="Initiated">Initiated</option>
													<option value="Prescribed">Prescribed</option>
													<option value="Follow-up">Follow-up</option>
													<option value="Under Medication">Under Medication</option>
													<option value="Cured">Cured</option>
													<option value="Hospitalized">Hospitalized</option>
												</select> <i></i> </label>
											</section>
											<section class="col col-2">
												<label class="label">If already request raised By HS</label> 
												<input type="checkbox" class="form-control" name="req_already_raised" value="Yes">
											</section>
										</div>

										<div class="row">
											<section class="col col-4">
												<div id="studentReqData"></div>
											</section>
											
										</div>
									</fieldset>
								
								<center><button type="submit" class="btn btn-primary">Save and Raise a Request</button></center>

								<?php echo form_close();?>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			</article>
		</div>
	<!-- Calls data saving -->
		<div class="col-xs-12 col-sm-4 col-md-10 col-lg-12">
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<!-- <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-comments"></i> </span>
						<h2>Default Tabs with border </h2>
					</header>
				</div> -->
				<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
					<header>
						<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
						<h2>Calls Data</h2>		
					</header>
				

					<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->
					<div class="row">
						<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
							<div class="well well-sm well-light">
								<?php
								$attributes = array('class' => 'smart-form','id'=>'web_view','name'=>'userform');
								echo  form_open_multipart('panacea_cc/save_cc_calls_count',$attributes);
								?>
									<fieldset>
										<div class="row">
											<section class="col col-3">
												<label class="label" for="">Purpose</label>
												<label class="select">
													<select id="purpose_type" name="purpose_type">
														<option value="Normal">Normal</option>
														<option value="Emergency">Emergency</option>
														<option value="Chronic">Chronic</option>
														<option value="Attendance">Attendance</option>
														<option value="Technical">Technical</option>
													</select> <i></i>
												</label>
											</section>
											<section class="col col-3">
												<label class="label" for="">Spoke with</label>
												<label class="select">
													<select id="call_options" name="call_options"></select>
												</label>
											</section>
											
										</div>
										
									</fieldset>
								
								<center><button type="submit" class="btn btn-primary">Save a call</button></center>

								<?php echo form_close();?>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			</article>
		</div>

	</div>



	</div>
<!-- END MAIN PANEL -->
</div>	






<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>



<?php 
	//include footer
	include("inc/footer.php"); 
?>






<script type="text/javascript">
	/* TO get schools list*/
	$( function() {
		$.ajax({
			url: 'get_schools_list_only',
			type: 'POST',
			data: "",
			success: function (data) {			

				var availableTags = $.parseJSON(data);
				console.log('schoolsNames', availableTags);

				$( "#school_name" ).autocomplete({
				  source: availableTags
				});
						
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
	 
	 
	} );

	/*$('.display').DataTable({
    	"ordering":false
    });*/
</script>

<script type="text/javascript">
$(document).ready(function() {



change_request_type();

change_call_type();

$('#request_type').change(function(){
	change_request_type();
});

function change_request_type(){

	
	var type = $('#request_type option:selected').val();

	var options = $("#subtype");

	if( type == "Normal")
	{
		options.empty();
		options.append($("<option />").val("IP").text("IP"));
		options.append($("<option />").val("OP").text("OP"));
		options.append($("<option />").val("Sick").text("Sick"));
		options.append($("<option />").val("Review").text("Review"));
	}
	else if( type == "Emergency")
	{
		options.empty();
		options.append($("<option />").val("IP").text("IP"));
		options.append($("<option />").val("OP").text("OP"));
		//options.append($("<option />").val("IP").text("Sick"));
		//options.append($("<option />").val("IP").text("Review"));
	}
	else
	{
		options.empty();
		options.append($("<option />").val("IP").text("IP"));
		options.append($("<option />").val("OP").text("OP"));
		options.append($("<option />").val("Sick").text("Sick"));
		options.append($("<option />").val("Review").text("Review"));
	}
}

$('#purpose_type').change(function(){
	change_call_type();
});

function change_call_type(){

	var type = $('#purpose_type option:selected').val();

	
	var options = $("#call_options");

	if( type == "Normal")
	{
		options.empty();
		options.append($("<option />").val("Doctor").text("Doctor"));
		options.append($("<option />").val("HS").text("HS"));
		options.append($("<option />").val("ACT").text("ACT"));
		options.append($("<option />").val("Principal").text("Principal"));
		options.append($("<option />").val("RHSO").text("RHSO"));
		options.append($("<option />").val("House Master").text("House Master"));
		options.append($("<option />").val("Parents").text("Parents"));


	}else if(type == "Emergency")
	{
		options.empty();
		options.append($("<option />").val("Project Head").text("Project Head"));
		options.append($("<option />").val("Doctor").text("Doctor"));
		options.append($("<option />").val("HS").text("HS"));
		options.append($("<option />").val("RHSO").text("RHSO"));
		options.append($("<option />").val("ACT").text("ACT"));
		options.append($("<option />").val("Principal").text("Principal"));
		options.append($("<option />").val("House Master").text("House Master"));
		options.append($("<option />").val("Parents").text("Parents"));


	}else if(type == "Chronic")
	{

		options.empty();
		options.append($("<option />").val("Doctor").text("Doctor"));
		options.append($("<option />").val("Parents").text("Parents"));
		options.append($("<option />").val("HS").text("HS"));
		options.append($("<option />").val("ACT").text("ACT"));
		options.append($("<option />").val("Principal").text("Principal"));
		options.append($("<option />").val("RHSO").text("RHSO"));
		options.append($("<option />").val("House Master").text("House Master"));

	}else if(type == "Attendance")
	{
		options.empty();
		options.append($("<option />").val("HS").text("HS"));
		options.append($("<option />").val("ATC").text("ATC"));
		options.append($("<option />").val("Principal").text("Principal"));
		options.append($("<option />").val("House Master").text("House Master"));
		options.append($("<option />").val("RHSO").text("RHSO"));

	}else if(type == "Technical")
	{
		options.empty();
		options.append($("<option />").val("Hyndhavi").text("Hyndhavi"));
		options.append($("<option />").val("Yoga").text("Yoga"));
		/*options.append($("<option />").val("Sick").text("Sick"));
		options.append($("<option />").val("Review").text("Review"));*/

	}
}



	/* get Students By School Name*/
	$('.school_name').change(function(e){

		//dist = $('#school_name').val();
		scl_name = $(".school_name").val();
		
		var options = $("#student_name");
		//options.prop("disabled", true);
		
		//options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
		$.ajax({
			url: 'get_students_list_only',
			type: 'POST',
			data: {"school_name" : scl_name},
			success: function (data) {			

				result = $.parseJSON(data);
				//console.log('get studets after click',result);

				//options.prop("disabled", false);
				options.empty();
				options.append($("<option />").val("All").prop("selected", true).text("All"));
				$.each(result, function() {
				    options.append($("<option />").val(this.doc_data.widget_data["page1"]['Personal Information']['Name']+' , '+ this.doc_data.widget_data["page1"]['Personal Information']['Hospital Unique ID']+' , '+this.doc_data.widget_data["page2"]['Personal Information']['Class']+' , '+this.doc_data.widget_data["page2"]['Personal Information']['Section']));
				});
						
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
	});

	$('.student_requests').click(function(e){

		var stud_id = $(".student_name").val();
		//alert(stud_id);

		$.ajax({
			url:'get_student_recent_requests_data',
			method:'POST',
			data:{"stud":stud_id},
			success:function(data){
				var result = $.parseJSON(data);
				var totalReq = result.totalReqCount;

				if(totalReq == 0){
					var recentReq = 0;
				}else{

				var recentReq = result.recent_req[0]['history'][0]['time'];
				}

				$('#studentReqData').html('<table class="table table-bordered"><thead><tr><td>Recent Requests Raised</td><td>TOtal Requests Count</td></tr></thead><tbody><tr><td>'+recentReq+'</td><td>'+totalReq+'</td></tr></tbody></table>');

			}
 
		})
	});

});
</script>

