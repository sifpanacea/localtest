<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "All Activities";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "";
include("inc/header.php");
//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa Activities"]["active"] = true;
include("inc/nav.php");

?>
<style>
.modal-lg{
	width: 1300px;
}
span.one{
    color: rgb(34, 194, 34);border: 2px solid rgb(34, 194, 34);
    font-size: 20px;
}

li.active span.one{
    background: #fff !important;
    border: 2px solid #ddd;
    color: rgb(34, 194, 34);
}

span.two{
     color: rgb(34, 194, 34);border: 2px solid rgb(34, 194, 34);
    font-size: 20px;
}


li.active span.two{
    background: #fff !important;
    border: 2px solid #ddd;
    color: rgb(34, 194, 34);
}

.news-item
{
    color:blue;
    //border-bottom:1px dotted #555; 
}
.nav-tabs{
	height: 72px;
}
.square{
	width:170px;
	height:30px;
	font-size: 15px;
}
.no_values{
	background-color:#00ffbf;
}
.normal{
	background-color:#00ff40;
}
.over{
	background-color:#ffff00;
}
.under{
	background-color:#ff0000;
	color: white;
}
.obese{
	background-color:#ff8000;
}
</style>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo(CSS.'admin_dash_js.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo CSS; ?>marquee.css">
<link rel="stylesheet" href="<?php echo CSS; ?>example.css">
<link rel="stylesheet" href="<?php echo CSS; ?>AdminLTE.min.css">
	<link rel="stylesheet" href="<?php echo CSS; ?>AdminLTE.css">
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>

<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->

<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
<?php
	//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
	//$breadcrumbs["New Crumb"] => "http://url.com"
	include("inc/ribbon.php");
?>
<!-- <?php $message = $this->session->flashdata('message');?> -->
<div class="row">
<div class="well well-sm col-sm-12">
				<div class="content" style="min-height: 52px;">
			<div class="simple-marquee-container">
				<div class="marquee-sibling hide">
					NEWS
				</div>
				<div class="marquee">
					<ul class="marquee-content-items">
										<?php if(count($news_feeds) > 0): ?>
										<?php foreach ($news_feeds as $news_feed):?>
									<td> <li style="list-style-type:none" class="news-item marquee-content-items" style="font-size:20px;color:white"><i class="fa fa-bell" ></i>&nbsp;&nbsp; <?php echo $news_feed["display_date"].' <i class="fa fa-lg fa-fa-calendar"></i> '.((strlen($news_feed["news_feed"])>=500)? substr($news_feed["news_feed"], 0,500)." <font size='2' color='white'><i>cont...</i></font>" : $news_feed["news_feed"]) ;?> 
													
													<?php if(isset($news_feed["file_attachment"])){
															echo ' | <img src='.IMG.'/attachment.png'.' width="40" class="img-circle" />'.count($news_feed["file_attachment"]).' attachments ';
													}?><a href="#" class="open_news" news_data="<?php echo base64_encode(json_encode($news_feed))?>" style="color:#0a0a0a"> read more ...</a>
													</li><br>
										</td>
										<?php endforeach;?>
										<?php else: ?>
										<h3> No news feed for today.</h3>
										<?php endif ?>
						</ul>
						</div>
					</div>
				</div>
           </div>
	</div>
<!-- 
		<div class="row">
 	
			<div class="pull-right">
		    <ul class="nav nav-tabs" id="myTab">
                    <div class="liner"></div>
                <li class="col">
                     <a href="http://www.paas.com/PaaS/healthcare/index.php/tmreis_schools/all_activities" title="Basic Dashboard">
                      <span class="one">
                           <i>Basic Dashboard</i>
                      </span></a></li>
              <li class="col"><a href="<?php //echo URLCustomer; ?>index.php/tmreis_schools/to_dashboard" title="Advanced Dashboard"">
                     <span class="two">
                         <i>Click Here For Advanced Dashboard</i>
                     </span></a> 
                 </li>
            </ul>
			</div> 

		</div> -->
	
	<!-- MAIN CONTENT -->
	 <div class="row container-fluid">
			   		<div class="col-md-3 col-sm-6 col-xs-12">
			          <div class="info-box">
			            <span class="info-box-icon bg-aqua"><i class="fa fa-plus-square"></i></span>

			            <div class="info-box-content">
			              <span class="info-box-text" style="font-size: 18px;">School Status.</span>
			              <span class="info-box-number">
						  <?php if($school_color_code == "No BMI values"):?>
				<p class="square no_values"><strong>No BMI values</strong></p>
				<?php endIf;?>
				<?php if($school_color_code == "under_weight"):?>
				<span  class="square under"><strong>More Under Weight cases</strong></span>
				<?php endIf;?>
				<?php if($school_color_code == "normal_weight"):?>
				<p class="square normal"><strong>Towards Healthy School</strong></p>
				<?php endIf;?>
				<?php if($school_color_code == "over_weight"):?>
				<p class="square over"><strong>More Over Weight cases</strong></p>
				<?php endIf;?>
				<?php if($school_color_code == "obese"):?>
				<p class="square obese"><strong>More Obese</strong></p>
				<?php endIf;?>
												
			              </span><br>
						   <footer style="font-size:xx-small;">Based On BMI Values</footer>
			            </div>
						
			            <!-- /.info-box-content -->
			          </div>
			          <!-- /.info-box -->
			        </div>

			         <div class="col-md-3 col-sm-6 col-xs-12">
			          <div class="info-box">
			            <span class="info-box-icon bg-red"><i class="fa fa-plus-square"></i></span>
			            <div class="info-box-content">
			              <span class="info-box-text">Total Screened Students</span>
			              <span class="info-box-number"><?php echo "$basic_dash_screening_report_yearly"; ?></span>
			            </div>
			            <!-- /.info-box-content -->
			          </div>
			          <!-- /.info-box -->
			        </div>
        
			        <div class="col-md-3 col-sm-6 col-xs-12">
			          <div class="info-box">
			           <span class="info-box-icon bg-green"><i class="fa fa-list"></i></span>
			            <div class="info-box-content">
			              <span class="info-box-text">Total Requests</span>
			              <span class="info-box-number"><?php echo "$basic_dash_yearly_request_count"; ?></span>
			            </div>
			            <!-- /.info-box-content -->
			          </div>
			          <!-- /.info-box -->
			        </div>
			        <!-- /.col -->
			        <div class="col-md-3 col-sm-6 col-xs-12">
			          <div class="info-box">
			            <span class="info-box-icon bg-yellow"><i class="fa fa-info-circle"></i></span>
			            <div class="info-box-content">
			              <span class="info-box-text">Total Students</span>
			              <span class="info-box-number">
			              	<h4><span><?php if(!empty($basic_dash_students_count)) {?><?php echo $basic_dash_students_count;?><?php } else {?><?php echo "0";?><?php }?></span></h4>
			              </span>
			            </div>
			            <!-- /.info-box-content -->
			          </div>
			          <!-- /.info-box -->
			        </div>
	</div>
<!-- <center></center> -->
<div class="tab-pane fade in active" id="home">
	<div id="content">	
		<div class="row">
		<div class="col col-lg-3">
		 <div class="panel panel-primary">
           <div class="panel-heading" style="background-color: #ef5c0bc9; color: #f0f8ff;">Daily Health Issues</div>
				<table class="table table-sm">
				  <thead>
				    <tr>
				      <th scope="col" style="background-color: #DAF7A6;">Request Type</th>
				      <th scope="col" style="background-color: #DAF7A6;">Count</th>
				    </tr>
				  </thead>
				  <tbody>
				    <tr>
				     <th scope="row">
					 <a href="#" data-toggle="modal" data-target="#EmergencyRequestsInfo" style="color:#0033cc">Emergency Request</a></th>
				     <td><span class="badge bg-color-red"><?php echo $emergency_requests; ?></span></td>
				    </tr>
				    <tr>
				      <th scope="row">
						<a href="#" data-toggle="modal" data-target="#NormalRequestsInfo" style="color:#0033cc">Normal Request </a></th>
				      <td><span class="badge bg-color-red"><?php echo $normal_requests; ?></span></td>
				    </tr>
				    <tr>
				      <th scope="row">
						<a href="#" data-toggle="modal" data-target="#ChronicRequestsInfo" style="color:#0033cc">Chronic Request </a></th> 
				      <td colspan="2"><span class="badge bg-color-red"><?php echo $chronic_requests; ?></span></td>
				    </tr>
				  </tbody>
				  </table>
				  <div class="panel-footer" style="background-color: #6b718c;"><a class="btn btn-primary" href="<?php echo URLCustomer.'index.php/tmreis_schools/hs_request' ?>">Submit Request</a>
				</div>
				</div>
    		  </div>

    	<div class="col col-lg-3">
			 <div class="panel panel-primary">
         		<div class="panel-heading" style="background-color: #ef5c0bc9; color: #f0f8ff;">Doctor Responses</div>
				<table class="table table-sm">
				  <thead>
				    <tr>
				      <th scope="col" style="background-color: #DAF7A6;">Request Type</th>
				      <th scope="col" style="background-color: #DAF7A6;">Count</th>
				    </tr>
				  </thead>
				  <tbody>
				    <tr>
				     <th scope="row">
					 <a href="#" data-toggle="modal" data-target="#doctorEmergencyRequestsInfo" style="color:#0033cc">Emergency Request</a></th>
				     <td><span class="badge bg-color-red"><?php echo $doc_emergency_requests; ?></span></td>
				    </tr>
				    <tr>
				      <th scope="row">
						<a href="#" data-toggle="modal" data-target="#doctorNormalRequestsInfo" style="color:#0033cc">Normal Request </a></th>
				      <td><span class="badge bg-color-red"><?php echo $doc_normal_requests; ?></span></td>
				    </tr>
				    <tr>
				      <th scope="row">
						<a href="#" data-toggle="modal" data-target="#doctorChronicRequestsInfo" style="color:#0033cc">Chronic Request </a></th> 
				      <td colspan="2"><span class="badge bg-color-red"><?php echo $doc_chronic_requests; ?></span></td>
				    </tr>
				  </tbody>
				  </table>
				   <div class="panel-footer" style="background-color: #6b718c;">
				    <a href="<?php echo URLCustomer.'index.php/tmreis_schools/fetch_submited_requests_docs' ?>">	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateRequests">Update Request </button></a>
				   </div>
				</div>
    		  </div>

    	  <div class="col col-lg-3">
			 <div class="panel panel-primary">
         		<div class="panel-heading" style="background-color: #ef5c0bc9; color: #f0f8ff;">BMI</div>
				<table class="table table-sm">
				  <thead>
				    <tr>
				      <th scope="col" style="background-color: #DAF7A6;">Students</th>
				      <th scope="col" style="background-color: #DAF7A6;">Count</th>
				    </tr>
				  </thead>
				  <tbody>
				    <tr>
				     <th scope="row">
				      <a href="#" data-toggle="modal" data-target="#UnderWeightInfo" style="color:#0033cc">Under Weight</a></th>
				     <td><span class="badge bg-color-red"><?php echo $under_weight; ?></span></td>
				    </tr>
				    <tr>
				      <th scope="row">
					 <a href="#" data-toggle="modal" data-target="#NormalWeightInfo" style="color:#0033cc">Healthy Weight</a></th>
				     <td><span class="badge bg-color-red"><?php echo $normal_weight; ?></span></td>
				    </tr>
				    <tr>
				      <th scope="row">
					 <a href="#" data-toggle="modal" data-target="#OverWeightInfo" style="color:#0033cc">Over Weight</a></th>
				     <td><span class="badge bg-color-red"><?php echo $over_weight; ?></span></td>
				    </tr>
				    <tr>
				      <th scope="row">
					 <a href="#" data-toggle="modal" data-target="#ObeseInfo" style="color:#0033cc">Obese</a></th>
				     <td><span class="badge bg-color-red"><?php echo $obese; ?></span></td>
				    </tr>
				  </tbody>
				  </table>
				   <div class="panel-footer" style="background-color: #6b718c;"><a href="<?php echo URLCustomer.'index.php/tmreis_schools/feedBmiStudentReport' ?>"><button type="button" class="btn btn-primary">Submit BMI
				   </button></a></div>
				</div>
    		  </div>

    	<!-- HB Values Table -->
    	 <div class="col col-lg-3">
			 <div class="panel panel-primary">
         		<div class="panel-heading" style="background-color: #ef5c0bc9; color: #f0f8ff;">HB VALUES</div>
				<table class="table table-sm">
				  <thead>
				    <tr>
				      <th scope="col" style="background-color: #DAF7A6;">HB Type</th>
				      <th scope="col" style="background-color: #DAF7A6;">Count</th>
				    </tr>
				  </thead>
				  <tbody>
				    <tr>
				     <th scope="row">
				      <a href="#" data-toggle="modal" data-target="#severeinfo" style="color:#0033cc">Severe</a></th>
				     <td><span class="badge bg-color-red"><?php echo count($severe_anamia); ?></span></td>
				    </tr>
				    <tr>
				      <th scope="row">
					 <a href="#" data-toggle="modal" data-target="#moderateinfo" style="color:#0033cc">Moderate</a></th>
				     <td><span class="badge bg-color-red"><?php echo count($moderate_anamia); ?></span></td>
				    </tr>
				    <tr>
				      <th scope="row">
					 <a href="#" data-toggle="modal" data-target="#mildinfo" style="color:#0033cc">Mild</a></th>
				     <td><span class="badge bg-color-red"><?php echo count($mild_anamia); ?></span></td>
				    </tr>
				    <tr>
				      <th scope="row">
					 <a href="#" data-toggle="modal" data-target="#normalinfo" style="color:#0033cc">Normal</a></th>
				     <td><span class="badge bg-color-red"><?php echo count($normal_anamia); ?></span></td>
				    </tr>
				  </tbody>
				  </table>
				   <div class="panel-footer" style="background-color: #6b718c;"><a href="<?php echo URLCustomer.'index.php/tmreis_schools/initiateHemoglobinReport' ?>"><button type="button" class="btn btn-primary">Submit HB
				   </button></a></div>
				</div>
    		  </div>
    	</div>

    <div class="row">

       	 <div class="col col-lg-3">
				<div class="panel panel-primary">
           		<div class="panel-heading" style="background-color: #ef5c0bc9; color: #f0f8ff;">Daily Health Attendance</div>
				<div class="panel-body">
	           		<?php if(!empty($report_count)) :?>
				<table class="table table-sm">
					  <form action="drill_down_absent_to_students_load_ehr_basic" method="POST">
				<thead>
				    <tr>
				      <th scope="col" style="background-color: #DAF7A6;">Description</th>
				      <th scope="col" style="background-color: #DAF7A6;">Count</th>
				    </tr>
				</thead>
				<tbody>
				   <tr>
				    	<th scope="row" style="color: blue;">Total Attended </th><td><span class="badge bg-color-red"> <?php echo $report_count[0]['doc_data']['widget_data']['page1']['Attendence Details']['Attended']; ?></span></td>
				    </tr>
				    <tr>

				    	<th scope="row" style="color: blue;"><input type="hidden" name="ehr_data_for_absent" id='ehr_data_for_absent_input' value=""><button type="submit" id='ehr_data_for_absent'>Total Absent </button></th>
				    	<td><span class="badge bg-color-red"> <?php echo $report_count[0]['doc_data']['widget_data']['page1']['Attendence Details']['Absent']; ?></span></td>
				    </tr>
				    <tr>
				    	<th scope="row" style="color: blue;"><input type="hidden" name="ehr_data_for_sick" id='ehr_data_for_sick_input' value=""><button type="submit" id='ehr_data_for_sick'>Total Sick</button></th>
						<td><span class="badge bg-color-red"> <?php echo $report_count[0]['doc_data']['widget_data']['page1']['Attendence Details']['Sick']; ?></span></td>
				    </tr>
				    <tr>
				    	<th scope="row" style="color: blue;"><input type="hidden" name="ehr_data_for_r2h" id='ehr_data_for_r2h_input' value=""><button type="submit" id='ehr_data_for_r2h'>Total R2H</button></th>
						<td><span class="badge bg-color-red"> <?php echo $report_count[0]['doc_data']['widget_data']['page1']['Attendence Details']['R2H']; ?></span></td>
				    </tr>
				    <tr>
				    	<th scope="row" style="color: blue;"><input type="hidden" name="ehr_data_for_restroom" id='ehr_data_for_restroom_input' value=""><button type="submit" id='ehr_data_for_restroom'>Rest Room</button></th>
						<td><span class="badge bg-color-red"> <?php echo $report_count[0]['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom'] ?></span></td>
				    </tr>
				  </tbody>
						<tr>
					    <?php else: ?>
						<td><h4 style="color: red;">Attendance <br>Not submitted Today<br>please<br>Submit.</h4><a href="<?php echo URLCustomer.'index.php/tmreis_schools/initiateAttendanceReport' ?>"><button type="button" class="btn btn-info">Submit Attendance</button></a></td>
						</tr>
				    <?php endif; ?>
				</form>
				  </table>
				 </div>
				</div>
    		  </div>

    	<div class="col col-lg-3">
			 	<div class="panel panel-primary">
         		<div class="panel-heading" style="background-color: #ef5c0bc9; color: #f0f8ff;">Screening Details</div>
				<table class="table table-sm">
				  <thead>
				    <tr>
				      <th scope="col" style="background-color: #DAF7A6;">Abnormalities</th>
				      <th scope="col" style="background-color: #DAF7A6;">Count</th>
				    </tr>
				  </thead>
				  <tbody>
				      
				      	<?php $i=0; 
				      	foreach($screening_info as $screening): 
				      		$i++;
				      		?>
				      		<tr>
							
								<th scope="row" style="color: blue;"><a href="#" data-toggle="modal" data-target="#screening_modal" id="<?php echo $i; ?>" class="screening_info" style="color:#0033cc"><?php echo $screening['label']; ?></a><input type="hidden" id="labelName" value="<?php echo $screening['label']; ?>"></th><td><span class="badge bg-color-red"><?php echo $screening['value']; ?> </span></td>
								
								
				      		<!--	<th><span id="<?php echo $i; ?>" class="details"><?php echo $screening['label']; ?></span></th> -->
				      			<!-- <th><a href="#" data-toggle="modal" data-target="#screening_info_modal" style="color:#0033cc"><?php echo $screening['label']; ?></a></th> -->
				      		<!--	<td><span class="badge bg-color-red"><?php echo $screening['value']; ?></span></td> -->
							</tr>
				      	<?php endforeach; ?>
				    
				  </tbody>
				  </table>
				</div>
    		  </div>
    		  <!----==============Sanitation Report====================================-->
    	<div class="col col-lg-3">
			<div class="panel panel-primary" style="">
         		<div class="panel-heading" style="background-color: #ef5c0bc9; color: #f0f8ff;">Daily Sanitation Details</div>
         			<div class="panel-body">
         				<?php if(!empty($sanitation_report)) :?>
				    <tr>
				    	<?php else: ?>
					<td>
						<p style="font-size: 20px; color: red; font-family: ">Sanitation<br> Not submitted Today<br>Please Submit.<br><a href="<?php echo URLCustomer.'index.php/tmreis_schools/initiateSanitationReport' ?>"><button type="button" class="btn btn-info">Submit Sanitation</button></a></p>
					</td>
						<?php endif; ?>
				    </tr>
				    <?php if($sanitation_report): ?>
					<div class="panel-group smart-accordion-default" id="accordion-2">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseOne-1"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> Campus </a></h4>
							</div>

							<div id="collapseOne-1" class="panel-collapse collapse">
								<div class="panel-body">
								<table class="table table-bordered"><thead>
									<thead>
									<th>Item</th>
									<th>Value</th>
										<?php foreach($sanitation_report['campus'] as $campus): ?>
									<tr></tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<?php echo $campus['label']; ?>
											</td>
											<td>
												<?php echo $campus['value']; ?>
											</td>
											
										</tr>
												<?php endforeach; ?>
												<tr><td>
													<?php foreach($sanitation_report['campus_attachments'] as $external_attachments): ?>
										      <a href="<?php echo URLCustomer.$external_attachments['file_path']; ?>" rel="prettyPhoto" class="thumbnail col-sm-2"><img src="<?php echo URLCustomer.$external_attachments['file_path']; ?>"></a>
													<?php endforeach; ?>
												</td></tr>
									</tbody>
								</table>
								</div>
							</div>
						</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseTwo-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> kitchen </a></h4>
						</div>
						<div id="collapseTwo-1" class="panel-collapse collapse">
							<div class="panel-body">
								<table class="table table-bordered">
									<thead>
									<th>Item</th>
									<th>Value</th>
										<?php foreach($sanitation_report['kitchen'] as $kitchen): ?>
									<tr></tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<?php echo $kitchen['label']; ?>
											</td>
											<td>
												<?php echo $kitchen['value']; ?>
											</td>
										</tr>
												<?php endforeach; ?>
										<tr><td>
													<?php foreach($sanitation_report['kitchen_attachments'] as $external_attachments): ?>
										      <a href="<?php echo URLCustomer.$external_attachments['file_path']; ?>" rel="prettyPhoto" class="thumbnail col-sm-2"><img src="<?php echo URLCustomer.$external_attachments['file_path']; ?>"></a>
													<?php endforeach; ?>
										</td></tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseThree-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> Toilet</a></h4>
						</div>
						<div id="collapseThree-1" class="panel-collapse collapse">
							<div class="panel-body">
								<table class="table table-bordered">
									<thead>
										<th>Item</th>
										<th>Value</th>
												<?php foreach($sanitation_report['toilets'] as $toilets): ?>
										<tr></tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<?php echo $toilets['label']; ?>
											</td>
											<td>
												<?php echo $toilets['value']; ?>
											</td>
										</tr>
												<?php endforeach; ?>
										<tr><td>
													<?php foreach($sanitation_report['toilets_attachments'] as $external_attachments): ?>
										      <a href="<?php echo URLCustomer.$external_attachments['file_path']; ?>" rel="prettyPhoto" class="thumbnail col-sm-2"><img src="<?php echo URLCustomer.$external_attachments['file_path']; ?>"></a>
													<?php endforeach; ?>
										</td></tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
						<?php if(!empty($sanitation_report['water_Supply_Condition'])) : ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseFour-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i>Water Supply Condition. </a></h4>
						</div>
						<div id="collapseFour-1" class="panel-collapse collapse">
							<div class="panel-body">
								<table class="table table-bordered">
									<thead>
										<th>Item</th>
										<th>Value</th>
												<?php foreach($sanitation_report['water_Supply_Condition'] as $water): ?>
										<tr></tr>
										</thead>
										<tbody>
											<tr>
												<td><?php echo $water['label']; ?></td>
												<td><?php echo $water['value']; ?></td>
											</tr>
												<?php endforeach; ?>
										</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php else : ?>
				<?php endif; ?>
				
					<?php if($sanitation_report['dormitories']) : ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseFive-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i>Dormitories. </a></h4>
						</div>
						<div id="collapseFive-1" class="panel-collapse collapse">
							<div class="panel-body">
								<table class="table table-bordered">
									<thead>
										<th>Item</th>
										<th>Value</th>
											<?php foreach($sanitation_report['dormitories'] as $dormitory): ?>
										<tr></tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo $dormitory['label']; ?></td>
											<td><?php echo $dormitory['value']; ?></td>
										</tr>
										<?php endforeach; ?>

										<tr><td>
													<?php foreach($sanitation_report['dormitories_attachments'] as $external_attachments): ?>
										      <a href="<?php echo URLCustomer.$external_attachments['file_path']; ?>" rel="prettyPhoto" class="thumbnail col-sm-2"><img src="<?php echo URLCustomer.$external_attachments['file_path']; ?>"></a>
													<?php endforeach; ?>
										</td></tr>

									</tbody>
								</table>
							</div>
						</div>
					</div>

					<?php else : ?>
				<?php endif; ?>
				
				<?php if($sanitation_report['store']): ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseSix-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i>Store. </a></h4>
						</div>
						<div id="collapseSix-1" class="panel-collapse collapse">
							<div class="panel-body">
								<table class="table table-bordered">
									<thead>
										<th>Item</th>
										<th>Value</th>
											<?php foreach($sanitation_report['store'] as $store): ?>
										<tr></tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo $store['label']; ?></td>
											<td><?php echo $store['value']; ?></td>
										</tr>
													<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php else : ?>
				<?php endif; ?>
				
				
				<?php if($sanitation_report['waste_management']): ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseSeven-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i>Waste Management. </a></h4>
						</div>
						<div id="collapseSeven-1" class="panel-collapse collapse">
							<div class="panel-body">
								<table class="table table-bordered">
									<thead>
										<th>Item</th>
										<th>Value</th>
											<?php foreach($sanitation_report['waste_management'] as $waste): ?>
										<tr></tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo $waste['label']; ?></td>
											<td><?php echo $waste['value']; ?></td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php else: ?>
				<?php endif; ?>
				
				<?php if($sanitation_report['water']): ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseEight-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i>Water. </a></h4>
						</div>
						<div id="collapseEight-1" class="panel-collapse collapse">
							<div class="panel-body">
								<table class="table table-bordered">
									<thead>
										<th>Item</th>
										<th>Value</th>
											<?php foreach($sanitation_report['water'] as $water): ?>
										<tr></tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo $water['label']; ?></td>
											<td><?php echo $water['value']; ?></td>
										</tr>
													<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php else : ?>
				<?php endif; ?>

				</div>
					<?php  else:?>
					<?php endif; ?>
    		</div>
			
			</div>
    	</div>
    		  <!---=================end Sanitation Report=========--->
    		  <!--==================Weekly Doctor visiting  Start=================================== -->
    		  <div class="col col-lg-3">
				<div class="panel panel-primary">
           		<div class="panel-heading" style="background-color: #ef5c0bc9; color: #f0f8ff;">Doctor Checking In school.</div>
				<fieldset>
                          <div class="form-group">
                              <div id="chartContainer" style="height: 265px; width: 80%;"></div>
                            </div>
                        </fieldset>
                        <form style="display: hidden" action="drill_down_to_doctor_treated_list" method="POST" 
                    id="ehr_data_form">
                        <input type="hidden" id="selected_date" name="selected_date" value=""/>
                        
                      </form>
				</div>
    		  </div>
    		  <!--==================Weekly Doctor visiting  end=================================== -->

    	   </div>
		</div>
  	</div>
  </div>
  

 <!-- Modal For HS Normal Requests Table -->
 
					 <div class="modal fade bd-example-modal-lg" id="NormalRequestsInfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-lg">
					    <div class="modal-content">
					    	<div class="modal-header" style="background-color: #ff3547;">
					            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222</h2></p>
					         </div>
					     	<div class="table-responsive">
									<table class="table">
									  <thead>
									    <tr>
									      <th scope="col">Unique ID</th>
									      <th scope="col">Name</th>
									      <th scope="col">Class</th>
									      <th scope="col">Section</th>
									      <th scope="col">Problem Info</th>
									      <th scope="col">Status</th>
									      <th scope="col">EHR </th>
									    </tr>
									  </thead>
									  <?php if(isset($normal_requests_info)): ?>

									  <?php foreach ($normal_requests_info as $row) :?>
									  <tbody>
									  <tr>
									  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Unique ID']; ?></td>
									  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref']; ?></td>
									  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref']; ?></td>
									  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Section']['field_ref']; ?></td>
									   <td>
									  	  <?php foreach ($row['doc_data']['widget_data']['page1']['Problem Info']['Normal'] as $normal) : ?>
									  		<?php foreach ($normal as $sick) :?>
									  	    <?php echo $sick; ?>
									  	    <?php endforeach; ?>
									        <?php endforeach; ?>
									  </td>
									  <td><?php echo $row['doc_data']['widget_data']['page2']['Review Info']['Status']; ?></td>
									  <td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
										
									   </tr>
									  </tbody>

									  
									  <?php endforeach; ?>
									  <?php else: ?>
										<td>	
											<?php echo "No Normal Requests Today."; ?></td>
										<?php endif; ?>
									</table>
									</div>
									<div class="modal-footer">
									  	<button type="button" data-dismiss='modal'>Close</button>
									</div>
								</div>
							</div>
						</div>


 <!-- Modal For Doctor Normal Requests Reply Table -->
 
					 <div class="modal fade bd-example-modal-lg" id="doctorNormalRequestsInfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-lg">
					    <div class="modal-content">
					    	<div class="modal-header" style="background-color: #ff3547;">
					            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222</h2></p>
					         </div>
					     	<div class="table-responsive">
									<table class="table">
										<!-- <h2 style="color: red; font-family: initial;">PANACEA HELP LINE NO : 040-45511222</h2> -->
									  <thead>
									    <tr>
									      <th scope="col">Unique ID</th>
									      <th scope="col">Name</th>
									      <th scope="col">Class</th>
									      <th scope="col">Section</th>
									      <th scope="col">Problem Info</th>
									      <th scope="col">Doctor Response Type</th>
									    
									      <th scope="col">EHR </th>
									    </tr>
									  </thead>
									  <?php if(isset($doc_normal_requests_info)): ?>
									  <?php foreach ($doc_normal_requests_info as $row) :?>
									  <tbody>
									  <tr>
									  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Unique ID']; ?></td>
									  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref']; ?></td>
									  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref']; ?></td>
									  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Section']['field_ref']; ?></td>
									   <td>
									  	  <?php foreach ($row['doc_data']['widget_data']['page1']['Problem Info']['Normal'] as $normal) : ?>
									  		<?php foreach ($normal as $sick) :?>
									  	    <?php echo $sick; ?>
									  	    <?php endforeach; ?>
									        <?php endforeach; ?>
									  </td>
									  <td><?php echo $row['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice']; ?></td>
									
									   <td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
									   </tr>
									  </tbody>
									<?php endforeach; ?>
									<?php else: ?>
									<td><?php echo "No Requests Submitted Today."; ?></td>
								    <?php endif; ?>
									</table>
									</div>
									<div class="modal-footer">
									  	<button type="button" data-dismiss='modal'>Close</button>
									</div>
								</div>
							</div>
						</div>

	
<!-- Modal For HS Emergency Requests Table -->
						 <div class="modal fade bd-example-modal-lg" id="EmergencyRequestsInfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

						  <div class="modal-dialog modal-lg">
						    <div class="modal-content">
						    	<div class="modal-header" style="background-color: #ff3547;">
						            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222</h2></p>
						         </div>
						     	<div class="table-responsive">
										<table class="table">
										  <thead>
										    <tr>
										      <th scope="col">Unique ID</th>
										      <th scope="col">Name</th>
										      <th scope="col">Class</th>
										      <th scope="col">Section</th>
										      <th scope="col">Problem Info</th>
										      <th scope="col">Status</th>
										      <th scope="col">EHR </th>
										    </tr>
										  </thead>
										  <tbody>
									<?php if(isset($emergency_requests_info)) :?>


										  <?php foreach ($emergency_requests_info as $row) :?>
										  <tr>
										  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Unique ID']; ?></td>'
										   <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref']; ?></td>
										  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref']; ?></td>
										  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Section']['field_ref']; ?></td>
										   <td>
										  	  <?php foreach ($row['doc_data']['widget_data']['page1']['Problem Info']['Emergency'] as $emergency) : ?>
										  		<?php foreach ($emergency as $sick) :?>
										  	    <?php echo $sick; ?>
										  	    <?php endforeach; ?>
										        <?php endforeach; ?>
										  </td>
										  <td><?php echo $row['doc_data']['widget_data']['page2']['Review Info']['Status']; ?></td>
										  <td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
										<?php endforeach; ?>
										<?php else: ?>
										<td>	
											<?php echo "No Emergency Requests Today."; ?></td>
										<?php endif; ?>
										 </tr>
										</tbody>
										</table>
										</div>
										<div class="modal-footer">
										  	<button type="button" data-dismiss='modal'>Close</button>
										 </div>
									</div>
								</div>
						    </div>


 <!-- Modal For Doctor Emergency Requests Reply Table -->

							 <div class="modal fade bd-example-modal-lg" id="doctorEmergencyRequestsInfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							  <div class="modal-dialog modal-lg">
							    <div class="modal-content">
							    	<div class="modal-header" style="background-color: #ff3547;">
							            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222</h2></p>
							         </div>
							     	<div class="table-responsive">
											<table class="table">
											  <thead>
											    <tr>
											      <th scope="col">Unique ID</th>
											      <th scope="col">Name</th>
											      <th scope="col">Class</th>
											      <th scope="col">Section</th>
											      <th scope="col">Problem Info</th>
											      <th scope="col">Doctor Response Type</th>
											    
											      <th scope="col">EHR </th>
											    </tr>
											  </thead>
											  <?php if(isset($doc_emergency_requests_info)): ?>
											  <?php foreach ($doc_emergency_requests_info as $row) :?>
											  <tbody>
											  <tr>
											  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Unique ID']; ?></td>
											   <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref']; ?></td>
											  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref']; ?></td>
											  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Section']['field_ref']; ?></td>
											   <td>
											  	  <?php foreach ($row['doc_data']['widget_data']['page1']['Problem Info']['Emergency'] as $emergency) : ?>
											  		<?php foreach ($emergency as $sick) :?>
											  	    <?php echo $sick; ?>
											  	    <?php endforeach; ?>
											        <?php endforeach; ?>
											  </td>
											  <td><?php echo $row['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice']; ?></td>
											 
											   <td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
											   </tr>
											  </tbody>
											<?php endforeach; ?>
											 <?php else : ?>
										     <td><?php echo "No Requests Submitted Today."; ?></td>
										    <?php endif; ?>
											</table>
											</div>
											<div class="modal-footer">
											  	<button type="button" data-dismiss='modal'>Close</button>
											</div>
										</div>
									</div>
								</div>
 
<!-- Modal For HS Chronic Requests Table -->
							 <div class="modal fade bd-example-modal-lg" id="ChronicRequestsInfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

							  <div class="modal-dialog modal-lg">
							    <div class="modal-content">
							    	<div class="modal-header" style="background-color: #ff3547;">
							            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222</h2></p>
							         </div>
							     	<div class="table-responsive">
											<table class="table">
											  <thead>
											    <tr>
											      <th scope="col">Unique ID</th>
											      <th scope="col">Name </th>
											      <th scope="col">Class </th>
											      <th scope="col">Section </th>
											      <th scope="col">Problem Info</th>
											      <th scope="col">Status</th>
											      <th scope="col">EHR </th>
											    </tr>
											  </thead>
											  	<?php if(isset($chronic_requests_info)): ?>
											   <?php foreach ($chronic_requests_info as $row) :?>
											  <tbody>
											  <tr>
											  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Unique ID']; ?></td>
											  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref']; ?></td>
											  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref']; ?></td>
											  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Section']['field_ref']; ?></td>
											   <td>
											  	  <?php foreach ($row['doc_data']['widget_data']['page1']['Problem Info']['Chronic'] as $chronic) : ?>
											  		<?php foreach ($chronic as $sick) :?>
											  	    <?php echo $sick; ?>
											  	    <?php endforeach; ?>
											        <?php endforeach; ?>
											  </td>
											  <td><?php echo $row['doc_data']['widget_data']['page2']['Review Info']['Status']; ?></td>
											  <td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
											   </tr>
											  </tbody>
											  <?php endforeach; ?>
											  <?php else: ?>
											<td><?php echo "No Requests Submitted Today."; ?></td>
											<?php endif; ?>
											</table>
											</div>
											<div class="modal-footer">
											  	<button type="button" data-dismiss='modal'>Close</button>
											 </div>
										</div>
									 </div>
								</div>

<!-- Modal For Doctor Chronic Requests Reply Table -->
  
						 <div class="modal fade bd-example-modal-lg" id="doctorChronicRequestsInfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						  <div class="modal-dialog modal-lg">
						    <div class="modal-content">
						    	<div class="modal-header" style="background-color: #ff3547;">
						            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222</h2></p>
						         </div>
						     	<div class="table-responsive">
										<table class="table">
										  <thead>
										    <tr>
										      <th scope="col">Unique ID</th>
										      <th scope="col">Name</th>
										      <th scope="col">Class</th>
										      <th scope="col">Section</th>
										      <th scope="col">Problem Info</th>
										      <th scope="col">Doctor Response Type</th>
										    
										      <th scope="col">EHR</th>
										    </tr>
										  </thead>
										  <?php if(isset($doc_chronic_requests_info)) : ?>
										  <?php foreach ($doc_chronic_requests_info as $row) :?>
										  <tbody>
										  <tr>
										  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Unique ID']; ?></td>
										  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref']; ?></td>
										  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref']; ?></td>
										  <td><?php echo $row['doc_data']['widget_data']['page1']['Student Info']['Section']['field_ref']; ?></td>
										   <td>
										  	  <?php foreach ($row['doc_data']['widget_data']['page1']['Problem Info']['Chronic'] as $chronic) : ?>
										  		<?php foreach ($chronic as $sick) :?>
										  	    <?php echo $sick; ?>
										  	    <?php endforeach; ?>
										        <?php endforeach; ?>
										  </td>
										  <td><?php echo $row['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice']; ?></td>z`
										 

										   <td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
										   </tr>
										  </tbody>
										<?php endforeach; ?>
										<?php else: ?>
										<td><?php echo "No Requests Submitted Today."; ?></td>
									    <?php endif; ?>
										</table>
										</div>
										<div class="modal-footer">
										  	<button type="button" data-dismiss='modal'>Close</button>
										</div>
									</div>
								</div>
							</div>

<!-- Modal For Under Weight BMI REPORT Table -->
				<div class="modal fade bd-example-modal-lg" id="UnderWeightInfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							 <div class="modal-content">
							    <div class="modal-header" style="background-color: #ff3547;">
							            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222<br>Under Weight Students</h2></p>
							         </div>
							     		<div class="table-responsive">
											<table class="table">
											  <thead>
											    <tr>
											      <th scope="col">Unique ID</th>
											      <th scope="col">Name</th>
											      <th scope="col">Class</th>
											      <th scope="col">Section</th>
											      <th scope="col">Height</th>
											      <th scope="col">Weight</th>
											      <th scope="col">BMI </th>
											      <th scope="col">EHR</th>
											    </tr>
											  </thead>
											 <?php if(isset($under_weight_info)): ?>
											 <?php foreach ($under_weight_info as $row) :?>
											  <tbody>
											 <tr>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']; ?></td>
											<?php $latest_bmi = end($row['doc_data']['widget_data']['page1']['Student Details']['BMI_values']); ?>
											<td><?php echo $latest_bmi['height']; ?></td>
											<td><?php echo $latest_bmi['weight']; ?></td>
											<td><?php echo $latest_bmi['bmi']; ?></td>
											<td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
										</tr>
									  </tbody>
									  <?php endforeach; ?>
									   <?php else: ?>
										<td><?php echo "No Data"; ?> </td>
									    <?php endif; ?>
								</table>
							</div>
											<div class="modal-footer">
											  	<button type="button" data-dismiss='modal'>Close</button>
											 </div>
										</div>
								    </div>
								</div>

<!-- Modal For Normal Weight BMI REPORT Table -->
				<div class="modal fade bd-example-modal-xl" id="NormalWeightInfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							 <div class="modal-content">
							    <div class="modal-header" style="background-color: #ff3547;">
							            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222<br>Normal Weight Students</h2></p>
							         </div>
							     			<div class="table-responsive">
											<table class="table">
											  <thead>
											    <tr>
											      <th scope="col">Unique ID</th>
											      <th scope="col">Name</th>
											      <th scope="col">Class</th>
											      <th scope="col">Section</th>
											      <th scope="col">Height</th>
											      <th scope="col">Weight</th>
											      <th scope="col">BMI </th>
											      <th scope="col">EHR</th>
											    </tr>
											  </thead>
								             <?php if(isset($normal_weight_info)) : ?>
								             <?php foreach ($normal_weight_info as $row) :?>
							            <tbody>
								        <tr>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']; ?></td>
											<?php $latest_bmi = end($row['doc_data']['widget_data']['page1']['Student Details']['BMI_values']); ?>
											<td><?php echo $latest_bmi['height']; ?></td>
											<td><?php echo $latest_bmi['weight']; ?></td>
											<td><?php echo $latest_bmi['bmi']; ?></td>
											<td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
										</tr>
								       </tbody> 	
											<?php endforeach; ?>
											<?php else: ?>
											<td><?php echo "No data" ?></td>
											<?php endif; ?>
										</table>
										</div>
											<div class="modal-footer">
											  	<button type="button" data-dismiss='modal'>Close</button>
											 </div>
										</div>
								    </div>
								</div>

<!-- Modal For Over Weight BMI REPORT Table -->
				<div class="modal fade bd-example-modal-xl" id="OverWeightInfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							 <div class="modal-content">
							    <div class="modal-header" style="background-color: #ff3547;">
							            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222<br>Over Weight Students</h2></p>
							         </div>
							     			<div class="table-responsive">
											<table class="table">
											  <thead>
											    <tr>
											      <th scope="col">Unique ID</th>
											      <th scope="col">Name</th>
											      <th scope="col">Class</th>
											      <th scope="col">Section</th>
											      <th scope="col">Height</th>
											      <th scope="col">Weight</th>
											      <th scope="col">BMI </th>
											      <th scope="col">EHR</th>
											    </tr>
											  </thead>
									<?php if(isset($over_weight_info)) : ?>
									<?php foreach ($over_weight_info as $row) :?>
								<tbody>
									<tr>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']; ?></td>
											<?php $latest_bmi = end($row['doc_data']['widget_data']['page1']['Student Details']['BMI_values']); ?>
											<td><?php echo $latest_bmi['height']; ?></td>
											<td><?php echo $latest_bmi['weight']; ?></td>
											<td><?php echo $latest_bmi['bmi']; ?></td>
											<td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
										</tr>
								</tbody> 
								<?php endforeach; ?>
								<?php else :?>
								<td><?php echo "No Data"; ?></td>
							     <?php endif; ?>
							    </table>
							</div>
									<div class="modal-footer">
									  <button type="button" data-dismiss='modal'>Close</button>
									</div>
								</div>
						    </div>
						</div>


<!-- Modal For Obese BMI REPORT Table -->
				<div class="modal fade bd-example-modal-xl" id="ObeseInfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							 <div class="modal-content">
							    <div class="modal-header" style="background-color: #ff3547;">
							            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222<br>Obese Students</h2></p>
							         </div>
							     			<div class="table-responsive">
											<table class="table">
											  <thead>
											    <tr>
											      <th scope="col">Unique ID</th>
											      <th scope="col">Name</th>
											      <th scope="col">Class</th>
											      <th scope="col">Section</th>
											      <th scope="col">Height</th>
											      <th scope="col">Weight</th>
											      <th scope="col">BMI </th>
											      <th scope="col">EHR</th>
											    </tr>
											  </thead>
								<?php if(isset($obese_info)) :?>
							    <?php foreach ($obese_info as $row) :?>
						<tbody>
							<tr>	
								<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']; ?></td>
								<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']; ?></td>
								<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']; ?></td>
								<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']; ?></td>
											<?php $latest_bmi = end($row['doc_data']['widget_data']['page1']['Student Details']['BMI_values']); ?>
								<td><?php echo $latest_bmi['height']; ?></td>
								<td><?php echo $latest_bmi['weight']; ?></td>
								<td><?php echo $latest_bmi['bmi']; ?></td>
								<td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
										</tr>
								</tbody>
											<?php endforeach; ?>
									<?php else: ?>
									<td><?php echo "No Data"; ?></td>
								    <?php endif; ?>
											</table>
											</div>
											<div class="modal-footer">
											  	<button type="button" data-dismiss='modal'>Close</button>
											 </div>
										</div>
								    </div>
								</div>

<!-- Modal For Severe Anamia REPORT Table -->
				<div class="modal fade bd-example-modal-xl" id="severeinfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							 <div class="modal-content">
							    <div class="modal-header" style="background-color: #ff3547;     color: aliceblue;">
							            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222<br>Severe Anemia Students</h2></p>
							         </div>
							     			<div class="table-responsive">
											<table class="table">
											  <thead>
											    <tr>
											      <th scope="col">Unique ID</th>
											      <th scope="col">Name</th>
											      <th scope="col">Class</th>
											      <th scope="col">Section</th>
											      <th scope="col">Blood Group</th>
											      <th scope="col">Latest HB Value (mg)</th>
											      <th scope="col">Submitted Date and Time </th>
											      <th scope="col">EHR</th>
											    </tr>
											  </thead>
											 <?php foreach ($severe_anamia_info as $row) :?>
									<tbody>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['bloodgroup']['field_ref']; ?></td>
											<?php $latest_hb = end($row['doc_data']['widget_data']['page1']['Student Details']['HB_values']); ?>
											<td><?php echo $latest_hb['hb']; ?></td>
											<td><?php echo $row['history']['last_stage']['time']; ?></td>
											<td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
										</tr>
								</tbody> 	
											<?php endforeach; ?>
									</table>
								</div>	
											<div class="modal-footer">
											  	<button type="button" data-dismiss='modal'>Close</button>
											 </div>
										</div>
								    </div>
								</div>
<!-- Modal For Moderate HB REPORT Table -->
				<div class="modal fade bd-example-modal-xl" id="moderateinfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							 <div class="modal-content">
							    <div class="modal-header" style="background-color: #ff3547;">
							            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222<br>Moderate Anemia Students</h2></p>
							         </div>
							     			<div class="table-responsive">
											<table class="table">
											  <thead>
											    <tr>
											      <th scope="col">Unique ID</th>
											      <th scope="col">Name</th>
											      <th scope="col">Class</th>
											      <th scope="col">Section</th>
											      <th scope="col">Blood Group</th>
											      <th scope="col">Latest HB Value (mg)</th>
											      <th scope="col">Submitted Date and Time </th>
											      <th scope="col">EHR</th>
											    </tr>
											  </thead>
									<?php foreach ($moderate_anamia_info as $row) :?>
									<tbody>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['bloodgroup']['field_ref']; ?></td>
											<?php $latest_hb = end($row['doc_data']['widget_data']['page1']['Student Details']['HB_values']); ?>
											<td><?php echo $latest_hb['hb']; ?></td>
											<td><?php echo $row['history']['last_stage']['time']; ?></td>
											<td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
										</tr>
								</tbody> 
											<?php endforeach; ?>
											</table>
										</div>
											
											<div class="modal-footer">
											  	<button type="button" data-dismiss='modal'>Close</button>
											 </div>
										</div>
								    </div>
								</div>

<!-- Modal For mild HB REPORT Table -->
				<div class="modal fade bd-example-modal-xl" id="mildinfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							 <div class="modal-content">
							    <div class="modal-header" style="background-color: #ff3547;">
							            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222<br>Mild Anemia Students</h2></p>
							         </div>
							     			<div class="table-responsive">
											<table class="table">
											  <thead>
											    <tr>
											      <th scope="col">Unique ID</th>
											      <th scope="col">Name</th>
											      <th scope="col">Class</th>
											      <th scope="col">Section</th>
											      <th scope="col">Blood Group</th>
											      <th scope="col">Latest HB Value (mg)</th>
											      <th scope="col">Submitted Date and Time </th>
											      <th scope="col">EHR</th>
											    </tr>
											  </thead>
											 
											 <?php foreach ($mild_anamia_info as $row) :?>
										<tbody>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['bloodgroup']['field_ref']; ?></td>
											<?php $latest_hb = end($row['doc_data']['widget_data']['page1']['Student Details']['HB_values']); ?>
											<td><?php echo $latest_hb['hb']; ?></td>
											<td><?php echo $row['history']['last_stage']['time']; ?></td>
											<td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
										</tr>
								</tbody> 
								
											<?php endforeach; ?>
									</table>
								</div>
											
											<div class="modal-footer">
											  	<button type="button" data-dismiss='modal'>Close</button>
											 </div>
										</div>
								    </div>
								</div>

<!-- Modal For Normal HB REPORT Table -->
				<div class="modal fade bd-example-modal-xl" id="normalinfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							 <div class="modal-content">
							    <div class="modal-header" style="background-color: #ff3547;">
							            <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222<br>Normal Anemia Students</h2></p>
							         </div>
							     			<div class="table-responsive">
											<table class="table">
											  <thead>
											    <tr>
											      <th scope="col">Unique ID</th>
											      <th scope="col">Name</th>
											      <th scope="col">Class</th>
											      <th scope="col">Section</th>
											      <th scope="col">Blood Group</th>
											      <th scope="col">Latest HB Value (mg)</th>
											      <th scope="col">Submitted Date and Time </th>
											      <th scope="col">EHR</th>
											    </tr>
											  </thead>
											 
											 <?php foreach ($normal_anamia_info as $row) :?>
										<tbody>
											 <tr>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']; ?></td>
											<td><?php echo $row['doc_data']['widget_data']['page1']['Student Details']['bloodgroup']['field_ref']; ?></td>
											<?php $latest_hb = end($row['doc_data']['widget_data']['page1']['Student Details']['HB_values']); ?>
											<td><?php echo $latest_hb['hb']; ?></td>
											<td><?php echo $row['history']['last_stage']['time']; ?></td>
											<td><a href='<?php echo URL."tmreis_schools/get_students_load_ehr_doc_basic_dashboard/".$row['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];?>'>
										  <button class="btn btn-primary btn-xs">Show EHR</button>
										  </a></td>
										</tr>
								</tbody> 
											<?php endforeach; ?>
											</table>
											</div>
											<div class="modal-footer">
											  	<button type="button" data-dismiss='modal'>Close</button>
											 </div>
										</div>
								    </div>
								</div>
	
<!-- Modal For Screening Reports Table -->
<!-- Modal -->
  <div class="modal fade" id="screening_modal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #ff3547;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <p class="heading lead" style="color: white; font-family: initial;">PANACEA HELP LINE NO : 040-45511222<br>Screening Details</p>
        </div>
       <div class="modal-body">
					<div class="table-responsive">
						<table class="table">
							<?php $i=1; ?>
						<?php	foreach($screening_info as $screening): 
						  ?>		
							 <tr id="data<?php echo $i; ?>" class="popup-data">
							 	
							 	<div id="abnormalties_report_table">
					
								</div>
							 </tr><?php
								 $i++; ?>	
							 <?php endforeach; ?>
								
						</table>
							<form style="display: hidden" action="drill_down_screening_to_students_load_ehr_count" method="POST" id="ehr_form">
									  <input type="hidden" id="ehr_data" name="ehr_data" value=""/>
									  <input type="hidden" id="ehr_navigation" name="ehr_navigation" value=""/>
									</form>
					</div>
				</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>



<!-- ==========================CONTENT ENDS HERE ========================== -->




<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>
<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
 <script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.barnumbers.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<script src="<?php echo JS; ?>jquery.bootstrap.newsbox.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS; ?>marquee.js" type="text/javascript" charset="utf-8"></script>

<script src="<?php echo JS; ?>admin_dash.js"></script>
<script src="<?php echo JS; ?>highcharts.js"></script>
<script src="<?php echo JS; ?>highcharts-more.js"></script>


<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> -->
<script>
$('a[title]').tooltip();
$(document).ready(function() {

$("a[rel^='prettyPhoto']").prettyPhoto();
$('.simple-marquee-container').SimpleMarquee();

	$('#ehr_data_for_restroom').click(function(){
		
		$('#ehr_data_for_restroom_input').val("<?php echo $report_count[0]['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom UID'];?>");
	})

	$('#ehr_data_for_r2h').click(function(){
		
		$('#ehr_data_for_r2h_input').val("<?php echo $report_count[0]['doc_data']['widget_data']['page1']['Attendence Details']['R2H UID'];?>");
	})

	$('#ehr_data_for_sick').click(function(){
		
		$('#ehr_data_for_sick_input').val("<?php echo $report_count[0]['doc_data']['widget_data']['page1']['Attendence Details']['Sick UID'];?>");
	})

	$('#ehr_data_for_absent').click(function(){
		
		$('#ehr_data_for_absent_input').val("<?php echo $report_count[0]['doc_data']['widget_data']['page2']['Attendence Details']['Absent UID'];?>");
	})


		$(".show_bmi_graph").each(function(){
		$(this).click(function (){
		   var selectedID = $(this).val();
		   var month_data = "";
		
		
		graph_values = [];
			$.ajax({
				url  : "student_bmi_graph",
			    type : "POST",
				data : { 'uid': selectedID },
				success : function(response){
					if(response=='NO_GRAPH')
					{
						
				$.SmartMessageBox({
					title : "Alert!",
					content : "For This Unique ID There is No BMI Graph",
					buttons : '[OK]'
				})
						$('#line_graph,#legend').empty();
						$('.bmi_tip').addClass('hide');
					}
					else
					{
					
				try
				{
					var line_chart_data = JSON.parse(response);
					var graph_data = line_chart_data.graph_data;
					month_data = line_chart_data.month_data;
					console.log("GD==",graph_data);
					console.log("MD==",month_data);
					var obj = {'label':'BMI Graph','data':graph_data};
					graph_values.push(obj);
					
		          
					$.plot($("#line_graph"), graph_values, {
					series: 
					{
					lines : {show: true},
					points: {show: true}
					},
					xaxis : {
					mode: 'time',
					tickSize: [1, "month"],
					timeformat:"%b %y",
					axisLabel: "Months",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 20
					},
					grid: 
					{
					borderColor: 'black',
					borderWidth: 1
					},
					legend: 
					{
					show: true,
					container: '#legend'    
					},
					grid: { hoverable: true, clickable: true },
					yaxis : {
					min: 0,
					max: 50,
					tickSize:5,
					axisLabel:"BMI",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 10
					}
					});
					line_graph_plothover();
					
					$('.bmi_tip').removeClass('hide');
				
       
 
       
				}
				catch(e)
				{
				
				} 
					}
					
				}
				
			})
		
		 function showTooltip(x, y, color, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 120,
                border: '2px solid ' + color,
                padding: '3px',
                'font-size': '9px',
                'border-radius': '5px',
                'background-color': '#fff',
                'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                opacity: 0.9
            }).appendTo("body").fadeIn(200);
        }
		
		function line_graph_plothover(){
			
			var previousPoint = null, previousLabel = null;
			
		$("#line_graph").bind("plothover", function (event, pos, item) {
				console.log("ITEM==",item);
                if (item) {
					
                    if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                        previousPoint = item.dataIndex;
                        previousLabel = item.series.label;
                        $("#tooltip").remove();
 
                        var x = item.datapoint[0];
                        var y = item.datapoint[1];
 
                        var color = item.series.color;
                       
						showTooltip(item.pageX,
                            item.pageY,
                            color,
                            "<strong>Height : </strong>" + month_data[0][x]['height'] + " cm<br><strong>Weight : </strong>" + month_data[0][x]['weight'] + " kg<br><strong>BMI : </strong>" + y + "");
                        
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
		}

		$('#reset').click(function (){
			$('#student_code').val("");
		})
		  

		});
		});

	var today_date = $('#set_data').val();

	
	var request_pie_span = "Monthly";
	var request_data = "";
	previous_request_a_value = [];
	previous_request_title_value = [];
	previous_request_search = [];
	search_arr = [];

	var identifiers_data = "";
	previous_identifiers_a_value = [];
	previous_identifiers_title_value = [];
	previous_identifiers_search = [];
	search_arr = [];

	


$('.datepicker').datepicker({
	minDate: new Date(1900, 10 - 1, 25)
 });
 
initialize_variables(today_date);

change_to_default();

function change_to_default(today_date,absent_report,request_report,symptoms_report,screening_report){
	$('#request_pie_span').val("Monthly");
	
}
/*******************************************************************
 *
 * Helper : Initialize for request/identifiers pie
 *
 */
 
function init_req_id_pie(request_report,symptoms_report)
{
	request_data 			 	 = request_report;
	previous_request_a_value 	 = [];
	previous_request_title_value = [];
	previous_request_search 	 = [];
	search_arr 					 = [];
	
	identifiers_data 			     = symptoms_report;
	previous_identifiers_a_value     = [];
	previous_identifiers_title_value = [];
	previous_identifiers_search 	 = [];
	search_arr 						 = [];
}
/*******************************************************************
 *
 * Helper : Initialize dashboard pie's
 *
 */
 
function initialize_variables(today_date,request_report,symptoms_report)
{
	
	today_date = today_date;
	
	
	init_req_id_pie(request_report,symptoms_report);
	
}


		// Absent list sent schools list
$('.Request_pie_modal').click(function(){
	
	
/*******************************************************************
 *
 * Helper : Initialize for screening pie
 *
 */


draw_request_pie();

draw_identifiers_pie();

$('#request_pie_span').change(function(e){
	request_pie_span = $('#request_pie_span').val();
	$( "#req_id_pies" ).hide();
	$("#loading_req_pie").show();
	console.log('error', today_date);
	console.log('error', request_pie_span);
	$.ajax({
		url: 'update_request_pie',
		type: 'POST',
		data: {"today_date" : today_date, "request_pie_span" : request_pie_span},
		success: function (data) {			
			$("#loading_req_pie").hide();
			$( "#req_id_pies" ).show();
			
			$( "#pie_request" ).empty();
			$( "#pie" ).empty();
			
			data = $.parseJSON(data);
			request_report = $.parseJSON(data.request_report);
			symptoms_report = $.parseJSON(data.symptoms_report);
			console.log("request_pie=======",request_report);	
			console.log("symptoms_report=======",symptoms_report);	
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



$('#set_date_btn').click(function(e){
	today_date = $('#set_data').val();

	$.ajax({
		url: 'to_dashboard_with_date',
		type: 'POST',
		data: {"today_date" : today_date, "request_pie_span" : request_pie_span},
		success: function (data) {
			$('#load_waiting').modal('hide');
			$( "#pie_request" ).empty();
			$( "#pie" ).empty();
			
			
			data = $.parseJSON(data);
			request_report = $.parseJSON(data.request_report);
			symptoms_report = $.parseJSON(data.symptoms_report);
			

			
			initialize_variables(today_date,request_report,symptoms_report);
			
			draw_identifiers_pie();
			draw_request_pie();
		

		
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});



/*******************************************************************
 *
 * Helper : Identifiers Pie
 *
 *
 */
 
function identifiers_pie(heading, identifiers_data, onClickFn){
	var pie = new d3pie("pie", {
		header: {
			title: {
				text: heading
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth : 400
	    },
	    data: {
	      content: identifiers_data
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
					
					if(onClickFn == "drill_down_identifiers_to_students"){
					    
						drill_down_identifiers_to_students(a.data.label);
					
						
					}
					
				}
			}
	});
}

/*******************************************************************
 *
 * Helper : Draw identifiers pie
 *
 * @author  Vikas
 *
 */
 
function draw_identifiers_pie()
{
	if(identifiers_data == 1)
	{
		console.log('in false of identifiers');
		$("#pie").append('No positive values to dispaly');
	}
	else
	{
		identifiers_pie("Identifiers Pie Chart",identifiers_data,"drill_down_identifiers_to_students");
	}
}

/*******************************************************************
 *
 * Helper : Identifiers Pie - drill to students
 *
 * @author  Vikas
 *
 */
 
function drill_down_identifiers_to_students(identifier)
{
	$.ajax({
		url: 'drill_down_identifiers_to_students',
		type: 'POST',
		data: {"identifier" : identifier, "today_date" : today_date, "request_pie_span" : request_pie_span},
		success: function (data) 
		{
		    $("#ehr_data_for_identifiers").val(data);
			$("#ehr_form_for_identifiers").submit();
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});
}

/*******************************************************************
 *
 * Helper : Request Pie
 *
 *
 */
 
 function request_pie(heading, request_data, onClickFn)
 {
	var pie = new d3pie("pie_request", {
		header: {
			title: {
				text: heading
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth : 400
	    },
	    labels: {
	        inner: {
	            format: "value"
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
				    console.log(a);
				    console.log(onClickFn);
					if (onClickFn == "drill_down_request_to_students"){
						drill_down_request_to_students(a.data.label.trim());
					}
				}
			}
	      
	});
}

/*******************************************************************
 *
 * Helper : Draw request pie
 *
 *
 */
 
function draw_request_pie()
{
	if(request_data == 1)
	{
		$("#pie_request").append('No positive values to dispaly');
	}
	else
	{
		request_pie("Request Pie Chart",request_data,"drill_down_request_to_students");
	}
}

/*******************************************************************
 *
 * Helper : Request Pie - drill to students
 *
 *
 */
 
function drill_down_request_to_students(request_label)
{
    console.log(request_label);
	$.ajax({
		url: 'drill_down_request_to_students',
		type: 'POST',
		data: {"data" : request_label, "today_date" : today_date, "request_pie_span" : request_pie_span},
		success: function (data) 
		{
		    console.log(data);
			$("#ehr_data_for_request").val(data);
			$("#ehr_form_for_request").submit();
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});
}
	$('#Request_pie_modal').modal('show');
});



$(document).on('click','.open_news',function(){
		//alert("newssssssssssssssssssssssssssssssssssss")
		var news_data = $(this).attr("news_data");
		console.log('news_data',news_data);
		news_obj = JSON.parse(atob(news_data))
		console.log('news_obj',news_obj);
		var news_details = '<p><h3>Time:</h3>'+news_obj.display_date+'</p><p style="word-wrap: break-word;"><h3>News:</h3>'+news_obj.news_feed+'</p>';
		if (news_obj.hasOwnProperty('file_attachment')){
			news_obj.file_attachment.forEach(function(entry) {
			    console.log('entry',entry);
			    url = "../../"+entry.file_path.substr(2);
			    news_details = news_details + '<embed src="'+url+'" width="100%" height="100%" autoplay="false" controller="false">'
			});}
		$("#news_body").html(news_details);
		$('#news_modal').modal("show");
		});	

	$("#news_modal").on('hidden.bs.modal', function(){
		$("#news_body").empty();
});

	$('.popup-data').hide();
		var that;
		
		$(".screening_info").each(function(){
			$(this).click(function (){
	
        	var selectedLabel = $(this).text();
        	  $.ajax({

				url: 'get_screening_data_with_abnormalities',
				type: 'POST',
				data: {"selectedLabel" : selectedLabel},
				success: function (data) {
					
					console.log('abnormalities', data);
					abnormalities = $.parseJSON(data);
						that = $(this).attr("id")
		
						$('.popup-data').hide();

						$('#data'+that+'').show()
						
						$('#screening_modal').modal('show');

						$('#absent_sent_school_modal_body').empty();
						

						displayScreeningAbnormalitiesTable(abnormalities,selectedLabel);

						
					
					},
				    error:function(XMLHttpRequest, textStatus, errorThrown)
					{
					// console.log('error', errorThrown);
				    }
				});

		
		})
	})

		function displayScreeningAbnormalitiesTable(abnormalities,selectedLabel){
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
				$("#abnormalties_report_table").html('<h5>No Screening data available</h5>');
			}

			$(".abnormality_label_btn").each(function(){
			 	$(this).click(function (){
	        		var symptome_type = $(this).val();
	        		  $.ajax({
						url: 'drill_down_screening_to_students_count',
						type: 'POST',
						data: {"symptome_type" : symptome_type},
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

$(function () {
	
$(".demo1").bootstrapNews({
newsPerPage: 10,
navigation: true,
autoplay: true,
direction:'up', // up or down
animationSpeed: 'normal',
newsTickerInterval: 4000, //4 secs
pauseOnHover: true,
onStop: null,
onPause: null,
onReset: null,
onPrev: null,
onNext: null,
onToDo: null
});
});

});


</script>
<script>

 var a = <?php echo json_encode($all); ?>;

var chart = Highcharts.chart('chartContainer', {

  title: {
    text: 'Doctor Checkup'
  },

  subtitle: {
    text: 'Doctor visiting Dates'
  },

  xAxis: {
    categories: a[0]
  },
  yAxis:{
  	title:{
  		text:"no of students",
  	}
  },

  series: [{
    type: 'column',
    colorByPoint: true,
    data: a[1],
    showInLegend: false,
    point: {
                events: {
                    click: function () {

                        $('#selected_date').val(this.category);
                        $('#ehr_data_form').submit();
                        

                    }
                }
            },
  }]

});


$('#plain').click(function () {
  chart.update({
    chart: {
      inverted: false,
      polar: false
    },
    subtitle: {
      text: 'Plain'
    }
  });
});

$('#inverted').click(function () {
  chart.update({
    chart: {
      inverted: true,
      polar: false
    },
    subtitle: {
      text: 'Inverted'
    }
  });
});

$('#polar').click(function () {
  chart.update({
    chart: {
      inverted: false,
      polar: true
    },
    subtitle: {
      text: 'Polar'
    }
  });
});

</script>
