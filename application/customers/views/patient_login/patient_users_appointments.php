<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Doctor's Appointment";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["appointment"]["sub"]["search_user"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL --><link href="<?php //echo(CSS.'datepicker.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<style>
.datepicker{ z-index: 1040 !important;}
</style>

<style>
.txt-color-bluee
{
color:#214e75;!important
}
</style>

<link href="<?php echo CSS; ?>user_dash.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>smartadmin-production.css"/>
<div id="main" role="main">
<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<div id="content">
<!-- 	<div class="row"> -->
	<!-- col -->
<!-- 	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4"> -->
<!-- 		<h1 class="page-title txt-color-blueDark"> -->
			
			<!-- PAGE HEADER -->
<!-- 			<i class="fa-fw fa fa-user-md"></i>  -->
				<?php echo $username; ?>
			
<!-- 		</h1> -->
<!-- 	</div> -->
	<!-- end col -->
<!-- 	</div> -->
<!-- 	<div class="row"> -->
<!-- 		<div class="col-md-2 col-lg-4"> -->
<!-- 			<p id="re_sche_note"></p> -->
<!-- 		</div> -->
<!-- 	</div> -->
	<!-- row -->
<!-- 	<div class="row"> -->
<!-- 	<div class="col-sm-4"> -->
<!-- 		<div class="form-group"> -->
<!-- 			<div class="input-group"> -->
<!-- 				<input type="text" name="mydate" placeholder="Select a date" class="datepicker form-control appointment_date" data-dateformat="mm/dd/yy" id="appointment_date" readonly='true'> -->
<!-- 				<span class="input-group-addon"><i class="fa fa-calendar"></i></span> -->
<!-- 			</div> -->
<!-- 		</div> -->
<!-- 	</div> -->
<!-- 	</div> -->
	<!-- end row -->
<!-- 	<div class="row" id='select_date'> -->
<!-- 		<div class="col-md-2 col-lg-4 table_data"> -->
<!-- 			<p>Please select a date from above to start.</p> -->
<!-- 		</div> -->
<!-- 	</div> -->
	
	
	
<!-- 	//=================================================== -->
	
<div class="row">
	<!-- NEW WIDGET START -->
	<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

	<!-- Widget ID (each widget will need unique ID)-->
	<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
				
		<header>
			<span class="widget-icon"> <i class="fa-fw fa fa-user-md"></i> </span>
			<h2><?php echo $username; ?></h2>
		</header>

		<!-- widget div-->
		<div>

			<!-- widget edit box -->
			<div class="jarviswidget-editbox">
				<!-- This area used as dropdown edit box -->

			</div>
			<!-- end widget edit box -->

			<!-- widget content -->
			<div class="widget-body no-padding">
					
<!-- 					content startsssssssssssssssssssssssssssssssss -->
	<form class="smart-form">
	<fieldset>
	<div class="row">
		<section class="col col-12">
			<p id="re_sche_note"></p>
		</section>
	</div>
	<div class="row">
		<section class="col col-12">
			<div class="form-group">
			<div class="input-group">
				<input type="text" name="mydate" placeholder="Select a date" class="datepicker form-control appointment_date" data-dateformat="dd/mm/yy" id="appointment_date" readonly='true'>
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
		</div>
		</section>
	</div>
	<div class="row">
		<section class="col col-12">
			<p id='select_date'>Please select a date from above to start.</p>
		</section>
	</div>
	<div id='time_slots'>
	<div id='legends'>
	<div class="row">
	<section class="col col-12">
		Legends:
	</section>
	</div>
	<div class="row">
	<section class="col col-12">
		<ul class="list-unstyled">
			<li>
			<svg height="10" width="10">
			  <circle cx="5" cy="5" r="5" stroke-width="0" fill="#616161" />
			</svg>&nbsp;&nbsp;&nbsp;Appointment Booked
			</li>
			<li>
			<svg height="10" width="10">
			  <circle cx="5" cy="5" r="5" stroke-width="0" fill="#01579B" />
			</svg>&nbsp;&nbsp;&nbsp;Appointment booked by you</li>
			<li>
			<svg height="10" width="10">
			  <circle cx="5" cy="5" r="5" stroke-width="0" fill="#00C853" />
			</svg>&nbsp;&nbsp;&nbsp;Slot free to book appoitment</li>
		</ul>
	</section>
	</div>
	</div>
	<div class="row">
	<section class="col col-12">
		<p id='select_date'>Please click on any time slot below to book appointment.</p>
	</section>
	</div>
	<div id='appointment_table'>
	</div>
<input type="hidden" id="appointment_data" name="appointment_data" value='<?php echo $appointments;?>' />
<input type="hidden" id="userid" name="user_email" value="<?php echo $user_email;?>" />
<input type="hidden" id="user_name" name="username" value="<?php echo $username; ?>" />
<input type="hidden" id="re-schedule" name="re-schedule" value="" />
	</div>
	</fieldset>
</form>
					
<!-- 					content endddddddddddddddddddddddddddddddddddd -->

			</div>
			<!-- end widget content -->

		</div>
		<!-- end widget div -->

	</div>
	<!-- end widget -->
	</article>

</div>
	
<!-- 	//=================================================== -->
	
	
	
	
<!-- 	<div class="row" id='time_slots1'> -->
<!-- 	<div class="col-md-2 col-lg-4 table_data"> -->
<!-- <div class="table-responsive"> -->
<!-- <table class="table table-bordered"> -->
<!-- <thead> -->
<!-- <tr> -->
<!-- <th>Time</th> -->
<!-- <th>Select a slot</th> -->
<!-- </tr> -->
<!-- </thead> -->
<!-- <tbody id='appointment_table1'> -->
<!-- </tbody> -->
<!-- </table> -->
<!-- </div> -->
<!-- </div> -->

<!-- <input type="hidden" id="re-schedule" name="re-schedule" value="" /> -->
<!-- </div> -->
</div>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Appointment details</h4>
      </div>
      <div class="modal-body">
        <div class="widget-body">
        <form action="../../../patient_login/place_appointment" method='post' id='appointment_form' class="smart-form" novalidate="novalidate">
        <input type="hidden" id="appo_date" name="appo_date" value="" />
        <input type="hidden" id="appo_time" name="appo_time" value="" />
        <input type="hidden" id="user_email" name="user_email" value="<?php echo $user_email;?>" />
        <input type="hidden" id="username" name="username" value="<?php echo $username; ?>" />
        <fieldset>
			<section>
				<div class="row">
					<label class="label col col-3">Appointment purpose</label>
					<div class="col col-9">
						<label class="input"> <i class="icon-append fa fa-user"></i>
							<input type="text" name="text" id='appointment_title'>
						</label>
					</div>
				</div>
			</section>

			<section>
			</section>
		</fieldset>
        </form>
        </div>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-success" id='form_submit'>Place appointment</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      
    </div>

  </div>
</div>
</div>
<!-- END MAIN PANEL -->
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script>

$(document).ready(function() {
	//console.log("ready")
	$('#time_slots').hide();
	$('#select_date').show();
	var userid = $('#userid').val();
	$('#re-schedule').val("");
	
	//$('#appointment_data').val() = '';
	
	$(document).on("change",'.appointment_date',function(e)
	{
		$('#select_date').hide();
		$('#time_slots').show();
		var app_date = $('#appointment_date').val();
		
		console.log(app_date);
		console.log(userid);
		
		$.ajax 
		({
			url: '../../../patient_login/get_user_appointment/'+btoa(userid)+'/'+btoa(app_date),
			type: 'POST',
			success: function (data) 
			{
				if(data != 'no_date'){
					$('#appointment_table').html(data);
				}else{
					$('#time_slots').hide();
					$('#select_date').show();
				}
			},
			error: function (XMLHttpRequest, textStatus, errorThrown)
			{
				console.log('error', errorThrown);
			}
		});//AJAX call end

		//$('.datepicker').datepicker({ minDate: 0});
		//$('input[name="mydate"]').datepicker({ autoClose: true, minDate: 0});
		
	});
	
	
	$(document).on("click",'.book_appointment',function(e)
	{
		var sche_flag = $('#re-schedule').val();

		var app_date = $('#appointment_date').val();
		var app_time = $(this).attr("time");
		$('#appo_date').val(app_date);
		$('#appo_time').val(app_time);
		///console.log('flagggggggggg', sche_flag);
		if(sche_flag.length == 0){
			$('#myModal').modal("show");
		}else{
			$.ajax 
			({
				url: '../../../patient_login/re_sche_appointment/'+btoa(app_date)+'/'+btoa(app_time)+'/'+sche_flag+'/'+btoa(userid),
				type: 'POST',
				success: function (data) 
				{
					console.log(data);
					if(data){
						//console.log('in trueeeeeeeeeeeeeeeeeeeeeeeeee');
						//console.log(data);
						$('#appointment_table').html(data);
						}
					
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
					console.log('error', errorThrown);
				}
			});//AJAX call end
			$('#re-schedule').val("");
			$('#re_sche_note').html("");
			$('#re_sche').html("Re-schedule appointment");
		}
		
		
	});
	$(document).on("click",'#form_submit',function(e)
	{
		$('#appointment_form').submit();
	});		

	$(document).on("click",'.re_appointment',function(e)
	{
		console.log('.re_appointment');
		var re_sche_id = $(this).attr("appointment_id");
		$('#re-schedule').val(re_sche_id);
		$('#re_sche_note').html("Please select a slot to re-schedule the appointment.");
		$('#re_sche').html("Up for re-schedule");
	});

	$(document).on("click",'.delete_appointment',function(e)
	{
		var app_id = $(this).attr("appointment_id");
		var app_date = $('#appointment_date').val();
		
		//console.log('flagggggggggg', app_id);
		
		$.ajax 
		({
			url: '../../../patient_login/delete_appointment/'+app_id+'/'+btoa(userid)+'/'+btoa(app_date),
			type: 'POST',
			success: function (data) 
			{
				console.log(data);
				if(data){
					//console.log('in trueeeeeeeeeeeeeeeeeeeeeeeeee');
					//console.log(data);
					$('#appointment_table').html(data);
					}
				
			},
			error: function (XMLHttpRequest, textStatus, errorThrown)
			{
				console.log('error', errorThrown);
			}
		});//AJAX call end
		$('#re-schedule').val("");
		$('#re_sche_note').html("");
	});	
		
});

</script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>

