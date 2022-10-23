<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Students Report";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["basic_dashboard"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo(CSS.'AdminLTE.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="<?php echo JS; ?>/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="<?php echo JS; ?>/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<style>
#set_date
{
	float:right;
	 width: 200px; 
}
.summary{

			border-radius: 15px;
			background-color:  yellowgreen;
    		color: white;
    		padding: 15px;

}
.summary:hover{

			border-radius: 19px;
			background-color:  #8107C7;
    		color: white;

}
ul li{
	border-radius:20px;
}
li{
	margin: 5px;
}

#get_search {
  border: 1px solid #FF00;
  padding: 10px;
  border-radius: 5px;
}
#get_val {
  border: 2px solid #FF0000;
  padding: 7px;
}

</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Reports"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="row">		
			
					<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loading_modal">
					        <div class="modal-dialog modal-sm" role="document">
						        <div class="modal-content" id="loading">
						          	<center><img src="<?php echo(IMG.'Pulse-1s.gif'); ?>" id="gif" ></center>
						        </div>
					        </div>
					</div>
			<div class="col-sm-12">
			<h4 class="text-center summary">Male's :<?php echo $screening_report_count['male'];?> &nbsp;Female's : <?php echo $screening_report_count['female'];?> &nbsp; Total Students Count : <?php echo $screening_report_count['total_students'];?> &nbsp;
				<span id='screened_count_tail'></span>
				<input type="radio" name="student_type" class="student_type_for_tails" value="All" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false" style="padding: 6px;" checked> All
				<input type="radio" name="student_type" class="student_type_for_tails" id="student_type_boys" value="Male" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false" style="padding: 6px;"> Male
				<input type="radio" name="student_type" class="student_type_for_tails" id="student_type_girls"value="Female" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false" style="padding: 6px;"> Female
			</h4>	
			</div>
	<!-- <div class="panel panel-primary" >
		     <div class="panel-heading clearfix">			
				<div class="input-group col-lg-3 pull-right"> -->
					<!-- <div class="col-sm-4">
							<h4 class="text-center summary">
								<input   id="get_search" placeholder="Type Name & ID">
								<button type="submit" id="get_val" class="bg-color-red">Search...</button><br>
								<button type="submit" id="close_val" class="bg-color-pink">Clear</button>
							</h4>	
					</div> -->

			<br>
			<br>
			<div class="col-sm-12" id="request_btn">
				<div id="searched_stud_list"></div>
			</div>
	<article class="col-sm-12 col-md-12 col-lg-12">		
		<div class="col-lg-12" id="cards_div">
						<div class="row">
							<div class="col-lg-4 col-xs-6">
								<div class="panel panel-primary suman_panel">					 
								  <div class="panel-body btn-primary">
								  	<div id='sevier_count'>					  		
										<a class="hbCases" style="color: #fafafa"><h4>Very Severe Anemia Cases Count:<strong id="below_6_hb"></strong></h4></a><p></p>
						                <a class="hbCases" style="color: #fafafa"><h4>Severe Anemia Cases Count:<strong id="severe_hb"></strong></h4></a><p></p>
						                <a class="hbCases" style="color: #fafafa"><h4>Mild Anemia Cases Count:<strong id="mild_hb"></strong></h4></a><p></p>
						                <a class="hbCases" style="color: #fafafa"><h4>Moderate Anemia Cases Count:<strong id="moderate_hb"></strong></h4></a>
					                </div>
								  </div>
								</div>
					        </div>
					        <div class="col-lg-4 col-xs-6">
						          <!-- small box -->
						        <div class="panel panel-success suman_panel">						 
									<div class="panel-body btn-success">
									  	<div id="bmi_count">
						                	<!--<a class="bmiCases" style="color: #fafafa"><h4>Critical Under Weight Cases Count :<strong id="below_14_bmi"></strong></h4></a><p></p>-->
						                	<a class="bmiCases" style="color: #fafafa"><h4>Under Weight Count :<strong id="under_weight_bmi"></strong></h4></a><p></p>
						                	<a class="bmiCases" style="color: #fafafa"><h4>Normal Weight Count :<strong id="normal_weight_bmi"></strong></h4></a> <p></p>     	
						                	<a class="bmiCases" style="color: #fafafa"><h4>Over Weight Count :<strong id="over_weight_bmi"></strong></h4></a><p></p>
						                </div>
									</div>
								</div>
			       			</div>
			       			<div class="col-lg-4 col-xs-6">
					          <!-- small box -->
					          <div class="panel panel-warning suman_panel">
								 
								  <div class="panel-body btn-warning">
								  	<div id='screened_count'>
								  		<a class="emergencyCases" style="color: #fafafa"><h4 style="font-size:initial">Normal Requests:<strong id="normalReqCount"></strong></h4></a><p></p>
								  		<a class="emergencyCases" style="color: #fafafa"><h4 style="font-size:initial">Emergency Requests:<strong id="emergencyReqCount"></strong></h4></a><p></p>
								  		<a class="emergencyCases" style="color: #fafafa"><h4 style="font-size:initial">Chronic Requests:<strong id="chronicReqCount"></strong></h4></a><p></p>
								  		<a class="fieldOfficerCases" style="color: #fafafa"><h4 style="font-size:initial">Doctor Visits:<strong id="doctorVisitReqCount"></strong></h4></a>
								  	</div>					  
								  </div>
							</div>
			       			 </div>
			       			 
			       			 </div>

			              			<div class="row">
			              				<div class="col-lg-4 col-xs-6 suman_panel">
			       		          <!-- small box -->
			       		          	<div class="panel panel-danger">
			       					 
			       					  <div class="panel-body btn-danger">
			       					  	<div id='chronic_asthma_count'>
			       				             <a class="chronicCases" style="color: #fafafa"><h4>Anemia Requests:<strong id="chronic_anemia"></strong></h4></a><p></p>
			       		                	<a class="chronicCases" style="color: #fafafa"><h4>TB Requests:<strong id="chronic_tb"></strong></h4></a><p></p>
			       		                	<a class="chronicCases" style="color: #fafafa"><h4>Asthma Requests :<strong id="chronic_asthma"></strong></h4></a><p></p>
			       		                	<a class="chronicCases" style="color: #fafafa"><h4>HIV Requests :<strong id="chronic_hiv"></strong></h4></a><p></p>
			       		             	</div>
			       					  </div>
			       					</div>
			       		        </div>
			       		         <div class="col-lg-4 col-xs-6">
			       		          <!-- small box -->
			       		          	<div class="panel panel-info suman_panel">
			       					  <div class="panel-body btn-info">
			       					  	<div id='chronic2_asthma_count'>
			       				             <a class="chronicCases" style="color: #fafafa"><h4>Scabies Requests:<strong id="chronic_scabies"></strong></h4></a><p></p>
			       		                	<a class="chronicCases" style="color: #fafafa"><h4>Epilepsy Requests:<strong id="chronic_epilepsy"></strong></h4></a><p></p>
			       		                	<a class="chronicCases" style="color: #fafafa"><h4>Hypothyroidism Requests :<strong id="chronic_hypothyroidism"></strong></h4></a><p></p>
			       		                	<a class="chronicCases" style="color: #fafafa"><h4>Diabetes Requests :<strong id="chronic_diabetes"></strong></h4></a><p></p>
			       		             	</div>
			       					  </div>
			       					</div>
			       		        </div>

			       		        <div class="col-lg-4 col-xs-6">
			       		          <!-- small box -->
			       		          	<div class="panel suman_panel" style="background: #B51338">
			       					  <div class="panel-body">
			       					  	<div id='emergency_Req'>
			       				             
			       				             <a class="fieldOfficerCases" style="color: #fafafa"><h4 style="font-size:initial">Out Patient:<strong id="outPatientReqCount"></strong></h4></a><p></p>
			       				             <a class="fieldOfficerCases" style="color: #fafafa"><h4 style="font-size:initial">Admitted Cases:<strong id="admittedReqCount"></strong></h4></a><p></p>
			       				             <a class="fieldOfficerCases" style="color: #fafafa"><h4 style="font-size:initial">Review Cases:<strong id="reviewCasesReqCount"></strong></h4></a><p></p>
			       				             <a class="fieldOfficerCases" style="color: #fafafa"><h4 style="font-size:initial">Surgery Cases:<strong id="surgeryCasesReqCount"></strong></h4></a>				              
			       		             	</div>
			       					  </div>
			       					</div>
			       		        </div>

			       		        <div class="col-lg-3 col-xs-6" style="display: none;">
			       		          <!-- small box -->
			       		          	<div class="panel panel-info suman_panel">
			       					  <div class="panel-body btn-info">
			       				             <a class="" style="color: #fafafa"><h4>Total Requests count : <strong> <?php echo $total_request_count;?></strong></h4></a>
			       		                	<a class="" style="color: #fafafa"><h5>Normal : <?php echo $normal_req_count .", Not Cured : ".  $normal_req_count_not_cured;?> </h5></a>
			       		                	<a class="" style="color: #fafafa"><h5>Emergency : <?php echo $emergency_req_count .", Not Cured : ". $emergency_req_count_not_cured;?> </h5></a>
			       		                	<a class="" style="color: #fafafa"><h5>Chronic : <?php echo $chronic_req_count .", Not Cured : ".  $chronic_req_count_not_cured;?> </h5></a> 
			       					  </div>
			       					</div>
			       		        </div>
			       		</div>
			
				</div>
			
		   	</div>
		</article>
	</div>
		<article class="col-sm-12 col-md-10 col-lg-10">
			 <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
			 </div>
			</article>
			<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget" id="wid-id-5" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								
								<header>
									
								</header>
		<div class="row hide">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-orange" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
		<header>
			<span class="widget-icon"> <i class="fa fa-user"></i> </span>
			<h2>Select a district </h2>

		</header>

		<!-- widget div-->
		<div>
			<!-- widget content -->
			
			<div class="widget-body no-padding">
			<form class=smart-form>
				<!--<form class="smart-form">-->
					
						<fieldset>
					<div class="row">
					<section class="col col-4">
						<label class="label" for="first_name">District Name</label>
						<label class="select">
						<select id="select_dt_name" >
							<option value='All' >All</option>
							<?php if(isset($distslist)): ?>
								
								<?php foreach ($distslist as $dist):?>
								<option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
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
								<option value="All" >All</option>
							
							
						</select> <i></i>
					</label>
					</section> <input type="hidden" name="school_code" id="school_code"><br>
					
					<label class="label"></label>
						<section class="col col-2">
							<button type="button" class="btn bg-color-pink txt-color-white btn-sm" id="set_button" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
						   Set
							</button>
						</section>
					</div>
					</fieldset>	
					</form>
					<form style="display: hidden" action="drill_down_screening_to_students_load_ehr_count" method="POST" id="ehr_form">
					  <input type="hidden" id="ehr_data" name="ehr_data" value=""/>
					  <input type="hidden" id="ehr_navigation" name="ehr_navigation" value=""/>
					</form>
				

			</div>
			<!-- end widget content -->

		</div>
		<!-- end widget div -->

		</div>
		</article>

		</div><!-- ROW -->
			
			<!-- <div class="row" id="request_btn">
				<div id="searched_stud_list"></div>
			</div> -->
								
					<article class="col-sm-12 col-md-12 col-lg-8">
			<div class="well-sm well-light">
					<div class="row">
						<div class="panel panel-primary" >
							<div class="panel-heading clearfix">
								<h4 class="panel-title pull-left" style="padding-top: 7.5px;">Daily Health Issues</h4>
									<div class="input-group">
										<input type="text" id="set_date" name="set_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo date('Y-m-d')?>">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<div class="input-group-btn">
										<button type="button" class="btn btn-success button_field" id="set_date_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
										  Set date
											</button>
										</div>
									</div>
							</div>
							<div class="input-group col-sm-12">
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-6">
							<!-- widget content -->
						<div class="widget-body">
					      <div class="panel-body">
					      		<div id="request_response_table_view"></div>
					      </div>
					    </div>
						</div>				
				
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-5">
							<!-- widget content -->
							<div class="widget-body">
					      		<div class="panel-body">
					      			<div id="request_doctors_table_view"></div>
					      		</div>
							</div>
						</div>	
					</div>
	
					</div>
				</div>
			</div>
			</article>
		<article class="col-sm-12 col-md-12 col-lg-4">	
			<div class="well-sm well-light">
				
						<div class="panel panel-primary" >
							<div class="panel-heading clearfix">
								<h4 class="panel-title pull-left" style="padding-top: 7.5px;">Admitted Cases Issues</h4></div>
			<div class="row" style="padding-left: 15.5px;">
				<div class="col col-md-5">
                          <?php $end_date  = date ( "Y-m-d", strtotime ( $today_date . "-90 days" ) ); ?>
                            <span id="monitoring_datepicker">
                                    Start Date:
                       <input type="text" id="passing_date" name="passing_date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="" placeholder="Select Start Date">
                             </span>
                </div>
                <div class="col col-md-4">
                    <span id="monitoring_datepicker">
                                   End Date:
             <input type="text" id="passing_end_date" name="passing_end_date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="" placeholder="End Date">
                     </span>
                </div>
                <div class="col col-md-2" style="padding-top: 20px;">
	                <div class="input-group-btn">
	                    <!-- <button type="button" class="btn btn-success button_field" id="date_set_admitted" data-keyboard="false">
	                      Set date
	                    </button> -->
	                    <button type="button" class="btn btn-success button_field" id="date_set_admitted">
	                         <span class="glyphicon glyphicon-search"></span>
	                    </button>
	                </div>
                </div>
				<div class="input-group col-sm-11">	      
					<div class="panel-body">
						<div id="request_hospitalized_table_view"></div>
					</div>
				</div>
            </div>
					 <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loading_modal">
					  <div class="modal-dialog modal-sm" role="document">
					    <div class="modal-content" id="loading">
					    <center><img src="<?php echo(IMG.'loader.gif'); ?>" id="gif" ></center>
					    </div>
					  </div>
					</div>
								</div>
						
						</div>
		</article>

<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-purple" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Screening Report</h2>
	<div id="showPIEBtn"></div>
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
		<div class="row">
			<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
			<div class="well well-sm well-light">
			<div id="select_year_wise">
					<form class="smart-form">
					<label class="label">Search Span</label>
					<label class="select col-lg-6">
						<select id="screening_pie_spanss" class="get_last_screened">
						<!-- 	<option value="Monthly">Monthly</option>
							<option value="Bi Monthly">Bi Monthly</option>
							<option value="Quarterly">Quarterly</option>
							<option value="Half Yearly">Half Yearly</option>
							<option value="2015-16 Academic Year">2015-16 Academic Year</option>
							<option value="2016-17 Academic Year">2016-17 Academic Year</option>
							<option value="2017-18 Academic Year">2017-18 Academic Year</option> -->
							<option selected value="2018-19 Academic Year">2018-19 Academic Year</option>
						</select> <i></i> </label>
				</form>
			<label class="label" style="color:#fff;">.</label>
				<button type="button" class="btn bg-color-greenDark txt-color-white btn-sm" id="refresh_screening_data" disabled>
				Refresh
				</button>
				<img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="screening_update_loading" style="display:none;width:30px;height:30px">
												
			</div>
			<div class="">
				<div id="screen_pie_new_one"></div>
				<!-- <div id="screening_report">
				
				</div> -->
			

				<div id="abnormalties_report_table">
					
				</div>
			</div>
			</div>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
			<div class="well well-sm well-light">
				<div class="input-group">
					<input type="text" id="set_date_attendance" name="set_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo date('Y-m-d')?>">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<div class="input-group-btn">
					<button type="button" class="btn btn-success button_field" id="set_date_btn_for_attendance" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
					  Set date
						</button>
					</div>
				</div>
				<div class="well well-sm well-light">
				<br>
				<div id="pie_absent" class="pie_absent"></div><br>
				<form action="drill_down_absent_to_students_load_ehr" method="GET" id="ehr_form_for_absent">
					<input type="hidden" id="ehr_data_for_absent" name="ehr_data_for_absent" value=""/>
					<input type="hidden" id="ehr_navigation_for_absent" name="ehr_navigation_for_absent" value=""/>
				</form>
				
				<label class="form-control"> <a href="javascript:void(0)" class="abs_submitted_schools_list">Submitted Schools &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</a> <span class="abs_submitted_schools"></span></label>
				<label class="form-control"> <a href="javascript:void(0)" class="abs_not_submitted_schools_list"> Not Submitted Schools : </a><span class="abs_not_submitted_schools"></span></label>
				</div>
			</div>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-4 col-md-4">
				<div class="well well-sm well-light">
				
				<div class="col-md-12" id="loading_req_pie" style="display:none;">
					<center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
				</div>
				
				<div id="req_id_pies">
				
					<div class="well well-sm well-light">
						
						<input type="radio" name="gender" class="student_type" value="" checked> All
						<input type="radio" name="gender" class="student_type" id="student_type_boys" value="Male"> Male
						<input type="radio" name="gender" class="student_type" id="student_type_girls"value="Female"> Female

							<section class="pull-right control-group">
								<label class="label" for="age"> Select Age </label>
								<label class="select">
									<select id="age">
										<option value="select age"> Select Age </option>
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
						
						<br>
						<div>
							<div id="pie_request"></div>
							<span class="badge bg-color-blue  pull-left inbox-badge" id="total_active_req"></span>
							<span class="badge bg-color-greenLight pull-right inbox-badge" id="total_rised_req"></span>
							<br/>
							<form action="drill_down_request_to_students_load_ehr" method="POST" id="ehr_form_for_request">
								<input type="hidden" id="ehr_data_for_request" name="ehr_data_for_request" value=""/>
								<input type="hidden" id="ehr_navigation_for_request" name="ehr_navigation_for_request" value=""/>
							</form>
							<form class="smart-form" >
						<div class="well well-sm well-light">
						<fieldset style="padding-top: 0px; padding-bottom: 0px;">
						<section class="col col-6">
							<label class="label">Search Span</label>
							<label class="select">
								<select id="request_pie_span">
									<option selected value="Daily">Daily</option>
									<option value="Weekly">Weekly</option>
									<option value="Bi Weekly">Bi Weekly</option>
									<option value="Monthly">Monthly</option>
									<option value="Bi Monthly">Bi Monthly</option>
									<option value="Quarterly">Quarterly</option>
									<option value="Half Yearly">Half Yearly</option>
									<option value="Yearly">Yearly</option>
								</select> <i></i> </label>
						</section>
						
						<section class="col col-6">
							<label class="label">Select Status Type</label>
							<label class="select">
								<select id="request_pie_status">
									<option selected value="All">All (except Cured)</option>
									<option value="Cured">Cured</option>
								</select> <i></i> </label>
						</section>
						</fieldset>
						</div>
						</form>
						</div>
					</div>								
								
				
				</div>
			</div>
				
		</div>
		</div>
				
	</div>
	<!-- end widget content -->

</div>
<!-- end widget div -->

</div>
</article>
<article class="col-sm-12 col-md-12 col-lg-6">
	<div>
			<div class="well well-sm well-light">
			<div class="panel panel-primary" >
    			<div class="panel-heading clearfix">
    				<h4 class="panel-title pull-left" style="padding-top: 7.5px;">Screenig Pie</h4>
    					<div class="input-group col-sm-4 pull-right">
    						<div id="select_year_wise">
    								<form class="smart-form">
    								<label class="label" style="color: white">Search Span</label>
    								<label class="select col-sm-6">
    									<select id="screening_pie_span" class="get_last_screened">
    										<option value="Monthly">Monthly</option>
    										<option value="Bi Monthly">Bi Monthly</option>
    										<option value="Quarterly">Quarterly</option>
    										<option value="Half Yearly">Half Yearly</option>
    										<option value="2015-16 Academic Year">2015-16 Academic Year</option>
    										<option value="2016-17 Academic Year">2016-17 Academic Year</option>
    										<option value="2017-18 Academic Year">2017-18 Academic Year</option>
    										<option selected value="2018-19 Academic Year">2018-19 Academic Year</option>
    									</select> <i></i> </label>
    							</form>
    						<label class="label" style="color:#fff;">.</label>
    							<!-- <button type="button" class="btn bg-color-greenDark txt-color-white btn-sm" id="refresh_screening_data" disabled>
    							Refresh
    							</button> -->
    							<img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="screening_update_loading" style="display:none;width:30px;height:30px">
    															
    						</div>
    					</div>
    			</div>				
    			</div>
			
			<div class="">
			
				<center><div id="screening_report"></div> </center>
				

				<div id="abnormalties_report_table">
					
				</div>
			</div>
			</div>
		</div>
				
</article>

<article class="col-sm-12 col-md-12 col-lg-6">
    <div class="well well-sm well-light">

    	        <div class="panel panel-primary" >
    			<div class="panel-heading clearfix">
    				<h4 class="panel-title pull-left" style="padding-top: 7.5px;">Sanitation Report</h4>
    					<div class="input-group col-lg-4 pull-right">
    						<input type="text" id="set_date_sanitation" name="set_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo date('Y-m-d')?>">
    						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
    						<div class="input-group-btn">
    						<button type="button" class="btn btn-success button_field" id="set_date_btn_for_sanitation" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
    						  Set date
    							</button>
    						</div>
    					</div>
    			</div>				
    			</div>				


    	<!-- widget div-->
    	<div>

    		<!-- widget edit box -->
    		<div class="jarviswidget-editbox">
    			<!-- This area used as dropdown edit box -->

    		</div>
    		<!-- end widget edit box -->

    		<!-- widget content -->
    		
    		<div class="widget-body">
    			<div class="row">
    			<div class="col col-md-5">
    					
    					<div class="well well-sm well-light">
    					<label>Status of <label class="sanitation_report_status_date" for="date"></label></label>
    					<label class="form-control"> <a href="javascript:void(0)" class="sanitation_report_submitted_schools_list">Submitted Schools &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</a> <span class="sanitation_report_submitted_schools"></span></label>
    					<label class="form-control"> <a href="javascript:void(0)" class="sanitation_report_not_submitted_schools_list"> Not Submitted Schools : </a><span class="sanitation_report_not_submitted_schools"></span></label>
    					</div>
    					<br>
    				<div id="sanitation_report_table"></div>
    				<div id="sanitation_report_counts"></div>
    				
    				<br>
    				<div class="form-group checkbox_sanitation hide" >
    										
    											<div class="col-md-10">
    												<label class="checkbox-inline col col-md-3">
    														<input type="checkbox" class="checkbox style-0" id="daily">
    														<span>Daily</span>
    												</label>
    												<label class="checkbox-inline col col-md-3">
    														<input type="checkbox" class="checkbox style-0" id="weekly">
    														<span>Weekly</span>
    												</label>
    												<label class="checkbox-inline col col-md-3">
    														<input type="checkbox" class="checkbox style-0" id="monthly">
    														<span>Monthly</span>
    												</label>
    											</div>
    					</div>
    				</div>

    				<div class="col col-md-7">
    					<br>
    					<br>
    					<br>
    					<br>
    					

    				
    						<div class="" id="chart_details" style="height:300px">
    			
    		 				
    					</div>
    						
    				</div>
    			
    			
    					
    		</div>
    		<div class="row">
    			<div class="col col-md-5">
    					<div id="show_yes_no"></div>
    						<div id="show_yes_no_count"></div>
    			</div>
    			<div class="col col-md-7">
    				<div id="myDiv"><!-- Plotly chart will be drawn inside this DIV --></div>
    			</div>
    		</div>
    		

    		<!-- ATTENDANCE SUBMITTED SCHOOLS LIST -->
    			<div class="modal fade-in" id="absent_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    				<div class="modal-dialog modal-lg">
    					<div class="modal-content">
    						<div class="modal-header">
    							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
    								×
    							</button>
    							
    							<h4 class="modal-title" id="myModalLabel">Absent Report Submitted Schools </h4>
    						</div>
    						<div class="well well-sm well-light">
    						<button type="button" class="btn btn-primary" id="absent_sent_school_download">
    								Download
    							</button>
    							</div>
    						<div id="absent_sent_school_modal_body" class="modal-body">
    			            
    						
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
    			
    			<!-- ATTENDANCE NOT SUBMITTED SCHOOLS LIST -->
    			<div class="modal" id="absent_not_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    				<div class="modal-dialog modal-lg">
    					<div class="modal-content">
    						<div class="modal-header">
    							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
    								×
    							</button>
    							<h4 class="modal-title" id="myModalLabel">Absent Report Not Submitted Schools </h4>
    						</div>
    						<div class="well well-sm well-light">
    						<button type="button" class="btn btn-primary" id="absent_not_sent_school_download">
    								Download
    							</button>
    							</div>
    						
    						
    						<div id="absent_not_sent_school_modal_body" class="modal-body">
    						
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

    				<!-- SANITATION REPORT SUBMITTED SCHOOLS LIST -->
    				<div class="modal fade-in" id="sani_repo_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
    				<div class="modal-dialog modal-lg">
    					<div class="modal-content">
    						<div class="modal-header">
    							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
    								×
    							</button>
    							<h4 class="modal-title" id="myModalLabel">Sanitation Report Submitted Schools </h4>
    						</div>
    						
    						<div class="well well-sm well-light">
    						<button type="button" class="btn btn-primary" id="sani_repo_sent_school_download">
    								Download
    							</button>
    							</div>
    						
    						<div id="sani_repo_sent_school_modal_body" class="modal-body">
    			            
    						
    						</div>
    						<div class="modal-footer">
    						  <!-- <button type="button" class="btn btn-primary" id="sani_repo_sent_school_download">
    								Download
    							</button> -->
    							<button type="button" class="btn btn-default" data-dismiss="modal">
    								Close
    							</button>
    						</div>
    					</div><!-- /.modal-content -->
    				</div><!-- /.modal-dialog -->
    			</div>
    			
    			<!-- SANITATION REPORT NOT SUBMITTED SCHOOLS LIST -->
    			<div class="modal" id="sani_repo_not_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    				<div class="modal-dialog modal-lg">
    					<div class="modal-content">
    						<div class="modal-header">
    							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
    								×
    							</button>
    							<h4 class="modal-title" id="myModalLabel">Sanitation Report Not Submitted Schools  </h4>
    						</div>
    						
    						<div class="well well-sm well-light">
    						<button type="button" class="btn btn-primary" id="sani_repo_not_sent_school_download">
    								Download
    							</button>
    							</div>
    						
    						<div id="sani_repo_not_sent_school_modal_body" class="modal-body">
    						
    						</div>
    						<div class="modal-footer">
    						   <!-- <button type="button" class="btn btn-primary" id="sani_repo_not_sent_school_download">
    								Download
    							</button> -->
    							<button type="button" class="btn btn-default" data-dismiss="modal">
    								Close
    							</button>
    						</div>
    					</div><!-- /.modal-content -->
    				</div><!-- /.modal-dialog -->
    			</div>

    			<!-- Select Age and SCHOOLS LIST for BMI-->
    		<div class="modal fade" id="load_waiting_age_bmi" tabindex="-1" role="dialog" >
    							<div class="modal-dialog">
    								<div class="modal-content">
    									<h4 class="modal-title" id="myModalLabel">Select Age </h4>
    							<div class="modal-body">
    										
    							<section class="control-group">
    								<label class="label" for="age"> Select Age </label>
    									<label class="select">
    										<select id="age_bmi_modal">
    											<option value="select age"> Select Age </option>
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
    									<button type="button" class="btn btn-primary bmi_selected">
    										OK
    									</button>
    									<div class="modal-footer">
    										<button type="button" class="btn btn-default" data-dismiss="modal">
    											Close
    										</button>
    									</div>
    								</div><!-- /.modal-content -->
    							</div><!-- /.modal-dial-->
    							</div>
    							<!-- end widget div -->
    		<!-- Modal -->
    						<div class="modal fade" id="load_waiting" tabindex="-1" role="dialog" >
    							<div class="modal-dialog">
    								<div class="modal-content">
    									<div class="modal-header">
    										<h2 class="modal-title" id="myModalLabel">Generating Counts, Please wait!</h2>
    									</div>
    									<div class="modal-body">
    										<div class="row">
    											<div class="col-md-12">
    												<img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" style="display: block; margin: 0 auto; width: 100px;">
    											</div>
    										</div>
    									</div>
    								</div><!-- /.modal-content -->
    							</div><!-- /.modal-dial-->
    							</div>
    							<!-- end widget div -->
    		
    	</div>

    </div>  
       

</article>

	<article class="hidden">

		<div class="row">
		<div class="col-lg-12">
			<h2>Death Cases</h2>
			<table class="table table-striped">
				<thead>
					<tr>
						<th> Student Name </th>
						<th> Class </th>
						<th> School Name </th>
						<th> Reason for Death </th>
						<th> Date of Death </th>
						<th> Hospital/Home </th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td> M.Deepthi </td>						
						<td> 9 </td>
						<td> YERRUPALEM(GIRLS) </td>				
						<td> Suscidal attempet by Hanging herself in hostel </td>				
						<td> 02-08-2018 </td>				
						<td> KMM area hospital </td>				
					</tr>
					<tr>
						<td> Pavithra </td>						
						<td> 5 </td>
						<td> Mdk ANDOL (Girls) </td>				
						<td> Brain fever </td>				
						<td> 24-07-2018 </td>				
						<td> Dead on the way to her home </td>				
					</tr>
					<tr>
						<td> L.Arun </td>						
						<td> 8 </td>
						<td> Dhamaragidda MBNR (b) </td>				
						<td> Burnt himself </td>				
						<td> 13-09-2018 </td>				
						<td>  Gandhi hospital,09:00AM </td>				
					</tr>
					<tr>

						<td> R.BHAVANI </td>						
						<td> 7 </td>
						<td> kmnr Ramagundam (G) </td>				
						<td> ASD,(congenital heart disease. FEVER </td>				
						<td> 04-11-2018 </td>				
						<td>  Area hospital </td>				
					</tr>
					<tr> 
						<td> J.Ravikumar </td>						
						<td> 8 </td>
						<td> KMNR Manakondur (b) </td>				
						<td> C/o Blood cancer,leg pain and fever </td>				
						<td> 05-01-2019 </td>				
						<td>  MNJ hospital ,3 am </td>				
					</tr>
					<tr> 
						<td> S.Deekshitha </td>						
						<td> 5 </td>
						<td> Nizamabad Domakonda(G) </td>				
						<td> C/o Acute lymphoblastic leukemia Cancer  </td>				
						<td> 25-04-2019 </td>				
						<td>  MNJ Hospital </td>				
					</tr>
					<tr> 
						<td> Neha  Gugulothu </td>						
						<td> 8 </td>
						<td> TSWREIS MADHIRA(G),KHAMMAM </td>				
						<td>Giddiness, vomitings, abdominal pain:
She came to hostel on 13-6-19, suffering from abdominal pain 2 days prior.yesterday she complained of three episodes of vomiting and giddiness treated with tab domperidone, ors. she complained about giddiness to HS .she took her to nearest PHC ,where no doctor is available.Then they took her to madhira private hospital,due to low pulse. Doctor referred to higher center .Then HS took her to District hospital khammam. where doctor examined her and declared her death.   </td>				
						<td> 15-06-2019 </td>				
						<td>  District hospital khammam </td>				
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	</article>
	

</div><!-- ROW -->
<br><br>


	<form style="display: hidden" action="<?php echo URL; ?>panacea_mgmt/get_bmi_students_docs" method="POST" 
		id="bmi_report_form">
	  
	  <input type="hidden" id="bmi_type" name="bmi_type" value=""/>
	  <input type="hidden" id="age_bmi_type" name="age_bmi_type" value=""/>
	</form>
	<form style="display: hidden" action="<?php echo URL; ?>panacea_mgmt/get_hb_students_docs" method="POST" 
		id="hb_report_form">
	  
	  <input type="hidden" id="hb_type" name="hb_type" value=""/>
	</form>
	<form style="display: hidden" action="<?php echo URL; ?>panacea_mgmt/get_chronic_students_docs" method="POST" 
		id="chronic_report_form">
	  
	  <input type="hidden" id="chronic_type" name="chronic_type" value=""/>
	</form>
	<form style="display: hidden" action="<?php echo URL; ?>panacea_mgmt/get_emergency_req_students_docs" method="POST" 
		id="emergency_report_form">
	  
	  <input type="hidden" id="emergencyReq" name="emergencyReq" value=""/>
	</form>
	<form style="display: hidden" action="<?php echo URL; ?>panacea_mgmt/get_field_officer_docs" method="POST" 
		id="fieldOfficer_report_form">
	  
	  <input type="hidden" id="fieldOfficerReq" name="fieldOfficerReq" value=""/>
	</form>
	
	<form style="display: hidden" action="<?php echo URL; ?>panacea_mgmt/get_show_ehr_details" method="POST" id="requests_show_form">
	  <input type="hidden" id="to_date_new" name="to_date_new" value=""/>
	  <input type="hidden" id="school_name_new" name="school_name_new" value=""/>
	  <input type="hidden" id="request_type_new" name="request_type_new" value=""/>
	  <input type="hidden" id="request_type_hospitalised" name="request_type_hospitalised" value=""/>
	</form>

	<form style="display: hidden" action="<?php echo URL; ?>panacea_mgmt/get_show_admitted_ehr_details" method="POST" id="admitted_show_form">
	    <input type="hidden" id="start_date_new" name="start_date_new" value=""/>
	    <input type="hidden" id="end_date_old" name="end_date_old" value=""/> 
	    <input type="hidden" id="request_type_newsss" name="request_type_new" value=""/>
	</form>

	<form style="display: hidden" action="tswreis_diseases_counts_report" method="POST" id="abnormality_list_clicked">
        <input type="hidden" id="abnormality_name" name="abnormality_name" value=""/>
        <input type="hidden" id="screen_academic_year" name="academic_year" value=""/>
    </form>

	

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>



<script src="<?php echo JS; ?>echarts.min.js"></script>
<script src="<?php echo JS; ?>plotly-1.2.0.min.js"></script>

<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.barnumbers.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
<script src="<?php echo JS; ?>plugin/morris/raphael.min.js"></script>
<script src="<?php echo JS; ?>plugin/morris/morris.min.js"></script>
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<script src="<?php echo JS; ?>jquery.bootstrap.newsbox.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS; ?>marquee.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script type="text/javascript">

  /*$('#date_set_admitted').click(function(){
  	alert('yoga');
     	console.log('calling id');
     });*/
   $('#date_set_admitted').click(function(){
    	//alert('data came');
            var startDate = $('#passing_date').val();
            var endDate = $('#passing_end_date').val();
            //var requestsCount = $('#no_of_requests').val();
            $("#loading_modal").modal('show');
                $.ajax({
                    url : 'last_three_months_req_monitoring',
                    type : 'POST',
                    data : {"start_date" : startDate, "end_date": endDate},
                    success : function(data){
                      $("#loading_modal").modal('hide');
                        $('#students_more_req').empty();
                        var result = $.parseJSON(data);
                        data_table_req(result);
                    }
                });
        });
    var today_date = $('#set_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#set_date').change(function(e){
    today_date = $('#set_date').val();;
    });
 last_three_months_more_req_students();

     function last_three_months_more_req_students(){
        var todayDate = $('#passing_date').val();
        var endDate = $('#passing_end_date').val();
        //var requestsCount = $('#no_of_requests').val();
        $("#loading_modal").modal('show');
        $.ajax({
            url : 'last_three_months_req_monitoring',
            type : 'POST',
            data : {"start_date" : startDate, "end_date": endDate},
            success : function(data){

              $("#loading_modal").modal('hide');
                
                var result = $.parseJSON(data); 
                data_table_req(result);
              
            }
        });

     };

      function data_table_req(result)
      { 
      	 start_date = $("#passing_date").val();
      	 end_date   = $("#passing_end_date").val();
      	 //school_name = $('#school_name').val();

      url = '<?php echo URL."panacea_mgmt/get_show_admitted_ehr_details"; ?>';

        $('#request_hospitalized_table_view').html('<div id="request_hospitalised" class="text-center"></div>');

            var table = '<div style="overflow-y: auto;" ><table class="table table-striped table-bordered table-hover" id="requestTable"><thead><tr><th>Status</th><th class="text-center">Requests count</th><th class="text-center">Action</th></tr></thead><tbody>'
                
              table = table + '<tr><th>Admitted</th><td><center>'+result.hospitalised+'</center></td><td class="hide">'+start_date+'</td><td class="hide">'+end_date+'</td><td class="hide">Admitted</td><td class="hide">'+school_name+'</td><td><center><button class="addd">Show</button></center></td></tr>'

                table = table + '<tr><th>Review</th><td><center>'+result.reiew+'</center></td><td class="hide">'+today_date+'</td><td class="hide">Review</td><td class="hide">'+school_name+'</td><td><center><button class="addd">Show</button></center></td></tr>'
                
                table = table + '<tr><th>Out-Patient</th><td><span id="chronic_request_uniqueids"><center>'+result.out+'</center></td><td class="hide">'+today_date+'</td><td class="hide">Out-Patient</td><td class="hide">'+school_name+'</td><td><center><button class="addd">Show</button></center></td></tr>'

            $("#request_hospitalised").html(table);
            table = table + '</tbody></table></div>';

        $("#requestTable").each(function(){
            $('.addd').click(function (){
            	 start_date = $("#passing_date").val();
      			 end_date   = $("#passing_end_date").val();
                var currentRow=$(this).closest("tr"); 
                 
                 //var request_type_new=currentRow.find("th:eq(2)").text();
                 //var request_type_newww=currentRow.find("th:eq(3)").text();
                 var request_type_new = currentRow.find("th:eq(0)").text();
                 //alert(request_type_new);
                //var request_type_status=currentRow.find("td:eq(4)").text(); // get current row 4th TD
                       	
                $("#start_date_new").val(start_date);
                $("#end_date_old").val(end_date);
                $("#request_type_newsss").val(request_type_new);
                //$("#request_type_status").val(request_type_status);
                $("#admitted_show_form").submit();

                 
            });
         });
    } 

</script>

<script>
$(document).ready(function() {
	var screening_pie_span = "2018-19 Academic Year";
	var screening_data = "";
	var screening_navigation = [];
	previous_screening_a_value = [];
	previous_screening_title_value = [];
	previous_screening_search = [];
	search_arr = [];

	$('#total_active_req').html("Active request today : "+<?php echo $total_active_req;?>);
	$('#total_rised_req').html("Raised request today : "+<?php echo $total_raised_req;?>);

	var absent_sent_schools     = <?php echo $absent_report_schools_list['submitted_count'];?>;
	var absent_not_sent_schools = <?php echo $absent_report_schools_list['not_submitted_count'];?>;
	var absent_submitted_schools_list     = "";
	var absent_not_submitted_schools_list = "";
	
	absent_submitted_schools_list         = <?php echo json_encode($absent_report_schools_list['submitted']);?>;
	absent_not_submitted_schools_list     = <?php echo json_encode($absent_report_schools_list['not_submitted']);?>;

	var sani_repo_sent_schools     = <?php echo $sanitation_report_schools_list['submitted_count'];?>;
	var sani_repo_not_sent_schools = <?php echo $sanitation_report_schools_list['not_submitted_count'];?>;
	var sani_repo_submitted_schools_list     = "";
	var sani_repo_not_submitted_schools_list = "";
	var chronic_id_list        		      = "";
	
	sani_repo_submitted_schools_list         = <?php echo json_encode($sanitation_report_schools_list['submitted']);?>;
	sani_repo_not_submitted_schools_list     = <?php echo json_encode($sanitation_report_schools_list['not_submitted']);?>;
	
	var today_date = $('#set_date').val();
	var dt_name = $('#select_dt_name').val();
	var school_name = $('#school_name').val();
		
		//console.log('php111111111111111', today_date);
		
		$('.datepicker').datepicker({
			minDate: new Date(1900, 10 - 1, 25)
		});
		
		change_to_default();
		display_sanitation_graph_default();
		screenings_pie();

 // New SCreening Pie
		function screenings_pie(){
        var academicYear = $('#screening_pie_spanss').val();

        //alert(academicYear);

        $.ajax({

            url : 'get_screening_pie_values',
            type: 'POST',
            data: {'academic_year':academicYear},
            success: function(data){

                $('#screen_pie_new_one').empty();
                    
                var datas = $.parseJSON(data);

                new_screening_pie_data(datas);
                //console.log("dataaaa haerish", datas);

            }

        });
    };

    $('#screening_pie_spanss').change(function(){

    	var academicYear = $('#screening_pie_spanss').val();

        $.ajax({

            url : 'get_screening_pie_values',
            type: 'POST',
            data: {'academic_year':academicYear},
            success: function(data){

                $('#screen_pie_new_one').empty();
                    
                var datas = $.parseJSON(data);

                new_screening_pie_data(datas);
            }

        });

    })

    function new_screening_pie_data(datas){
    	$('#screen_pie_new_one').empty();
    	var pie = new d3pie("screen_pie_new_one", {
                    
                    "footer": {
                        "color": "#999999",
                        "fontSize": 10,
                        "font": "open sans",
                        "location": "bottom-left"
                    },
                    "size": {
                        "canvasWidth": 400,
                        //"pieOuterRadius": "90%"
                        canvasHeight: 400,
                       
                    },
                    "data": {
                        "sortOrder": "value-desc",
                        "content": datas
                    },
                    "labels": {
                        "outer": {
                            "format": "label-value2",
                            "pieDistance": 32
                        },
                        "inner": {
							"format": "value",
							"color": "#28b83a",
							"hideWhenLessThanPercentage": 3
						},
                        "mainLabel": {
                            "color": "#28b83a",
                            "fontSize": 10
                        },
                        "percentage": {
                            "color": "#ffffff",
                            "decimalPlaces": 0
                        },
                        "value": {
                            "color": "#FF0000",
                            "fontSize": 10
                        },
                        "lines": {
                            "enabled": true
                        }
                    },
                    "tooltips": {
                        "enabled": true,
                        "type": "placeholder",
                        "string": "{label}: {value}"
                    },
                    "effects": {
                        "pullOutSegmentOnClick": {
                            "effect": "linear",
                            "speed": 400,
                            "size": 8
                        }
                    },
                    "misc": {
                        "gradient": {
                            "enabled": true,
                            "percentage": 100
                        },
                        "canvasPadding": {
                            "right": 3
                        }
                    },

                    callbacks: {
                        onClickSegment: function(a) {
                            
                             var dats = a.data;
                             var label_name = dats.label;
                             //alert(label_name);
                             displayData(label_name);
                            
                             function displayData(label_name) {
                                    var academicYear = $('#get_academic_year').val();
                                   // alert(academicYear);
                                    $('#screen_academic_year').val(academicYear);
                                     $("#abnormality_name").val(label_name);
                                    
                                     $("#abnormality_list_clicked").submit();
                                }
                            
                        }
                    }
                        
        });
    }

 // New SCreening Pie

		function change_to_default(today_date,absent_report,request_report,symptoms_report,screening_report)
		{
			$('#request_pie_span').val("Daily");
			$('#request_pie_status').val("All");
			$('#screening_pie_span').val("2018-19 Academic Year");
			$('#school_name').val(school_name);

			today_date = today_date;
			//$('#screening_pie_span').val("Yearly");
			request_count_all();
			get_sevier_count();
			show_tails_label_counts();
		}
		
		initialize_variables(today_date,<?php echo $screening_report?>,<?php echo $request_report?>,<?php echo $symptoms_report?>,<?php echo $absent_report?>);
		function initialize_variables(today_date,screening_report,request_report,symptoms_report,absent_report)
		{
			today_date = today_date;
			init_screening_pie(screening_report);
			init_req_id_pie(request_report,symptoms_report);
			init_absent_pie(absent_report);

			$('.sanitation_report_status_date').html('');
			$('.sanitation_report_status_date').html(today_date);
			$('.abs_submitted_schools').html(absent_sent_schools);
			$('.abs_not_submitted_schools').html(absent_not_sent_schools);
			$('.sanitation_report_submitted_schools').html(sani_repo_sent_schools);
			$('.sanitation_report_not_submitted_schools').html(sani_repo_not_sent_schools);
		}

		function update_absent_schools_data(absent_submitted_schools_list_count,absent_not_submitted_schools_list_count)
		{
			$('.abs_submitted_schools').html(absent_submitted_schools_list_count);
			$('.abs_not_submitted_schools').html(absent_not_submitted_schools_list_count);
		}
		function init_absent_pie(absent_report){
			absent_data = absent_report;
			absent_navigation = [];
			previous_absent_a_value = [];
			previous_absent_title_value = [];
			previous_absent_search = [];
			absent_search_arr = [];
		}
		function init_req_id_pie(request_report,symptoms_report){
			request_data = request_report;
			request_navigation = [];
			previous_request_a_value = [];
			previous_request_title_value = [];
			previous_request_search = [];
			search_arr = [];
			
			identifiers_data = symptoms_report;
			identifiers_navigation = [];
			previous_identifiers_a_value = [];
			previous_identifiers_title_value = [];
			previous_identifiers_search = [];
			search_arr = [];
		}
		
		function request_count_all()
		{
			$.ajax({
			url: 'initiate_request_count_all_today_date',
			type: 'POST',
			data: {"today_date" : today_date},
			success: function (data) {			
				$('#load_waiting').modal('hide');
				result = $.parseJSON(data);
				document_details_list(result);
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			})
			
		}
		draw_absent_pie();
		draw_identifiers_pie();
		draw_request_pie();
		stu_age = $('#age option:selected').text();
		type = $(".student_type").val();

		$(".student_type").each(function()
		{
			$(this).click(function ()
			{
				var type = $(this).val();
				stu_age = $('#age option:selected').text();
				request_pie_span = $('#request_pie_span').val();
				request_pie_status = $('#request_pie_status').val();
				$( "#req_id_pies" ).hide();
				$("#loading_req_pie").show();
				stu_type = type;
				$.ajax({
					url: 'get_requests_students_values',
					type: 'POST',
					data: {"today_date" : today_date, "request_pie_span" : request_pie_span, "request_pie_status" : request_pie_status,"student_type" : type,'student_age':stu_age},
					success: function (data) 
					{			
						$("#loading_req_pie").hide();
						$( "#req_id_pies" ).show();
						
						$( "#pie_request" ).empty();
						$( "#pie" ).empty();
						
						data = $.parseJSON(data);
						request_report = $.parseJSON(data.request_report);
						symptoms_report = $.parseJSON(data.symptoms_report);
						
						init_req_id_pie(request_report,symptoms_report);
						draw_identifiers_pie();
						draw_request_pie();
							
					},
				    error:function(XMLHttpRequest, textStatus, errorThrown)
					{
					 console.log('error', errorThrown);
				    }
				});
			})
		});

		$('#request_pie_span').change(function(e)
		{
			request_pie_span = $('#request_pie_span').val();
			request_pie_status = $('#request_pie_status').val();
			$( "#req_id_pies" ).hide();
			$("#loading_req_pie").show();
			console.log('error', today_date);
			console.log('error', request_pie_span);
			$.ajax({
				url: 'update_request_pie',
				type: 'POST',
				data: {"today_date" : today_date, "request_pie_span" : request_pie_span, "request_pie_status" : request_pie_status},
				success: function (data) {			
					$("#loading_req_pie").hide();
					$( "#req_id_pies" ).show();
					
					$( "#pie_request" ).empty();
					$( "#pie" ).empty();
					
					data = $.parseJSON(data);
					request_report = $.parseJSON(data.request_report);
					symptoms_report = $.parseJSON(data.symptoms_report);
					
					init_req_id_pie(request_report,symptoms_report);
					draw_identifiers_pie();
					draw_request_pie();			
					},
				    error:function(XMLHttpRequest, textStatus, errorThrown)
					{
					 console.log('error', errorThrown);
				    }
				});
	
		});
		$('#request_pie_status').change(function(e)
		{
			request_pie_span = $('#request_pie_span').val();
			request_pie_status = $('#request_pie_status').val();
			$( "#req_id_pies" ).hide();
			$("#loading_req_pie").show();
			console.log('error', today_date);
			console.log('error', request_pie_span);
			$.ajax({
				url: 'update_request_pie',
				type: 'POST',
				data: {"today_date" : today_date, "request_pie_span" : request_pie_span, "request_pie_status" : request_pie_status},
				success: function (data) {			
					$("#loading_req_pie").hide();
					$( "#req_id_pies" ).show();
					
					$( "#pie_request" ).empty();
					$( "#pie" ).empty();
					
					data = $.parseJSON(data);
					request_report = $.parseJSON(data.request_report);
					symptoms_report = $.parseJSON(data.symptoms_report);
					
					init_req_id_pie(request_report,symptoms_report);
					draw_identifiers_pie();
					draw_request_pie();			
					},
				    error:function(XMLHttpRequest, textStatus, errorThrown)
					{
					 console.log('error', errorThrown);
				    }
				});
		});

		function draw_identifiers_pie()
		{
			if(identifiers_data == 1){
				console.log('in false of abbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
				$("#pie").append('No positive values to dispaly');
			}else{
				identifiers_navigation.push("Symptoms Pie Chart");
				identifiers_pie("Symptoms Pie Chart",identifiers_data,"drill_down_identifiers_to_districts");
			}
		}
		

function request_pie(heading, request_data, onClickFn,type){
	var pie = new d3pie("pie_request", {
		header: {
			title: {
				text: request_navigation.join(" / "),
				/*color:    "#fff",*/
			}
		},
		size: {
	        canvasHeight: 300,
	        canvasWidth: 350//400
	    },
	    labels: {
			outer:
			{
				pieDistance:10
			},
	        inner: {
	            format: "value"
	        },
		/*	mainLabel: {
				color: "#fff",
				font: "arial",
				fontSize: 15
				},*/
			 truncation: {
				enabled: true,
				truncateLength:10
			} 
	    },
	    data: {
	      content: request_data
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					d3.select(this).on('click',null);
					console.log('contentcontent===========',request_data);
					if(onClickFn == "drill_down_request_to_districts")
						{
							
						console.log(a);
						previous_request_a_value[1] = request_data;
						previous_request_title_value[1] = "Request Pie Chart";
						drill_down_request_to_districts(a);
					}else if (onClickFn == "drill_down_request_to_schools"){
						console.log(a);
						previous_request_a_value[2] = request_data;
						previous_request_title_value[2] = heading;
						previous_request_search[2] = heading;
						console.log(previous_request_a_value);
						search_arr[0] = previous_request_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_request_to_schools(search_arr);
					}else if (onClickFn == "drill_down_request_to_students"){
						search_arr[0] = previous_request_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_request_to_students(search_arr);
					}else {
						index = onClickFn;
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						//previous_screening_a_value[index] = previous_screening_a_value[index];
						//previous_screening_title_value[index] = previous_screening_title_value[index];
						//previous_screening_search[index] = previous_screening_title_value[index];
						console.log("value from previous function -------------------------------------------");
						//console.log(previous_screening_a_value);

						if (index == 1){
							drill_down_request_to_districts(a);
						}else if (index == 2){
							search_arr[0] = previous_request_search[2];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_request_to_schools(search_arr);
						}
					}
				}
			}
	      
	});
}

function draw_request_pie()
		{
			if(request_data == 1){
				$("#pie_request").append('No positive values to dispaly');
			}else{
				request_navigation.push("Request Pie Chart");
				request_pie("Request Pie Chart",request_data,"drill_down_request_to_districts");
			}
		}

function drill_down_request_to_districts(pie_data)
{
	
	request_pie_span = $('#request_pie_span').val();
	request_pie_status = $('#request_pie_status').val();
	$.ajax({
		url: 'drilldown_request_to_districts',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "today_date" : today_date,"dt_name" : dt_name, "school_name" : school_name, "request_pie_span" : request_pie_span, "request_pie_status":request_pie_status,"student_type" : type,'student_age': stu_age},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie_request" ).empty();
			$("#pie_request").append('<button class="btn btn-primary pull-right" id="reports_back_btn" ind="1"> Back </button>');
			request_navigation.push(pie_data.data.label);
			request_pie(pie_data.data.label,content,"drill_down_request_to_schools");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function drill_down_request_to_schools(pie_data){
	$.ajax({
		url: 'drilling_request_to_schools',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name,"request_pie_status":request_pie_status},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie_request" ).empty();

			$("#pie_request").append('<button class="btn btn-primary pull-right" id="reports_back_btn" ind="2"> Back </button>');
			request_navigation.push(pie_data[1]);
			request_pie(pie_data[1],content,"drill_down_request_to_students");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

$(document).on("click",'#reports_back_btn',function(e){
	var index = $(this).attr("ind");
	$( "#pie_request" ).empty();
	if(index>1){
		var ind = index - 1;
	$("#pie_request").append('<button class="btn btn-primary pull-right" id="reports_back_btn" ind="' + ind + '"> Back </button>');
	}
	request_navigation.pop();
	request_pie(previous_request_title_value[index], previous_request_a_value[index], index);
});

function drill_down_request_to_students(pie_data){
$.ajax({
		url: 'drill_down_request_to_students',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name,"request_pie_status":request_pie_status},
		success: function (data) {
			console.log(data);
			$("#ehr_data_for_request").val(data);
			request_navigation.push(pie_data[1]);
			$("#ehr_navigation_for_request").val(request_navigation.join(" / "));
				
			//window.location = "drill_down_screening_to_students_load_ehr/"+data;
			//alert(data);
			
			$("#ehr_form_for_request").submit();
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

		


		function get_sevier_count()
		{
			//student_type =  $('input[class = student_type_for_tails]:first').attr('checked', true);
			student_type = $("input[name ='student_type']:checked").val();
			console.log('student_type=====', student_type);
			if(student_type == "All")
			{
				$.ajax({
				url:"get_sevier_count",
				type:"POST",
				data : {'student_type' :  student_type},
				success:function(data){
					counts = $.parseJSON(data);
					//console.log("ccccccccccooooo",counts);
					//var table = '<div style="overflow-y: auto;"><h4>Below 6 HB Count : '+counts.below_6_hb_values+'</h4><p></p> <h4>Severe Count : '+counts.sevier+'</h4><p></p><h4>Mild Count : '+counts.mild+'</h4><p></p><h4>Moderate Count : '+counts.moderate+'</h4></div>'
					/*var table = '<div style="overflow-y: auto;" ><table class="table table-striped table-bordered table-hover" style = "color :darkcyan"><thead><tr><th colspan="2" class="text-center">Sevier Counts </th> <td>'+counts.sevier+'</td></tr><tr><th colspan="2" class="text-center">Mild Counts </th> <td>'+counts.mild+'</td></tr><tr><th colspan="2" class="text-center">Moderate Counts </th> <td>'+counts.moderate+'</td></tr></thead><tbody>'*/
					$('#below_6_hb').html(counts.below_6_hb_values);
					$('#severe_hb').html(counts.sevier);					
					$('#mild_hb').html(counts.mild);	
					$('#moderate_hb').html(counts.moderate);
					//$('#sevier_count').html(table);
				},
				error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
				})
			}
			
		}

		$('.student_type_for_tails').each(function()
		{
			
			$(this).click(function(){
				var type = $(this).val();
				stu_age = $('#age option:selected').text();
				//$('#tails_counts_update_loading').show();
				$.ajax({
					url : 'get_student_type_for_tails',
					type : 'POST',
					data : {'student_type' : type},
					success:function(data)
					{
						$('#load_waiting').modal('hide');
						counts = $.parseJSON(data);
						$('#below_6_hb').html(counts.hb.below_6_hb_values);
						$('#severe_hb').html(counts.hb.sevier);					
						$('#mild_hb').html(counts.hb.mild);	
						$('#moderate_hb').html(counts.hb.moderate);

						//Showing BMI Reports
						$('#below_14_bmi').html(counts.bmi.below_14_bmi_values);					
						$('#under_weight_bmi').html(counts.bmi.under_weight);					
						$('#over_weight_bmi').html(counts.bmi.over_weight);					
						$('#normal_weight_bmi').html(counts.bmi.normal_weight);

						// Showing Chronic Cases
						$('#chronic_anemia').html(counts.chronic.anemia);	
						$('#chronic_tb').html(counts.chronic.tb);
						$('#chronic_asthma').html(counts.chronic.asthma);	
						$('#chronic_hiv').html(counts.chronic.hiv);	
						$('#chronic_scabies').html(counts.chronic.scabies);	
						$('#chronic_epilepsy').html(counts.chronic.epilepsy);	
						$('#chronic_hypothyroidism').html(counts.chronic.hypothyroidism);
						$('#chronic_diabetes').html(counts.chronic.diabetese);
						// Total Requests Count
						$('#normalReqCount').html(counts.request.total_normal_req_count);
						$('#emergencyReqCount').html(counts.request.total_emergency_req_count);
						$('#chronicReqCount').html(counts.request.total_chronic_req_count);
					},
					error:function(XMLHttpRequest, textStatus, errorThrown)
					{
						console.log('error', errorThrown);
					}
				});
			});
		});

		function show_tails_label_counts()
		{
			student_type = $("input[name ='student_type']:checked").val();
			$.ajax({
				url:"show_tails_label_counts",
				type:"POST",
				data:{'student_type' : student_type},
				success:function(data){
					counts = $.parseJSON(data);
					
					//Showing BMI Reports
					$('#below_14_bmi').html(counts.below_14_bmi_values);					
					$('#under_weight_bmi').html(counts.under_weight);					
					$('#over_weight_bmi').html(counts.over_weight);					
					$('#normal_weight_bmi').html(counts.normal_weight);
					// Showing Chronic Cases
					$('#chronic_anemia').html(counts.chronic_count.anemia);	
					$('#chronic_tb').html(counts.chronic_count.tb);
					$('#chronic_asthma').html(counts.chronic_count.asthma);	
					$('#chronic_hiv').html(counts.chronic_count.hiv);	
					$('#chronic_scabies').html(counts.chronic_count.scabies);	
					$('#chronic_epilepsy').html(counts.chronic_count.epilepsy);	
					$('#chronic_hypothyroidism').html(counts.chronic_count.hypothyroidism);
					$('#chronic_diabetes').html(counts.chronic_count.diabetese);

					// Total Requests Count
					$('#normalReqCount').html(counts.requests_total_count.total_normal_req_count);
					$('#emergencyReqCount').html(counts.requests_total_count.total_emergency_req_count);
					$('#chronicReqCount').html(counts.requests_total_count.total_chronic_req_count);
					$('#doctorVisitReqCount').html(counts.requests_total_count.doctor_visits_total_count);

					// Field Officeres Counts
					$('#outPatientReqCount').html(counts.requests_total_count.out_patient_total_count);
					$('#admittedReqCount').html(counts.requests_total_count.admitted_total_count);
					$('#reviewCasesReqCount').html(counts.requests_total_count.review_cases_total_count);
					$('#surgeryCasesReqCount').html(counts.requests_total_count.surgery_cases_total_count);
					

				/*var screened_count = '<h4>Eyes Screened : '+counts.screened_count.eye_screened_count+'</h4><p></p><h4>Dental Screened : '+counts.screened_count.dental_screened_count+'</h4><p></p><h4>Attendance Submitted : '+counts.attendance_count+'</h4><p></p><h4>Sanitation Submitted : '+counts.sanitation_count+'</h4>'*/
				var screened_count = 'Eyes Screened : '+counts.screened_count.eye_screened_count+' Dental Screened : '+counts.screened_count.dental_screened_count+' ';
					//$('#bmi_count').html(bmi_count);
					$('#screened_count_tail').html(screened_count);
					//$('#chronic_asthma_count').html(chronic_asthma_count);

				},
				error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			})
		}

		

		$('#set_date_btn').click(function(e){
			
			today_date = $("#set_date").val();
			school_name = $("#school_name").val();
			//console.log('school_name',school_name);
			$.ajax({
			url: 'initiate_request_count_all_today_date',
			type: 'POST',
			data: {"today_date" : today_date},
			success: function (data) {			
				$('#load_waiting').modal('hide');
				$("#submitted_by_name_dr1").html("");
				$("#submitted_by_name_dr2").html("");
				$("#submitted_by_name_dr3").html("");
				$("#submitted_by_name_dr4").html("");
				$("#submitted_by_name_dr5").html("");
				$("#submitted_by_name_dr6").html("");
				$("#submitted_by_name_dr7").html("");
				result = $.parseJSON(data);
				
				document_details_list(result);
				
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			})
		});
		
	
		
		 
//debugger;
	$('#chronic_request_uniqueids').click(function(){
	//console.log("hhhhhhhhhh");
		$('#chronic_request_modal_body').empty();
		var table="";
		var tr="";
		table += "<table class='table table-bordered' id='absent_not_submitted_schools_list_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
		for(var i=0;i<absent_not_submitted_schools_list['school'].length;i++)
		{
			var j=i+1;
			table+= "<tr><td>"+j+"</td><td>"+absent_not_submitted_schools_list['district'][i]+"</td><td>"+absent_not_submitted_schools_list['school'][i]+"</td><td>"+absent_not_submitted_schools_list['mobile'][i]+"</td><td>"+absent_not_submitted_schools_list['person_name'][i]+"</td></tr>"
		}
		table += "</tbody></table>";
		console.log(table)
		$(table).appendTo('#chronic_request_modal_body');
		
	
	$('#chronic_modal').modal('show');
})

	/*******************************************************************
 *
 * Helper : Initialize for screening pie
 *
 */
 
function init_screening_pie(screening_report,schoolEmail)
{
	screening_data 					= screening_report;
	schoolEmail 					= schoolEmail;
	screening_navigation            = [];
	previous_screening_a_value 		= [];
	selected_school_email 			= [];
	previous_screening_title_value 	= [];
	previous_screening_search 		= [];
	search_arr 						= [];
}

	$('#select_dt_name').change(function(e){
		dist = $('#select_dt_name').val();
		dt_name = $("#select_dt_name option:selected").text();
		//alert(dist);
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
				options.append($("<option />").val("All").prop("selected", true).text("All"));
				$.each(result, function() {
					options.append($("<option />").val(this.school_name).text(this.school_name));
				})		
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
		
	});
	
	$('#school_name').change(function(e){
		school_name = $("#school_name option:selected").text();
	});


$('#set_button').click(function(e){
	var today_date = $('#set_date').val();
	 dt_name = $('#select_dt_name').val();
	 school_name = $('#school_name').val();
	if(dt_name == "All" && school_name == "All"){

	$.ajax({
		url: 'basic_dashboard_with_date',
		type: 'POST',
		data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
					console.log('ALLLLLLL', data);
				$( "#screening_report" ).empty();
				data = $.parseJSON(data);
				
					screening_report  = $.parseJSON(data.screening_report);
					result  = $.parseJSON(data.sanitation_report);
					//display_sanitation_graph(result);
					//display_yes_no_table(result);
					initialize_variables(today_date,screening_report);
		
					draw_all_screening_pie();

			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}else{
	
	$.ajax({
		url: 'get_screening_data_with_district_school',
		type: 'POST',
		data: {/*"today_date" : today_date, "screening_pie_span" : screening_pie_span,*//* "dt_name" : dist,*/"today_date" : today_date, "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
			$('#select_year_wise').hide();
				console.log('FOR_SCREENING',data);
				result = $.parseJSON(data);
				console.log('total information', result);
				screening_data = result.screening_report;
				schoolEmail = result.school_email_id;
				
				init_screening_pie(screening_data,schoolEmail);
				document_details_list(result)
				display_data_table(screening_data);
				$('#showPIEBtn').html('<button class="btn bg-color-green txt-color-white pull-right" id="showPIE">Show PIE</button>');
				$('#showPIE').click(function(){
					draw_screening_pie();
					$('#test').removeClass('hide');

				});
				result = result.sanitation_report;
				draw_sanitation_report_table(result);
				
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}
	
});

$('#refresh_screening_data').click(function(e){
	$( "#screening_update_loading" ).show();
	$( "#refresh_screening_data" ).prop('disabled', true);
	$.ajax({
		url: 'refresh_screening_data',
		type: 'POST',
		data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			console.log(data);
			data = data;
			$("#refresh_date").text(data);
			$( "#screening_update_loading" ).hide();
			$( "#refresh_screening_data" ).prop('disabled', false);
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
});

	function display_sanitation_graph_default(){

		var today_date = $('#set_date').val();
		var dt_name = $('#select_dt_name').val();
		var school_name = $('#school_name').val();
		
		if(dt_name == "All" && school_name == "All"){
		$.ajax({
			url: 'basic_dashboard_with_date',
			type: 'POST',
			data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span, "dt_name" : dt_name, "school_name" : school_name},
			success: function (data) {
				$('#load_waiting').modal('hide');
				
					$( "#screening_report" ).empty();
					data = $.parseJSON(data);
						screening_report  = $.parseJSON(data.screening_report);
						result  = $.parseJSON(data.sanitation_report);
						//display_sanitation_graph(result);
						//display_yes_no_table(result);
						initialize_variables(today_date,screening_report);
						draw_all_screening_pie();				
				
				},
				error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
				}
			});
	}
	}

	function document_details_list(result)
	{
		today_date = $("#set_date").val();
		school_name = $('#school_name').val();
		
		url = '<?php echo URL."panacea_mgmt/get_show_ehr_details"; ?>';

		$('#request_hospitalized_table_view').html('<div id="request_admitted" class="text-center"></div>');

            var table = '<div style="overflow-y: auto;" ><table class="table table-striped table-bordered table-hover" id="requestTable"><thead><tr><th>Status</th><th class="text-center">Requests count</th><th class="text-center">Action</th></tr></thead><tbody>'
                
                table = table + '<tr><th>Admitted</th><td><center>'+result.hospitalised_count+'</center></td><td class="hide">'+today_date+'</td><td class="hide">Admitted</td><td class="hide">'+school_name+'</td><td><center><button class="btnShow">Show</button></center></td></tr>'

                table = table + '<tr><th>Review</th><td><center>'+result.review_count+'</center></td><td class="hide">'+today_date+'</td><td class="hide">Review</td><td class="hide">'+school_name+'</td><td><center><button class="btnShow">Show</button></center></td></tr>'
                
                table = table + '<tr><th>Out-Patient</th><td><span id="chronic_request_uniqueids"><center>'+result.out_patient_count+'</center></td><td class="hide">'+today_date+'</td><td class="hide">Out-Patient</td><td class="hide">'+school_name+'</td><td><center><button class="btnShow">Show</button></center></td></tr>'
                
        
            $("#request_admitted").html(table);
            table = table + '</tbody></table></div>';

		if((result.initiate_request_count_for_today) > 0 ){
			
			initiate_request_count_for_today = result.initiate_request_count_for_today;
			doctors_count = (result.doctors_count) ? result.doctors_count : 0;
			
			  $('#request_response_table_view').html('<div id="request_response" class="text-center"></div>');

			var table = '<div style="overflow-y: auto;" ><table class="table table-striped table-bordered table-hover" id="requestTable"><thead><tr><th></th><th class="text-center">Requests count</th><th class="text-center">Responses count</th></tr></thead><tbody>'

			table = table + '<tr><th>Total Requests</th><td><center>'+initiate_request_count_for_today+'</center></td><td><center>'+doctors_count+'</center></td>'
				
				table = table + '<tr><th>Normal</th><td><center>'+result.normal_requests_count+'</center></td><td class="hide">'+today_date+'</td><td class="hide">Normal</td><td class="hide">'+school_name+'</td><td><center><button class="btnShow">Show</button></center></td></tr>'

				table = table + '<tr><th>Emergency</th><td><center>'+result.emergency_requests_count+'</center></td><td class="hide">'+today_date+'</td><td class="hide">Emergency</td><td class="hide">'+school_name+'</td><td><center><button class="btnShow">Show</button></center></td></tr>'
				
				table = table + '<tr><th>Chronic</th><td><span id="chronic_request_uniqueids"><center>'+result.chronic_requests_count+'</center></td><td class="hide">'+today_date+'</td><td class="hide">Chronic</td><td class="hide">'+school_name+'</td><td><center><button class="btnShow">Show</button></center></td></tr>'
				
		
			$("#request_response").html(table);
			table = table + '</tbody></table></div>';

			if(result.doctors_names)
			{
				console.log(result.doctors_names,'result.doctors_names======12121');
				$('#request_doctors_table_view').html('<div id="request_response_doctors" class="text-center"></div>');

				var table = '<div style="overflow-y: auto;" ><table class="table table-striped table-bordered table-hover" id="requestTable"><thead><tr><th class="text-center">Doctor\'s Name</th><th class="text-center">Counts</th></tr></thead><tbody>'
				$.each(result.doctors_names,function(index,val){
					table = table + '<tr><td>'+index+'</td><td>'+val+'</td></tr>'
				});
				 
				$("#request_response_doctors").html(table);
			table = table + '</tbody></table></div>';
			}else{
			console.log(result.doctors_names,'result.doctors_names======');	
			}

			$('#total_request_count_table').html('<div id="total_request" class="text-center"></div>');

			 table = '<div style="overflow-y : auto"><table class="table table-striped table-bordered table-hover" id="requestTable"><thead><tr><th>Request Types</th><th class="text-center"> Open</th><th class="text-center"> Cured</th><th class="text-center"> Total Requests</th></tr></thead><tbody>'
			   
			   table = table+'<tr><th></th><td></td><td></td><td>'+ <?php echo $total_request_count ;?>+'</td></tr><tr><th>Normal</th><td>'+ <?php echo $normal_req_count_not_cured ;?>+'</td><td>'+ <?php echo $normal_req_count_cured ;?>+'</td><td>'+ <?php echo $normal_req_count ;?>+'</td></tr><tr><th>Emergency</th><td>'+ <?php echo $emergency_req_count_not_cured ;?>+'</td><td>'+ <?php echo $emergency_req_count_cured ;?>+'</td><td>'+ <?php echo $emergency_req_count ;?>+'</td></tr><tr><th>Chronic</th><td>'+ <?php echo $chronic_req_count_not_cured ;?>+'</td><td>'+ <?php echo $chronic_req_count_cured ;?>+'</td><td>'+ <?php echo $chronic_req_count ;?>+'</td></tr>'

			    $("#total_request").html(table);
			table = table + '</tbody></table></div>';				
		}
		else
		{
			initiate_request_count_for_today = result.initiate_request_count_for_today;
			doctors_count = result.doctors_count;
			$("#request_response").html(initiate_request_count_for_today);
			$("#request_response").html(doctors_count);
			//$("#request_response").html("<h6>No Initiate Requests</h6>");
			$("#request_response_table_view").html("<h6>No Initiate Requests</h6>");
			//$("#request_hospitalized_table_view").html("<h6>No Initiate Requests</h6>");
			//$("#submitted_by_name_dr1").html("<h6>No Initiate Requests</h6>");
			//$("#submitted_by_name_dr4").html("<h6>No Initiate Requests</h6>");
		}
			
		$("#requestTable").each(function(){
		 	$('.btnShow').click(function (){
        		 var currentRow=$(this).closest("tr"); 
				 var to_date_new=currentRow.find("td:eq(1)").text(); // get current row 1st TD
				 var request_type_new=currentRow.find("td:eq(2)").text(); // get current row 2nd TD
				 var school_name_new=currentRow.find("td:eq(3)").text(); // get current row 4th TD
				 //var request_type_hospitalised=currentRow.find("td:eq(4)").text();
			
				console.log('to_date_new',to_date_new);
				console.log('request_type_new',request_type_new);
				console.log('school_name_new',school_name_new);
				
				 
				$("#to_date_new").val(to_date_new);
				$("#request_type_new").val(request_type_new);
				$("#school_name_new").val(school_name_new);
				$("#request_type_hospitalised").val(request_type_hospitalised);
				
				$("#requests_show_form").submit();

				 
			});
		 });
	} 

function display_data_table(screening_data){
		
		console.log('screening_data', screening_data);
		//debugger;
		screening_data.forEach(function(e){
		if( e.value === 0){
			$('#abnormalties_report_table').hide();
			$('#pie_screening').hide();
			$("#screening_report").html("<h5 class='text-center'><div class='alert alert-danger'>No Screening data available for this School <strong>"+ school_name+"</strong></div></h5>");
		}
		else{
			$('#abnormalties_report_table').show();
			data_table = '<table id="screening_report_table" class="table table-striped table-bordered" width="100%"><tbody>';
			data_table = data_table + '<tr class="txt-color-magenta"><th>Identifier Name</th><th>No.of Issues found</th><th>Abnormalities</th></tr>';
				$.each(screening_data, function(index, value) {
				
				data_table = data_table + '<tr>';
				data_table = data_table + '<td id="identifier_table">'+value.label+'</td>';
				data_table = data_table + '<td><span class="badge bg-color-orange">'+value.value+'</span></td>';
				data_table = data_table + '<td><button class="btn btn-primary btn-xs identifier_label_btn" id="identifier_label_btn" value="'+value.label+'">View</button></td>';
				data_table = data_table + '</tr>';
			
				});
			data_table = data_table + '</tbody></table>';

			$("#screening_report").html(data_table);
		
	

    	$(".identifier_label_btn").each(function(){
			 	$(this).click(function (){
	        		var selectedLabel = $(this).val();
	        		    $.ajax({
						url: 'get_screening_data_with_abnormalities',
						type: 'POST',
						data: {"selectedLabel" : selectedLabel, "schoolEmail":schoolEmail},
						success: function (data) {
							$('#load_waiting').modal('hide');
							abnormalities = $.parseJSON(data);
							console.log(abnormalities);
							display_abnormalties_table(abnormalities,selectedLabel);
							
							console.log('abnormalities', abnormalities);
							
							},
						    error:function(XMLHttpRequest, textStatus, errorThrown)
							{
							 console.log('error', errorThrown);
						    }
						});
					
				});
			 });

    	}
	})

			
			//=====================================================================================================
			
	}


	function display_abnormalties_table(abnormalities,selectedLabel){
		if(abnormalities.length > 0){
			table = '<div class="panel panel-default"><div class="panel-heading"><h4 class="text-center">'+selectedLabel+'</h4></div><div class="panel-body"><table id="abnormality_report_table" class="table table-striped table-bordered" width="100%"><tbody>';


			$.each(abnormalities, function(index, value) {
				table = table + '<tr>';
				table = table + '<td id="abnormality_label_name">'+value.label+'</td>';
				table = table + '<td>'+value.value+'</td>';
				table = table + '<td><button class="btn btn-primary btn-xs abnormality_label_btn" id="abnormality_label_btn" value="'+value.label+'">Show EHR</button></td>';
				table = table + '</tr>';
					
			});

			table = table + '</tbody></table></div></div>';

			$("#abnormalties_report_table").html(table);

			}

			else
			{
				$("#abnormalties_report_table").html('<h5>No data available</h5>');
			}

			$(".abnormality_label_btn").each(function(){
			 	$(this).click(function (){
	        		var symptome_type = $(this).val();
	        		  $.ajax({
						url: 'drill_down_screening_to_students_count',
						type: 'POST',
						data: {"symptome_type" : symptome_type, "schoolEmail":schoolEmail},
						success: function (data) {
							$("#ehr_data").val(data);
							$("#ehr_navigation").val(symptome_type);
							
							$("#ehr_form").submit();
							
							},
						    error:function(XMLHttpRequest, textStatus, errorThrown)
							{
							 console.log('error', errorThrown);
						    }
						});
					
				});
			 });

		}

				
/*******************************************************************
 *
 * Helper : Screening Pie
 *
 */
 
function screening_pie(heading, screening_data, onClickFn){
	var pie = new d3pie("pie_screening", {
		header: {
			title: {
				text: heading
			}
		},
		size: {
	        canvasHeight: 300,
	        canvasWidth: 350
	    },
	    data: {
	      content: screening_data
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
					
					if(onClickFn == "drilling_screening_to_abnormalities_pie"){
						console.log('drilling_screening_to_abnormalities_pie===461', a);
						previous_screening_a_value[1] = screening_data;
						selected_school_email = schoolEmail;
						console.log('selected_school_email===464', selected_school_email);
						previous_screening_title_value[1] = "Screening Pie Chart";
						
						drilling_screening_to_abnormalities_pie(a);
					}else if(onClickFn == "drill_screening_to_students_pie"){
						
						search_arr =  a.data.label;
						console.log("drill_screening_to_students_pie==1060",search_arr);
						drill_screening_to_students_pie(search_arr);
					}else{
						index = onClickFn;
						
						if(index == 1){
							drilling_screening_to_abnormalities_pie(a);
						}else if (index == 2){
							search_arr[0] = previous_screening_search[3];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_screening_to_students_pie(search_arr);
						}
					}
					
				}
			}
	      
	});
}

/*******************************************************************
 *
 * Helper : Draw screening pie
 *
 */
 
function draw_screening_pie()
{
	if(screening_data == 1)
	{
		$("#pie_screening").append('No positive values to dispaly<br><br>');
	}
	else
	{
		screening_pie("Screening Pie Chart", screening_data, "drilling_screening_to_abnormalities_pie");
	}
}

/*******************************************************************
 *
 * Helper : Screening Pie - drill to abnormalities
 *
 *
 */
 
function drilling_screening_to_abnormalities_pie(pie_data)
{	
	$.ajax({
		url: 'drilling_screening_to_abnormalities_pie',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "schoolEmail":selected_school_email/*, "today_date" : today_date, "screening_pie_span" : screening_pie_span*/},
		success: function (data) {
			console.log('drilling_screening_to_abnormalities_pie====525==',data);
			var content = $.parseJSON(data);
			console.log('CONTENT', content);
			$("#pie_screening").empty();
			$("#pie_screening").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "1"> Back </button>');
			//screening_navigation.push(pie_data.data.label);
			screening_pie(pie_data.data.label, content, "drill_screening_to_students_pie");
			
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});
}

/*******************************************************************
 *
 * Helper : Screening Pie - drill to students
 *
 *
 */
 
function drill_screening_to_students_pie(search_arr)
{
	
	$.ajax({
		url : 'drill_screening_to_students_pie',
		type: 'POST',
		data: {"data" : search_arr, "schoolEmail":selected_school_email/*, "today_date" : today_date, "screening_pie_span" : screening_pie_span*/},
		success: function (data) {
			$("#ehr_data").val(data);
		
			$("#ehr_navigation").val(search_arr);
			
			$("#ehr_form").submit();
			
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
}

/*******************************************************************
 *
 * Helper : Screening Pie - back button functionality
 *
 *
 */
 
$(document).on("click",'#screening_back_btn',function(e){
	
	var index = $(this).attr("ind");
	$( "#screening_report" ).empty();
	
	if(index>1)
	{
		var ind = index - 1;
		$("#screening_report").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "' + ind + '"> Back </button>');
	}	
	screening_navigation.pop();
	screening_pie_all(previous_screening_title_value[index], previous_screening_a_value[index], index);
});

draw_all_screening_pie();

$('#screening_pie_span').change(function(e){
	today_date = $('#set_date').val();
	screening_pie_span = $('#screening_pie_span').val();
	$( "#screening_pies" ).hide();
	$("#loading_screening_pie").show();
	if(screening_pie_span != "Yearly" )
	{
		$("#refresh_screening_data").hide();
	}else{
		$("#refresh_screening_data").show();
	}
	console.log('error', today_date);
	console.log('error11111111', screening_pie_span);
	$.ajax({
		url: 'update_screening_pie',
		type: 'POST',
		data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {			
			$("#loading_screening_pie").hide();
			$( "#screening_report" ).show();
			$( "#screening_report" ).empty();
			data = $.parseJSON(data);
			screening_data = $.parseJSON(data.screening_report);
			console.log(screening_data);
			init_screening_pie(screening_data);
			draw_all_screening_pie();			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});


function screening_pie_all(heading, screening_data, onClickFn){
	var pie = new d3pie("screening_report", {
		header: {
			title: {
				text: screening_navigation.join(" / "),
				"fontSize": 18,
				"font": "open sans"
			}
		},
		size: {
	        canvasHeight: 300,
	        canvasWidth: 350//600
	    },
	    data: {
	      content: screening_data
	    },
	    labels: {
			outer:
			{
				"pieDistance": 20
			},
	        inner: {
	            format: "value",
				},
		/*	mainLabel: {
				color: "#fff",
				font: "arial",
				fontSize: 15
				},*/
			 truncation: {
				enabled: true,
				truncateLength:10
			} 
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					d3.select(this).on('click',null);
					if(onClickFn == "drill_down_screening_to_abnormalities"){
						console.log(a);
						previous_screening_a_value[1] = screening_data;
						previous_screening_title_value[1] = "Screening Pie Chart";
						console.log(previous_screening_a_value);
						drill_down_screening_to_abnormalities(a);
					}else if(onClickFn == "drill_down_screening_to_districts"){
						console.log(a);
						previous_screening_a_value[2] = screening_data;
						previous_screening_title_value[2] = heading;
						previous_screening_search[2] = heading;
						console.log(previous_screening_a_value);
						drill_down_screening_to_districts(a);
					}else if(onClickFn == "drill_down_screening_to_schools"){
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						previous_screening_a_value[3] = screening_data;
						previous_screening_title_value[3] = heading;
						previous_screening_search[3] = heading;
						console.log(previous_screening_a_value);
						search_arr[0] = previous_screening_search[3];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_screening_to_schools(search_arr);
					}else if(onClickFn == "drill_down_screening_to_students"){
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						search_arr[0] = previous_screening_search[3];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_screening_to_students(search_arr);
					}else{
						index = onClickFn;
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						//previous_screening_a_value[index] = previous_screening_a_value[index];
						//previous_screening_title_value[index] = previous_screening_title_value[index];
						//previous_screening_search[index] = previous_screening_title_value[index];
						console.log("value from previous function -------------------------------------------");
						//console.log(previous_screening_a_value);

						if(index == 1){
							drill_down_screening_to_abnormalities(a);
						}else if (index == 2){
							drill_down_screening_to_districts(a);
						}else if (index == 3){
							search_arr[0] = previous_screening_search[3];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_screening_to_schools(search_arr);
						}
					}
					
				}
			}
	      
	});
}

function draw_all_screening_pie(){
	if(screening_data == 1){
		console.log('in false of abbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
		$("#screening_report").append('No positive values to dispaly<br><br>');
	}else{
		screening_navigation.push("Screening Pie Chart");
		screening_pie_all("Screening Pie Chart", screening_data, "drill_down_screening_to_abnormalities");
	
	}
}

function drill_down_screening_to_abnormalities(pie_data){
today_date = $('#set_date').val();
	$.ajax({
		url: 'drilling_screening_to_abnormalities',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#screening_report" ).empty();
			$("#screening_report").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "1"> Back </button>');
			screening_navigation.push(pie_data.data.label);
			screening_pie_all(pie_data.data.label, content, "drill_down_screening_to_districts");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}

function drill_down_screening_to_districts(pie_data){

	$.ajax({
		url: 'drilling_screening_to_districts',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#screening_report" ).empty();
			$("#screening_report").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "2"> Back </button>');
			screening_navigation.push(pie_data.data.label);
			screening_pie_all(pie_data.data.label, content, "drill_down_screening_to_schools");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}

	function drill_down_screening_to_schools(pie_data){
	$.ajax({
		url: 'drilling_screening_to_schools',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#screening_report" ).empty();
			$("#screening_report").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "3"> Back </button>');
			screening_navigation.push(pie_data[1]);
			screening_pie_all(pie_data[1], content, "drill_down_screening_to_students");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}

	function drill_down_screening_to_students(pie_data){		

		$.ajax({
			url: 'drill_down_screening_to_students',
			type: 'POST',
			data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
			success: function (data) {
				console.log(data);
				$("#ehr_data").val(data);
				screening_navigation.push(pie_data[1]);
				$("#ehr_navigation").val(screening_navigation.join(" / "));
				//window.location = "drill_down_screening_to_students_load_ehr/"+data;
				//alert(data);
				
				$("#ehr_form").submit();
				
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
		}

// Absent list sent schools list
$('.abs_submitted_schools_list').click(function(){
	
	if(absent_submitted_schools_list!=null)
	{
		if(absent_submitted_schools_list['school']!="")
		{
			$('#absent_sent_school_modal_body').empty();
			var table="";
			var tr="";
			table += "<table class='table table-bordered' id='absent_sent_school_modal_body_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
			for(var i=0;i<absent_submitted_schools_list['school'].length;i++)
			{
				var j=i+1;
				table+= "<tr><td>"+j+"</td><td>"+absent_submitted_schools_list['district'][i]+"</td><td>"+absent_submitted_schools_list['school'][i]+"</td><td>"+absent_submitted_schools_list['mobile'][i]+"</td><td>"+absent_submitted_schools_list['person_name'][i]+"</td></tr>"
			}
			table += "</tbody></table>";
			$(table).appendTo('#absent_sent_school_modal_body');
		}
		else
		{
			table+="No Schools";
			$(table).appendTo('#absent_sent_school_modal_body');
		}
	}
	else
	{
		table+="No Schools";
		$(table).appendTo('#absent_sent_school_modal_body');
	}
	$('#absent_sent_school_modal').modal('show');
})

// Absent list not sent schools list
$('.abs_not_submitted_schools_list').click(function(){
	
	if(absent_not_submitted_schools_list!=null)
	{
		if(absent_not_submitted_schools_list['school']!="")
		{
			$('#absent_not_sent_school_modal_body').empty();
			var table="";
			var tr="";
			table += "<table class='table table-bordered' id='absent_not_submitted_schools_list_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
			for(var i=0;i<absent_not_submitted_schools_list['school'].length;i++)
			{
				var j=i+1;
				table+= "<tr><td>"+j+"</td><td>"+absent_not_submitted_schools_list['district'][i]+"</td><td>"+absent_not_submitted_schools_list['school'][i]+"</td><td>"+absent_not_submitted_schools_list['mobile'][i]+"</td><td>"+absent_not_submitted_schools_list['person_name'][i]+"</td></tr>"
			}
			table += "</tbody></table>";
			
			$(table).appendTo('#absent_not_sent_school_modal_body');
		}
		else
		{
			table+="No Schools";
			$(table).appendTo('#absent_not_sent_school_modal_body');
		}
	}
	else
	{
		 table+="No Schools";
		 $(table).appendTo('#absent_not_sent_school_modal_body');
	}
	$('#absent_not_sent_school_modal').modal('show');
})

// Absent list sent schools list download
$('#absent_sent_school_download').click(function(){
	
	var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
      var textRange; var j=0;
      tab = document.getElementById('absent_sent_school_modal_body_tab'); // id of table

      for(j = 0 ; j < tab.rows.length ; j++)
      {    
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
      }

      tab_text=tab_text+"</table>";


      var ua = window.navigator.userAgent;
      var msie = ua.indexOf("MSIE ");

      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
      {
         txtArea1.document.open("txt/html","replace");
         txtArea1.document.write(tab_text);
         txtArea1.document.close();
         txtArea1.focus();
         sa=txtArea1.document.execCommand("SaveAs",true,"absent_sent_school_.xlsx");
      } 
      else //other browser not tested on IE 11
         sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text)); 
        return (sa);
})

// Absent list not sent schools list download
$('#absent_not_sent_school_download').click(function(){
	
	      var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
      var textRange; var j=0;
      tab = document.getElementById('absent_not_submitted_schools_list_tab'); // id of table

      for(j = 0 ; j < tab.rows.length ; j++)
      {    
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
      }

      tab_text=tab_text+"</table>";


      var ua = window.navigator.userAgent;
      var msie = ua.indexOf("MSIE ");

      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
      {
         txtArea1.document.open("txt/html","replace");
         txtArea1.document.write(tab_text);
         txtArea1.document.close();
         txtArea1.focus();
         sa=txtArea1.document.execCommand("SaveAs",true,"Global View Task.xls");
      } 
      else //other browser not tested on IE 11
         sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text)); 
        return (sa);
})

$('#set_date_btn_for_attendance').click(function(e){
			today_date = $('#set_date_attendance').val();
			$.ajax({
				url : 'get_date_wise_attendance_report',
				type : 'POST',
				data : {'today_date' : today_date},
				success : function(data)
				{
					$('#load_waiting').modal('hide');
					var absent_data = $.parseJSON(data);
					if(absent_data.absent_report == 1)
					{
						$("#pie_absent").append('No positive values to dispaly');
					}else
					{
						$("#pie_absent").empty();
						$('.abs_submitted_schools').empty();
						$('.abs_not_submitted_schools').empty();						
						$('.abs_submitted_schools').html(absent_data.absent_report_schools_list.submitted_count);
						$('.abs_not_submitted_schools').html(absent_data.absent_report_schools_list.not_submitted_count);
						absent_submitted_schools_list = absent_data.absent_report_schools_list.submitted;
						absent_not_submitted_schools_list = absent_data.absent_report_schools_list.not_submitted;

						absent_navigation.push(today_date);
						absent_pie(today_date,absent_data.absent_report,"drill_down_absent_to_districts");	
					}
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
		})

function draw_absent_pie(){
	if(absent_data == 1){
		$("#pie_absent").append('No positive values to dispaly');
	}else{
		absent_navigation.push(today_date);
		absent_pie(today_date,absent_data,"drill_down_absent_to_districts");
}
}

function absent_pie(heading, data, onClickFn)
{
	var pie = new d3pie("pie_absent", {
		header: {
			title: {
				text: absent_navigation.join(" / "),
				/*color:    "#fff",*/
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
	        },
		/*	mainLabel: {
				color: "#fff",
				font: "arial",
				fontSize: 15
				},*/
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					d3.select(this).on('click',null);
					if(onClickFn == "drill_down_absent_to_districts"){
						console.log(a);
						previous_absent_a_value[1] = absent_data;
						previous_absent_title_value[1] = today_date;
						console.log(previous_absent_a_value);
						console.log("11111111111111111111111111111111111111111");
						drill_down_absent_to_districts(a);
					}else if(onClickFn == "drill_down_absent_to_schools"){
						console.log(a);
						previous_absent_a_value[2] = data;
						previous_absent_title_value[2] = heading;
						previous_absent_search[2] = heading;
						console.log(previous_absent_a_value);
						search_arr[0] = previous_absent_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						console.log("calling school funnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn");
						drill_down_absent_to_schools(search_arr);
					}else if(onClickFn == "drill_down_absent_to_students"){
						search_arr[0] = previous_absent_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						console.log("calling student funcccccccccccccccccccccccccc");
						drill_down_absent_to_students(search_arr);
					}else{
						index = onClickFn;
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						//previous_screening_a_value[index] = previous_screening_a_value[index];
						//previous_screening_title_value[index] = previous_screening_title_value[index];
						//previous_screening_search[index] = previous_screening_title_value[index];
						console.log("value from previous function -------------------------------------------");
						//console.log(previous_screening_a_value);
		
						if (index == 1){
							drill_down_absent_to_districts(a);
						}else if (index == 2){
							search_arr[0] = previous_absent_search[2];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_absent_to_schools(search_arr);
						}
						
					}
					
				}
			}
	      
		});
}




function drill_down_absent_to_districts(pie_data){
	$.ajax({
		url: 'drilldown_absent_to_districts',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data),"today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie_absent" ).empty();
			//$("#pie_absent").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_absent_back(1);"> Back </button>');
			$("#pie_absent").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "1"> Back </button>');

			absent_navigation.push(pie_data.data.label);
			absent_pie(pie_data.data.label,content,"drill_down_absent_to_schools");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}

	function drill_down_absent_to_schools(pie_data){
	
	$.ajax({
		url: 'drilling_absent_to_schools',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data),"today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			var content = $.parseJSON(data);
			$( "#pie_absent" ).empty();
			//$("#pie_absent").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_absent_back(2);"> Back </button>');
			$("#pie_absent").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "2"> Back </button>');

			absent_navigation.push(pie_data[1]);
			absent_pie(pie_data[1],content,"drill_down_absent_to_students");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function drill_down_absent_to_students(pie_data){

	$.ajax({
		url: 'drill_down_absent_to_students',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			console.log(data);
			$("#ehr_data_for_absent").val(data);
			absent_navigation.push(pie_data[1]);
			$("#ehr_navigation_for_absent").val(absent_navigation.join(" / "));
			//window.location = "drill_down_screening_to_students_load_ehr/"+data;
			//alert(data);
			
			$("#ehr_form_for_absent").submit();
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

$(document).on("click",'#absent_back_btn',function(e){
		var index = $(this).attr("ind");
		
		$( "#pie_absent" ).empty();
		if(index>1){
			var ind = index - 1;
		$("#pie_absent").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "' + ind + '"> Back </button>');
		}
		absent_navigation.pop();
		absent_pie(previous_absent_title_value[index], previous_absent_a_value[index], index);
});
		
	/*******************************************************************
 *
 * Helper : Sanitation Report 
 *
 *
 */
 
/*function draw_sanitation_report_table(result)
{
	   

		  $('#sanitation_report_table').html('<div class="row col-md-12"><div id="campus" class="col-md-4"></div><div id="kitchen" class="col-md-4"></div><div id="toilets" class="col-md-4"></div></div><div class="row col-md-12" ><div id="water_Supply_Condition" class="col-md-3"></div><div id="dormitories" class="col-md-3"></div><div id="store" class="col-md-3"></div><div id="waste_management" class="col-md-3"></div></div><div class="row col-md-10"><div id="water" class="col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div><div class="col-md-4" id="campus_attachments"></div><div class="col-md-4" id="kitchen_attachments"></div><div class="col-md-4" id="toilets_attachments"></div><div class="col-md-4" id="dormitories_attachments"></div>');
		 //use chart class in above table
		  var campus           = result.campus;
		
		  var toilets   = result.toilets;
		  var kitchen        = result.kitchen;
		  var dormitories            = result.dormitories;
		  var store 				 = result.store;
		  
		  var waste_management   = result.waste_management;
		  var water   = result.water;
		  var water_Supply_Condition   = result.water_Supply_Condition;
		  var campus_attachments   = result.campus_attachments;
		  var toilets_attachments   = result.toilets_attachments;
		  var kitchen_attachments   = result.kitchen_attachments;
		  var dormitories_attachments   = result.dormitories_attachments;
		

		  
		  
		  //console.log("external_files==>",external_files);
		  
	
		// hand wash
		campus = $.parseJSON(campus);
			if ($("#campus").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered" id="campus_table"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>';
				
				for (var item in campus) {
				  if (campus.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+campus[item].label+'</td><td>'+campus[item].value+'</td></tr>';
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#campus").html(table)
			}

			
			$('#campus').prepend('<div class="">campus</div>');
			// cleanliness
			kitchen = $.parseJSON(kitchen);
			if ($("#kitchen").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>test</th><th>Value</th></tr></thead><tbody>'
				for (var item in kitchen) {
				  if (kitchen.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+kitchen[item].label+'</td><td>'+kitchen[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#kitchen").html(table)
			}
			
			$('#kitchen').prepend('<div class="spec">kitchen</div>');
			
			// water dispensaries
			toilets = $.parseJSON(toilets);
			if ($("#toilets").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;"><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in toilets) {
				  if (toilets.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+toilets[item].label+'</td><td>'+toilets[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#toilets").html(table)
			}
			
			$('#toilets').prepend('<div class="spec">Toilets</div>');
			
			// dormitories
			dormitories = $.parseJSON(dormitories);
			
			if ($("#dormitories").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in dormitories) {
				  if (dormitories.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+dormitories[item].label+'</td><td>'+dormitories[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#dormitories").html(table)
			}
			var campus_daily = $('#daily').prop('checked');
				//debugger;
				if(campus_daily == false){
					$("#campus,#toilets,#kitchen,#campus_attachments,#kitchen_attachments,#toilets_attachments").addClass("hide");
				}else{
					$("#campus,#toilets,#kitchen,#campus_attachments,#kitchen_attachments,#toilets_attachments").removeClass("hide");
				}
			
			$('#dormitories').prepend('<div class="spec">dormitories</div>');
			// Store
			store = $.parseJSON(store);
			if ($("#store").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in store) {
				  if (store.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+store[item].label+'</td><td>'+store[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#store").html(table)
			}
				
		$('#store').prepend('<div class="spec">Store</div>');
		//Waster Management
		waste_management = $.parseJSON(waste_management);
			if ($("#waste_management").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in waste_management) {
				  if (waste_management.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+waste_management[item].label+'</td><td>'+waste_management[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#waste_management").html(table)
			}
				
		$('#waste_management').prepend('<div class="spec">Waste Management</div>');

		//water_supply_condition
		water_Supply_Condition = $.parseJSON(water_Supply_Condition);
			if ($("#water_Supply_Condition").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in water_Supply_Condition) {
				  if (water_Supply_Condition.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+water_Supply_Condition[item].label+'</td><td>'+water_Supply_Condition[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#water_Supply_Condition").html(table)
			}
				
		$('#water_Supply_Condition').prepend('<div class="spec">Wate Supply Condition</div>');
			
			var weekly_submit = $('#weekly').prop('checked');
				//debugger;
				if(weekly_submit == false){
					$("#water_Supply_Condition,#waste_management,#dormitories,#store,#dormitories_attachments").addClass("hide");
				}else{
					$("#water_Supply_Condition,#waste_management,#dormitories,#store,#dormitories_attachments").removeClass("hide");
				}

		//mothly water
		
		water = $.parseJSON(water);
			if ($("#water").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in water) {
				  if (water.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+water[item].label+'</td><td>'+water[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#water").html(table)
			}
			
			$('#water').prepend('<div class="spec">Water</div>');

			var mothly_get = $('#monthly').prop('checked');
				//debugger;
				if(mothly_get == false){
					$("#water").addClass("hide");
				}else{
					$("#water").removeClass("hide");
				}

		
		// campus external files
		campus_attachments = $.parseJSON(campus_attachments);
		//console.log(campus_attachments);	
		var table = '<div style="overflow-y: auto; height:200px;" ><table class=" table table-bordered"><thead><tr><th>Campus Attachments <span class="attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
		var length = Object.keys(campus_attachments).length;
		if(length > 0)
		{
		for(var item in campus_attachments)
		{
	      table = table + '<tr><td><a href="<?php //echo URLCustomer;?>'+campus_attachments[item].file_path+'" rel="prettyPhoto[gal]">'+campus_attachments[item].file_client_name+'</a></td></tr>'
		  
		}
		}
		else
		{
	      table = table + '<tr><td>No attachments </td></tr>'
		}
		
		table = table + '</tbody></table></div>';
		
		$("#campus_attachments").html(table)
		$('.attach_count').text(length);
		
		$("a[rel^='prettyPhoto']").prettyPhoto();

		
		//kitchen_attachments
		kitchen_attachments = $.parseJSON(kitchen_attachments);
		console.log(kitchen_attachments);	
		var table = '<div style="overflow-y: auto; height:200px;" ><table class=" table table-bordered"><thead><tr><th>Kitchen Attachments <span class="attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
		var length = Object.keys(kitchen_attachments).length;
		if(length > 0)
		{
		for(var item in kitchen_attachments)
		{
	      table = table + '<tr><td><a href="<?php //echo URLCustomer;?>'+kitchen_attachments[item].file_path+'" rel="prettyPhoto[gal]">'+kitchen_attachments[item].file_client_name+'</a></td></tr>'
		  
		}
		}
		else
		{
	      table = table + '<tr><td>No attachments </td></tr>'
		}
		
		table = table + '</tbody></table></div>';
		
		$("#kitchen_attachments").html(table)
		$('.attach_count').text(length);
		
		$("a[rel^='prettyPhoto']").prettyPhoto();

		// toilets external files
		toilets_attachments = $.parseJSON(toilets_attachments);
		console.log(toilets_attachments);	
		var table = '<div style="overflow-y: auto; height:200px;" ><table class=" table table-bordered"><thead><tr><th>Toilets Attachments <span class="attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
		var length = Object.keys(toilets_attachments).length;
		if(length > 0)
		{
		for(var item in toilets_attachments)
		{
	      table = table + '<tr><td><a href="<?php //echo URLCustomer;?>'+toilets_attachments[item].file_path+'" rel="prettyPhoto[gal]">'+toilets_attachments[item].file_client_name+'</a></td></tr>'
		  
		}
		}
		else
		{
	      table = table + '<tr><td>No attachments </td></tr>'
		}
		
		table = table + '</tbody></table></div>';
		
		$("#toilets_attachments").html(table)
		$('.attach_count').text(length);
		
		$("a[rel^='prettyPhoto']").prettyPhoto();

		dormitories_attachments = $.parseJSON(dormitories_attachments);
		//console.log(dormitories_attachments);	
		var table = '<div style="overflow-y: auto; height:200px;" ><table class=" table table-bordered"><thead><tr><th>Dormitories Attachments <span class="attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
		var length = Object.keys(dormitories_attachments).length;
		if(length > 0)
		{
		for(var item in dormitories_attachments)
		{
	      table = table + '<tr><td><a href="<?php //echo URLCustomer;?>'+dormitories_attachments[item].file_path+'" rel="prettyPhoto[gal]">'+dormitories_attachments[item].file_client_name+'</a></td></tr>'
		  
		}
		}
		else
		{
	      table = table + '<tr><td>No attachments </td></tr>'
		}
		
		table = table + '</tbody></table></div>';
		
		$("#dormitories_attachments").html(table)
		$('.attach_count').text(length);
		
		$("a[rel^='prettyPhoto']").prettyPhoto();	
}*/

	/*function display_sanitation_graph(result){
	
	var resultLength =  Object.values(result).length;
	
	if(resultLength > 0)
	{
		data_table = '<table id="datatable_fixed_column" class="table table-striped table-bordered"><thead><th></th><th colspan="3">No.of submitted schools</th></tr> <th>Cleanliness in below Area </th><th>Once </th><th>Twice </th><th>Thrice </th> </thead> <tbody>';

		data_table = data_table + '<tr><td>Campus</td><td>'+result.once+ '</td><td>'+result.twice + '</td><td>'+result.thrice + '</td></tr><tr><td>Kitchen</td><td>'+result.kit_once+ '</td><td>'+result.kit_twice + '</td><td>'+result.kit_thrice + '</td></tr><tr><td>Dining Halls</td><td>'+result.dininghall_once+ '</td><td>'+result.dininghall_twice + '</td><td>'+result.dininghall_thrice + '</td></tr><tr><td>Toilets</td><td>'+result.toilets_once+ '</td><td>'+result.toilets_twice + '</td><td>'+result.toilets_thrice + '</td></tr><tr><td>Wellness Centre</td><td>'+result.wellness_centre_once+ '</td><td>'+result.wellness_centre_twice + '</td><td>'+result.wellness_centre_thrice + '</td></tr><tr><td>Dormitory</td><td>'+result.dormitories_once+ '</td><td>'+result.dormitories_twice + '</td><td>'+result.dormitories_thrice + '</td></tr><tr><td>Store</td><td>'+result.store_once+ '</td><td>'+result.store_twice + '</td><td>'+result.store_thrice + '</td></tr><tr><td>Water</td><td>'+result.water_once+ '</td><td>'+result.water_twice + '</td><td>'+result.water_thrice + '</td></tr>';

		$("#sanitation_report_counts").html(data_table);

	
	var dom = document.getElementById("chart_details");

	var myChart = echarts.init(dom);
	var app = {};
	option = null;
	option = {
		legend: {                   
					
					bottom: -5,
				
				},
		tooltip: {},
		dataset: {
			dimensions: ['Cleanliness', 'Campus', 'Kitchen','Toilets', 'Dining Halls','Wellness Centre','Dormitory','Store','Water'],
			 source: [
				{Cleanliness: 'Once', 'Campus': result.once, 'Kitchen': result.kit_once,'Toilets':result.toilets_once, 'Dining Halls': result.dininghall_once,'Wellness Centre':result.wellness_centre_once,'Dormitories':result.dormitories_once,'Store':result.store_once,'Water':result.water_once},
				{Cleanliness: 'Twice', 'Campus': result.twice, 'Kitchen': result.kit_twice,'Toilets':result.toilets_twice, 'Dining Halls': result.dininghall_twice,'Wellness Centre':result.wellness_centre_twice,'Dormitories':result.dormitories_twice,'Store':result.store_twice,'Water':result.water_twice},
				{Cleanliness: 'Thrice', 'Campus': result.thrice, 'Kitchen': result.kit_thrice,'Toilets':result.toilets_thrice, 'Dining Halls': result.dininghall_thrice,'Wellness Centre':result.wellness_centre_thrice,'Dormitories':result.dormitories_thrice,'Store':result.store_thrice,'Water':result.water_thrice}
			   
			]
		},
		xAxis: {type: 'category'},
		yAxis: {},
		
		series: [
			{type: 'bar'},
			{type: 'bar'},
			{type: 'bar'},
			{type: 'bar'},
			{type: 'bar'},
			{type: 'bar'},
			{type: 'bar'},
			{type: 'bar'},
		]
	};

	if (option && typeof option === "object") {
		myChart.setOption(option, true);
	}
	}
	else{
		$('#sanitation_report_table').append('No sanitation report submitted Today ');
	}

	}*/

	/*function display_yes_no_table(result)
	{
		data_table = '<table id="datatable_fixed_col" class="table table-striped table-bordered" ><thead> <th>Item </th><th>Yes </th><th>No</th> </thead> <tbody>';
		data_table = data_table + '<tr><td>Animals Around the Campus</td><td>'+result.animal_yes+ '</td><td>'+result.animal_no + '</td></tr><tr><td>Any Damages To The Toilets</td><td>'+result.damages_toilets_yes+ '</td><td>'+result.damages_toilets_no + '</td></tr><tr><td>Daily Menu Followed</td><td>'+result.kitchen_menu_yes_count+ '</td><td>'+result.kitchen_menu_no_count + '</td></tr><tr><td>Utensils Cleanliness</td><td>'+result.kitchen_Utensils_yes_count+ '</td><td>'+result.kitchen_Utensils_no_count + '</td></tr><tr><td>Hand Gloves Used By Serving People</td><td>'+result.kitchen_hand_gloves_yes_count+ '</td><td>'+result.kitchen_hand_gloves_no_count + '</td></tr><tr><td>Staff members Tasting Food Before Serving Meals</td><td>'+result.kitchen_tasty_food_yes_count+ '</td><td>'+result.kitchen_tasty_food_no_count + '</td></tr><tr><td>RO Plant</td><td>'+result.ro_yes_count+ '</td><td>'+result.ro_no_count + '</td></tr><tr><td>Bore Water</td><td>'+result.bore_yes_count+ '</td><td>'+result.bore_no_count + '</td></tr><tr><td>No Plant Working</td><td>'+result.noplant_yes_count+ '</td><td>'+result.noplant_no_count + '</td></tr><tr><td>Water Tank Cleaning</td><td>'+result.watertank_yes_count+ '</td><td>'+result.watertank_no_count + '</td></tr><tr><td>Any Damages To Beds</td><td>'+result.beddamges_yes_count+ '</td><td>'+result.beddamges_no_count + '</td></tr><tr><td>Any Default Items Issued</td><td>'+result.defaultitem_yes_count+ '</td><td>'+result.defaultitem_no_count + '</td></tr><tr><td>Separate dumping of Inorganic waste</td><td>'+result.inorganic_yes_count+ '</td><td>'+result.inorganic_no_count + '</td></tr><tr><td>Separate dumping of Organic waste</td><td>'+result.organic_yes_count+ '</td><td>'+result.organic_no_count + '</td></tr><tr><td>Dustbins</td><td>'+result.dustbins_yes_count+ '</td><td>'+result.dustbins_no_count + '</td></tr>';
		$("#show_yes_no_count").html(data_table);

		var trace1 = {
		  x: ['Animals Around the Campus', 'Damages To The Toilets', 'Daily Menu Followed', 'Utensils Cleanliness', 'Hand Gloves Used By Serving People','Staff members Tasting Food','RO Plant','Bore Water','No Plant Working','Water Tank Cleaning','Any Damages To Beds','Any Default Items Issued','Inorganic waste','Organic waste','Dustbins'], 
		  y: [result.animal_yes, result.damages_toilets_yes, result.kitchen_menu_yes_count, result.kitchen_Utensils_yes_count,result.kitchen_hand_gloves_yes_count , result.kitchen_tasty_food_yes_count,result.ro_yes_count,result.bore_yes_count,result.noplant_yes_count,result.watertank_yes_count,result.beddamges_yes_count,result.defaultitem_yes_count,result.inorganic_yes_count,result.organic_yes_count,result.dustbins_yes_count],  
		  name: 'Yes', 
		  marker: {color: '#E3AF73'}, 
		  type: 'bar'
		};

		var trace2 = {
		  x: ['Animals Around the Campus', 'Any Damages To The Toilets', 'Daily Menu Followed', 'Utensils Cleanliness', 'Hand Gloves Used By Serving People','Staff members Tasting Food','RO Plant','Bore Water','No Plant Working','Water Tank Cleaning','Any Damages To Beds','Any Default Items Issued','Inorganic waste','Organic waste','Dustbins'], 
		  y: [result.animal_no, result.damages_toilets_no, result.kitchen_menu_no_count, result.kitchen_Utensils_no_count, result.kitchen_hand_gloves_no_count , result.kitchen_tasty_food_no_count,result.ro_no_count,result.bore_no_count,result.noplant_no_count,result.watertank_no_count,result.beddamges_no_count,result.defaultitem_no_count,result.inorganic_no_count,result.organic_no_count,result.dustbins_no_count], 
		  name: 'No', 
		  marker: {color: '#C93633'}, 
		  type: 'bar'
		};

		var data = [trace1, trace2];
		
		var layout = {barmode: 'group'};

		Plotly.newPlot('myDiv', data, layout);
	}*/

	/*$('#set_date_btn_for_sanitation').click(function(e){

		var today_date = $('#set_date_sanitation').val();
		var dt_name = $('#select_dt_name').val();
		var school_name = $('#school_name').val();
		
		if(dt_name == "All" && school_name == "All"){
		$.ajax({
			url: 'basic_dashboard_with_date',
			type: 'POST',
			data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span, "dt_name" : dt_name, "school_name" : school_name},
			success: function (data) {
				$('#load_waiting').modal('hide');
					data = $.parseJSON(data);
										
						result  = $.parseJSON(data.sanitation_report);						
						display_sanitation_graph(result);
						display_yes_no_table(result);
						sanitation_report  = data.sanitation_report_schools_list;						
						$('.sanitation_report_status_date').html(today_date);
						$('.sanitation_report_submitted_schools').html(sanitation_report.submitted_count);
						$('.sanitation_report_not_submitted_schools').html(sanitation_report.not_submitted_count);
						sani_repo_submitted_schools_list = sanitation_report.submitted;
						sani_repo_not_submitted_schools_list = sanitation_report.not_submitted;
				},
				error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
				}
			});
		}
			
		})*/
			
			$(".bmiCases").each(function(){
				//$('input[id = age_bmi_modal]').removeClass('hidden');
				$(this).click(function (){					
					$('#load_waiting_age_bmi').modal('show');
					var bmi_type = $(this).text();
					$("#bmi_type").val(bmi_type);
					$('.bmi_selected').click(function(){
						stu_age = $('#age_bmi_modal option:selected').text();
						$("#age_bmi_type").val(stu_age);
						$("#bmi_report_form").submit();
					})
					
				});
			});
		
		$(".hbCases").each(function(){
				$(this).click(function (){
				var hb_type = $(this).text();
				$("#hb_type").val(hb_type);
				$("#hb_report_form").submit();
			})
		});

		$(".chronicCases").each(function(){
				$(this).click(function (){
				var chronic_type = $(this).text();
				$("#chronic_type").val(chronic_type);
				$("#chronic_report_form").submit();
			})
		});
		$(".emergencyCases").each(function(){
				$(this).click(function (){
				var emergencyReq = $(this).text();
				$("#emergencyReq").val(emergencyReq);
				$("#emergency_report_form").submit();
			})
		});
		$(".fieldOfficerCases").each(function(){
				$(this).click(function (){
				var fieldOfficerReq = $(this).text();
				$("#fieldOfficerReq").val(fieldOfficerReq);
				$("#fieldOfficer_report_form").submit();
			})
		});

		 // Sanitation report sent schools list
    $('.sanitation_report_submitted_schools_list').click(function(){
		if(sani_repo_submitted_schools_list!=null)
		{
	        var table="";
			var tr="";
			
			if(sani_repo_submitted_schools_list['school']!="")
			{
				$('#sani_repo_sent_school_modal_body').empty();
				table += "<table class='table table-bordered' id='sani_repo_sent_school_modal_body_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
				for(var i=0;i<sani_repo_submitted_schools_list['school'].length;i++)
				{
					var j=i+1;
					table+= "<tr><td>"+j+"</td><td>"+sani_repo_submitted_schools_list['district'][i]+"</td><td>"+sani_repo_submitted_schools_list['school'][i]+"</td><td>"+sani_repo_submitted_schools_list['mobile'][i]+"</td><td>"+sani_repo_submitted_schools_list['person_name'][i]+"</td></tr>"
				}
				table += "</tbody></table>";
				$(table).appendTo('#sani_repo_sent_school_modal_body');
				$('#sani_repo_sent_school_download').prop('disabled',false);
			}
			else
			{
				table+="<table class='table table-bordered'><tbody><tr><td>No Schools</td></tr></tbody></table>";
				$(table).appendTo('#sani_repo_sent_school_modal_body');
				$('#sani_repo_sent_school_download').prop('disabled',true);
			}
		}
		else
		{
			table+="No Schools";
			$(table).appendTo('#sani_repo_sent_school_modal_body');
		}
		$('#sani_repo_sent_school_modal').modal('show');
    })

    // Sanitation report not sent schools list
    $('.sanitation_report_not_submitted_schools_list').click(function(){
		if(sani_repo_not_submitted_schools_list!=null)
		{
	        var table= "";
			var tr   = "";
			
			if(sani_repo_not_submitted_schools_list['school']!="")
			{
				$('#sani_repo_not_sent_school_modal_body').empty();
				table += "<table class='table table-bordered' id='sani_repo_not_submitted_schools_list_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
				for(var i=0;i<sani_repo_not_submitted_schools_list['school'].length;i++)
				{
					var j=i+1;
					table+= "<tr><td>"+j+"</td><td>"+sani_repo_not_submitted_schools_list['district'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['school'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['mobile'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['person_name'][i]+"</td></tr>"
				}
				table += "</tbody></table>";
				$(table).appendTo('#sani_repo_not_sent_school_modal_body');
				$('#sani_repo_not_sent_school_download').prop('disabled',false);
			}
			else
			{
				table+="<table class='table table-bordered'><tbody><tr><td>No Schools</td></tr></tbody></table>";
				$(table).appendTo('#sani_repo_not_sent_school_modal_body');
				$('#sani_repo_not_sent_school_download').prop('disabled',true);
			}
		}
		else
		{
			table+="No Schools";
			$(table).appendTo('#sani_repo_sent_school_modal_body');
		}
		$('#sani_repo_not_sent_school_modal').modal('show');
    })


    // Sanitation report not sent schools list download
	$('#sani_repo_not_sent_school_download').click(function(){
	
		var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
      var textRange; var j=0;
      tab = document.getElementById('sani_repo_not_submitted_schools_list_tab'); // id of table

      for(j = 0 ; j < tab.rows.length ; j++)
      {    
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
      }

      tab_text=tab_text+"</table>";


      var ua = window.navigator.userAgent;
      var msie = ua.indexOf("MSIE ");

      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
      {
         txtArea1.document.open("txt/html","replace");
         txtArea1.document.write(tab_text);
         txtArea1.document.close();
         txtArea1.focus();
         sa=txtArea1.document.execCommand("SaveAs",true,"Global View Task.xls");
      } 
      else //other browser not tested on IE 11
         sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text)); 
        return (sa);
	})
	
	// Sanitation report not sent schools list
    $('.sanitation_report_not_submitted_schools_list').click(function(){
		if(sani_repo_not_submitted_schools_list!=null)
		{
	        var table= "";
			var tr   = "";
			
			if(sani_repo_not_submitted_schools_list['school']!="")
			{
				$('#sani_repo_not_sent_school_modal_body').empty();
				table += "<table class='table table-bordered' id='sani_repo_not_submitted_schools_list_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
				for(var i=0;i<sani_repo_not_submitted_schools_list['school'].length;i++)
				{
					var j=i+1;
					table+= "<tr><td>"+j+"</td><td>"+sani_repo_not_submitted_schools_list['district'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['school'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['mobile'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['person_name'][i]+"</td></tr>"
				}
				table += "</tbody></table>";
				$(table).appendTo('#sani_repo_not_sent_school_modal_body');
				$('#sani_repo_not_sent_school_download').prop('disabled',false);
			}
			else
			{
				table+="<table class='table table-bordered'><tbody><tr><td>No Schools</td></tr></tbody></table>";
				$(table).appendTo('#sani_repo_not_sent_school_modal_body');
				$('#sani_repo_not_sent_school_download').prop('disabled',true);
			}
		}
		else
		{
			table+="No Schools";
			$(table).appendTo('#sani_repo_sent_school_modal_body');
		}
		$('#sani_repo_not_sent_school_modal').modal('show');
    })

});


       $('#get_val').click(function(){

      //  $('#request_btn').hide();
      		$("#loading_modal").modal('show');
           var search = $('#get_search').val();
         //alert(search);
           $.ajax({

               url:'get_searched_student_sick_requests',
               type: 'POST',
               data:{"search_value":search},
               success: function(data){
               	$("#loading_modal").modal('hide');
               // $('#search_btn').show();
                result = $.parseJSON(data);
                   //console.log(result);
                  display_data_table(result);
               },
               error:function(XMLHttpRequest, textStatus, errorThrown)
               {
                console.log('error', errorThrown);
               }
           });

       });

       function display_data_table(result)
       {
          if(result.length > 0){
              data_table = '<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%"> <thead> <tr> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter STUDENT NAME" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter UNIQUE ID" /></th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter STUDENT SCHOOL" /> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter CLASS" /></th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter Mobile Number" /></th> </tr> <tr> <th>STUDENT NAME</th><th>HOSPITAL UNIQUE ID</th><th>STUDENT SCHOOL</th> <th>CLASS</th> <th>Mobile Number</th><th>Action</th></tr> </thead> <tbody>';

              $.each(result, function() {
                  //console.log(this.doc_data.widget_data["page2"]['Personal Information']['AD No']);
                  data_table = data_table + '<tr>';
                  data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Name'] + '</td>';
                  data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Hospital Unique ID'] + '</td>'; 
                  data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['School Name'] + '</td>';                 
                  data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['Class'] + '</td>'; 
                  data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Mobile']['mob_num'] + '</td>'; 
                var urlLink = "https://mednote.in/PaaS/healthcare/index.php/";
				var obj = Object.values(this['_id']);
				data_table = data_table + '<td><a class="btn btn-primary btn-xs" href="'+urlLink+'panacea_mgmt/panacea_reports_display_ehr_uid/?id = '+this.doc_data.widget_data["page1"]['Personal Information']['Hospital Unique ID']+'">Show EHR</a></td>';
             
                  data_table = data_table + '</tr>';
                
              });
              data_table = data_table +'</tbody></table>';
              
              $("#searched_stud_list").html(data_table);

              	//=========================================data table functions=====================================
              	
              				/* BASIC ;*/
              		var responsiveHelper_dt_basic = undefined;
              		var responsiveHelper_datatable_fixed_column = undefined;
              		var responsiveHelper_datatable_col_reorder = undefined;
              		var responsiveHelper_datatable_tabletools = undefined;
              		
              		var breakpointDefinition = {
              			tablet : 1024,
              			phone : 480
              		};
              
              		$('#dt_basic').dataTable({
              			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
              				"t"+
              				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
              			"autoWidth" : true,
              			"preDrawCallback" : function() {
              				// Initialize the responsive datatables helper once.
              				if (!responsiveHelper_dt_basic) {
              					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
              				}
              			},
              			"rowCallback" : function(nRow) {
              				responsiveHelper_dt_basic.createExpandIcon(nRow);
              			},
              			"drawCallback" : function(oSettings) {
              				responsiveHelper_dt_basic.respond();
              			}
              		});
              
              	/* END BASIC */
              	var js_url = "<?php echo JS; ?>";
              	/* COLUMN FILTER  */
                  var otable = $('#datatable_fixed_column').DataTable({
                  
              		"autoWidth" : true,
              		"preDrawCallback" : function() {
              			// Initialize the responsive datatables helper once.
              			if (!responsiveHelper_datatable_fixed_column) {
              				responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
              			}
              		},
              		"rowCallback" : function(nRow) {
              			responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
              		},
              		"drawCallback" : function(oSettings) {
              			responsiveHelper_datatable_fixed_column.respond();
              		}		
              	
                  });
                  
                  // custom toolbar
                  //$("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
                  	   
                  // Apply the filter
                  $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
                  	
                      otable
                          .column( $(this).parent().index()+':visible' )
                          .search( this.value )
                          .draw();
                          
                  } );
                  /* END COLUMN FILTER */   
                  $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
		    	
		        otable
		            .column( $(this).parent().index()+':visible' )
		            .search( this.value )
		            .draw();
		            
		    } );
        

              //=====================================================================================================
              }else{
                  $("#stud_report").html('<h5>No students to display for this school</h5>');
              }
           }

       //$('#search_btn').hide();
       $('#close_val').click(function(){
      // $('#search_btn').hide();
       $('#request_btn').hide();
       });

</script>


