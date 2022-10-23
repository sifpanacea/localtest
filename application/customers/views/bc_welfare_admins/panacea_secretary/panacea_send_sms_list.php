<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Create Group";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["chat"]['sub']['create_group']["active"] = true;
include("inc/nav.php");

?>
<link rel="stylesheet" href="<?php echo(CSS.'bootstrap-multiselect.css'); ?>" type="text/css"/>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["PANACEA Masters"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	
	
<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>SMS List </h2>
		
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
				
										<div class="tabs-left">
											<ul class="nav nav-tabs tabs-left" id="demo-pill-nav">
												<li class="active">
													<a href="#tab-r1" data-toggle="tab"><span class="badge bg-color-blue txt-color-white">1</span> Health Request  </a>
												</li>
												<li>
													<a href="#tab-r2" data-toggle="tab"><span class="badge bg-color-greenLight txt-color-white">2</span> No Responce Requests </a>
												</li>
												<li>
													<a href="#tab-r3" data-toggle="tab"><span class="badge bg-color-blueDark txt-color-white">3</span> Doctor </a>
												</li>
												<li>
													<a href="#tab-r4" data-toggle="tab"><span class="badge bg-color-blueDark txt-color-white">4</span> BMI </a>
												</li>
												<li>
													<a href="#tab-r5" data-toggle="tab"><span class="badge bg-color-greenLight txt-color-white">5</span> HB </a>
												</li>
												<li>
													<a href="#tab-r6" id="click_tab_attendance" data-toggle="tab"><span class="badge bg-color-greenLight txt-color-white">6</span> Attendance Report  </a>
												</li>
												<li>
													<a href="#tab-r7" id="click_tab_sanitation" data-toggle="tab"><span class="badge bg-color-greenLight txt-color-white">7</span> Sanitation Report </a>
												</li>
											</ul>
											<div class="tab-content">
												<div class="tab-pane active col-lg-8" id="tab-r1">
													<p>
														<b><h4>New Health Request <span class="badge bg-color-red">1,14,832</span></h4></b>
														Hi $hs_name, New $request_type Request is created with $total_diseaes Identifier is created for $student_name with UID $unique_id on Today Date, Doctor will update You soon.
													</p>
													<p>
														<b><h4>Update Health Request <span class="badge bg-color-red">68,343</span></h4></b>
														Hi $hs_name, Update $request_type Request is created with $total_diseaes Identifier is created for $student_name with UID $unique_id on Today Date, Doctor will update You soon.
													</p>

												</div>
												<div class="tab-pane col-lg-8" id="tab-r2">
													<p>
														<b><h4>Normal Request</h4></b>
														Hi hs_name, A Health Request is created for $Student name with UID $unique_id on $date_created, Status need to set to CURED or change the same as per current status of the child.
													</p>
													<p>
														<b><h4>Emergency Request</h4></b>
														Hi hs_name, Emergency Request is created on $date_created with child name $Student Name and U ID $unique_id, update Status in the health request";
													</p>
													<p>
														<b><h4>Chronic Request</h4></b>
														 Hi hs_name, Chronic Request Raised on $date_created, with child name $Student Name and U ID $unique_id, Update the status and progress of the child in the Health request;
													</p>
												</div>
												<div class="tab-pane col-lg-8" id="tab-r3">
													<p>
														<b><h4>New Health Request</h4></b>
														New  Name  $student_name with U ID  $unique_id Request Type  $request_type IssuesIdentifiers;
													</p>
													<p>
														<b><h4>Update Health Request</h4></b>
														New  Update  $student_name with U ID  $unique_id Request Type  $request_type IssuesIdentifiers;
													</p>
												</div>
												<div class="tab-pane col-lg-10" id="tab-r4">
													
													<p>
														<b><h4>BMI Below 10</h4></b>
														Hi $hs_name, $Student Name with UID  $Student Unique ID is having BMI value  $BMI submitted on $month As this could lead to <b>Critical Medical issue, request to consult PANACEA immidiately for doctors advice.</b>
													</p>
													<p>
														<b><h4>BMI Below 12</h4></b>
														Hi hs_name, Student Name with UID  Student Unique ID is having HB value $HB submitted on $month As this is <b> A Moderate Anemic issue, request to consult PANACEA immidiately for doctors advice.</b>
													</p>
												
													<p>
														<b><h4>BMI Below 14</h4></b>
														 Hi hs_name, Student Name with UID  Student Unique ID is having HB value $hb submitted on $month As this is <b> A Mild Anemic issue, special attention is needed. Hence act before child becomes unhealthy.</b>
													</p>
													<p>
														<b><h4>BMI Above 28</h4></b>
														Hi $hs_name, $Student Name with UID $Student Unique IDis having BMI value $BMI submitted on $month Request to consult PANACEA immidiately for doctors advice.
													</p>
													<p><b><h4>BMI SMS For Official</h4></b>
														Respected Sir/Madam, Student Name with BMI values observed on month. Its been communicated to $hs_name $msg_count times. We would like to bring to your attention on this issue.
													</p>
												</div>
												<div class="tab-pane col-lg-10" id="tab-r5">
													<p><b><h4>HB Below 6</h4></b>
														Hi $hs_name , Student Name with UID Student Unique ID is having HB value HB submitted on month. As this could lead to <b> Critical Medical issue, request to consult PANACEA immidiately for doctors advice.</b>
													</p>
													<p><b><h4>HB Below 8</h4></b>
														Hi $hs_name , Student Name with UID Student Unique ID is having HB value HB submitted on month. As this is <b> A Severe anemic issue, request to consult PANACEA immidiately for doctors advice. </b>
													</p>
													<p><b><h4>HB Below 10</h4></b>
														Hi $hs_name , Student Name with UID Student Unique ID is having HB value HB submitted on month. As this is <b> A Moderate anemic issue, request to consult PANACEA immidiately for doctors advice.</b>
													</p>
													<p><b><h4>HB Below 12</h4></b>
														Hi $hs_name, Student Name with UID Student Unique ID is having HB value HB submitted on $month. As this is a mild anemic issue, special attention is needed. Hence act before child becomes unhealthy.
													</p>
													<p><b><h4>HB SMS For Official</h4></b>
														Respected Sir/Madam, Student Name with HB values observed on month. Its been communicated to $hs_name $msg_count times. We would like to bring to your attention on this issue.
													</p>
												</div>
												<div class="tab-pane col-lg-10" id="tab-r6">
													<div class="input-group">
														<input type="text" id="set_date" name="set_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo date('Y-m-d')?>">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<div class="input-group-btn">
														<button type="button" class="btn btn-success button_field" id="set_date_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
														  Set date
															</button>
														</div>
													</div>
													<div class="attendance_view"></div>	
													
												</div>
												<div class="tab-pane col-lg-10" id="tab-r7">
													<div class="input-group">
														<input type="text" id="set_date_sanitation" name="set_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo date('Y-m-d')?>">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<div class="input-group-btn">
														<button type="button" class="btn btn-success button_field" id="set_date_btn_sanitation" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
														  Set date
															</button>
														</div>
													</div>	
													<div class="sanitation_view"></div>											
												</div>
											</div>
										</div>
				
									</div>

	<!-- end widget content -->

</div>
<!-- end widget div -->
		
					</div>
					<!-- end widget -->
					</article>
        
        </div><!-- ROW -->
        
        
        
        
       

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
<script src="<?php echo(JS.'bootstrap-multiselect.js'); ?>" type="text/javascript"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {

	$('.datepicker').datepicker({
			minDate: new Date(1900, 10 - 1, 25)
		});
	

	$('#click_tab_attendance').click(function(e){
		
		$.ajax({
			url : 'get_attendance_msg_info',
			type : "POST",
			data : {'type_of_msg' : "Attendance_Messages_info"},
			success:function(data){
				if(data == "NO_DATA_AVAILABLE")
				{
					$('.attendance_view').html('<h2> No Data Available </h2>');
				}else
				{
					data_doc = JSON.parse(data);					
					show_msg = data_doc.sms_list.Attendance_Messages_info;
					$('.attendance_view').html('<div id="msg_view"</div>');

					var view = '<p><h2> Date : '+show_msg.Sent_date+'</h2></p>';
					view = view + '<p><b><h4> Attendance SMS Sent Count <span class="badge bg-color-red">' +show_msg.Sent_count+ '</span></h4></b></p>';
					view = view + '<p><h3> HS MSG : ' +show_msg.message_hs+ '</h3></p>';
					view = view + '<p><h3> Principal\'s MSG : ' +show_msg.message_principal+ '</h3></p>';
					view = view + '<h3> Not Submitted Schools List </h3>';
					$.each(show_msg.school_name, function(index, value){
						view = view + '<h4>'+value+'</h4>';
					})
					$('#msg_view').html(view);					
				}

			},error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
	})

	$('#click_tab_sanitation').click(function(e){
		
		$.ajax({
			url : 'get_attendance_msg_info',
			type : "POST",
			data : {'type_of_msg' : "Sanitation_Messages_info"},
			success:function(data){
				if(data == "NO_DATA_AVAILABLE")
				{
					$('.sanitation_view').html('<h2> No Data Available </h2>');
				}else
				{
					data_doc = JSON.parse(data);					
					show_msg = data_doc.sms_list.Sanitation_Messages_info;
					$('.sanitation_view').html('<div id="sanitation_msg_view"</div>');

					var view = '<p><h2> Date : '+show_msg.Sent_date+'</h2></p>';
					view = view + '<p><b><h4> Sanitation SMS Sent Count <span class="badge bg-color-red">' +show_msg.Sent_count+ '</span></h4></b></p>';
					view = view + '<p><h3> ACT MSG : ' +show_msg.message_act+ '</h3></p>';
					view = view + '<p><h3> Principal\'s MSG : ' +show_msg.message_principal+ '</h3></p>';
					view = view + '<h3> Not Submitted Schools List </h3>';
					$.each(show_msg.school_name, function(index, value){
						view = view + '<h4>'+value+'</h4>';
					})
					$('#sanitation_msg_view').html(view);
				}

			},error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
	})

	$('#set_date_btn').click(function(e){
		today_date = $("#set_date").val();
		$.ajax({
			url : 'get_attendance_msg_info',
			type : "POST",
			data : {'today_date' : today_date, 'type_of_msg' : "Attendance_Messages_info"},
			success:function(data)
			{
				if(data == "NO_DATA_AVAILABLE")
				{
					$('.attendance_view').html('<h2> No Data Available </h2>');
				}else
				{
					data_doc = JSON.parse(data);
					show_msg = data_doc.sms_list.Attendance_Messages_info;
					$('.attendance_view').html('<div id="msg_view"</div>');

					var view = '<p><h2> Date : '+show_msg.Sent_date+'</h2></p>';
					view = view + '<p><b><h4> Attendance SMS Sent Count <span class="badge bg-color-red">' +show_msg.Sent_count+ '</span></h4></b></p>';
					view = view + '<p><h3> HS MSG : ' +show_msg.message_hs+ '</h3></p>';
					view = view + '<p><h3> Principal\'s MSG : ' +show_msg.message_principal+ '</h3></p>';
					view = view + '<h3> Not Submitted Schools List </h3>';
					$.each(show_msg.school_name, function(index, value){
						view = view + '<h4>'+value+'</h4>';
					})
					$('#msg_view').html(view);
				}

			},error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
	});
	$('#set_date_btn_sanitation').click(function(e){
		today_date = $("#set_date_sanitation").val();
		$.ajax({
			url : 'get_attendance_msg_info',
			type : "POST",
			data : {'today_date' : today_date, 'type_of_msg' : "Sanitation_Messages_info"},
			success:function(data)
			{
				if(data == "NO_DATA_AVAILABLE")
				{
					$('.sanitation_view').html('<h2> No Data Available </h2>');
				}else
				{
					data_doc = JSON.parse(data);
					console.log(data_doc, "data_doc===================348");					
					show_msg = data_doc.sms_list.Sanitation_Messages_info;
					$('.sanitation_view').html('<div id="sanitation_msg_view"</div>');

					var view = '<p><h2> Date : '+show_msg.Sent_date+'</h2></p>';
					view = view + '<p><b><h4> Sanitation SMS Sent Count <span class="badge bg-color-red">' +show_msg.Sent_count+ '</span></h4></b></p>';
					view = view + '<p><h3> ACT MSG : ' +show_msg.message_act+ '</h3></p>';
					view = view + '<p><h3> Principal\'s MSG : ' +show_msg.message_principal+ '</h3></p>';
					view = view + '<h3> Not Submitted Schools List </h3>';
					$.each(show_msg.school_name, function(index, value){
						view = view + '<h4>'+value+'</h4>';
					})
					$('#sanitation_msg_view').html(view);
				}

			},error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
	});
	 
});

</script>
